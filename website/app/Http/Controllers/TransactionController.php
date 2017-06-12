<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{

    private $brokerFee = 50.00;

    private $buyPercentage = (1.00 / 100);
    private $sellPercentage = (0.25 / 100);

    private $buyPercentageMass = (0.75 / 100);
    private $sellPercentageMass = (0.1875 / 100);

    private $buyPercentageMassThreshold = 1000;
    private $sellPercentageMassThreshold = 500;


    public function apiAddBuyTransaction(Request $request)
    {
        //Check that the user is signed in
        if (!Auth::check())
            return $this->returnError("User not logged in", 403);

        //Make sure this is a POST request
        if (!$request->isMethod('POST'))
            return $this->returnError("Invalid call to add buy transaction", 403);

        $quantity = $request->quantity;

        //If the quantity is not an integer, return an error
        if (!ctype_digit($quantity))
            return $this->returnError("Stock Quantity must be an integer", 412);

        else if ($quantity <= 0)
            return $this->returnError("Stock Quantity must be greater than 0", 412);

        $user = Auth::user();

        //For safety, all Database queries are surrounded by Try Catch, if there is an error, ohh yes, it will be caught
        try {
            //Get the latest price for the stock
            $stock = DB::table('stocks')->where('id', intval($request->stock_id))->first();
            $price = $stock->current_price;

            //Get the balance of this Trade Account
            $balance = $user->balance;
        }
        catch (\Exception $exception)
        {
            return $this->returnError("Unable to query Stock and Trade Account information", 404);
        }

        //Get the total cost of Stock Purchase without the percentage or Broker Fee
        $totalCost = ($price * $request->quantity);

        //Add percentage based on threshold
        if ($request->quantity < $this->buyPercentageMassThreshold)
            $totalCost += $totalCost * $this->buyPercentage;
        else
            $totalCost += $totalCost * $this->buyPercentageMass;

        //Add the Broker Fee
        $totalCost += $this->brokerFee;

        //If the Trade User does not have enough to cover the cost, send back error
        if ($totalCost > $balance)
            return $this->returnError("Not enough funds for transaction", 404);

        //Add transaction to database
        $saveResult = $this->addTransactionToDatabase($price, $request->quantity, $request->stock_id, $request->TradeAccountId, true);

        //If for some reason it did not save, send back error message to user
        if (!$saveResult)
            return $this->returnError("An error occurred saving the new transaction, please try again", 403);

        //Update the User Balance
        $newBalance = $balance - $totalCost;
        $user->balance = $newBalance;
        $user->save();


        //If all went well, send back a nice message and a 200 status code
        $returnData = array();
        $returnData["message"] = $totalCost;
        $returnData["code"] = 200;
        return json_encode($returnData);
    }

    public function apiAddSellTransaction(Request $request)
    {

        //Check that the user is signed in
        if (!Auth::check())
            return $this->returnError("User not logged in", 403);

        $stock_id = $request->stock_id;
        $quantity = $request->quantity;

        //If the quantity is not an integer, return an error
        if (!ctype_digit($quantity))
            return $this->returnError("Stock Quantity must be an integer", 412);
        //If the quantity is less than 0, return precondition error
        else if ($quantity <= 0)
            return $this->returnError("Stock Quantity must be greater than 0", 412);

        //Make sure this is a POST request
        if (!$request->isMethod('POST'))
            return $this->returnError("Invalid call to add buy transaction", 403);

        $user = Auth::user();

        //For safety, all Database queries are surrounded by Try Catch, if there is an error, ohh yes, it will be caught
        try {
            //Get the latest price for the stock
            $stock = DB::table('stocks')->where('id', intval($request->stock_id))->first();
            $price = $stock->current_price;

            //Get the balance of this Trade Account
            $tradeAccount = DB::table('trade_accounts')->where('id', intval($request->trade_account_id))->first();

            $balance = $user->balance;

            //Get the number of Stocks held by Trade Account
            $stocks = $this->getStingleStockCount($stock_id, $tradeAccount->id);
        }
        catch (\Exception $exception)
        {
            return $this->returnError("Unable to query Stock, Transaction and Trade Account information", 404);
        }

        //If trade account does not have enough stocks for the quantity to sell, return an error.
        if ($stocks < $request->quantity)
        {
            return $this->returnError("Sell Quantity must be equal to or less than the number of Stocks Held", 404);
        }

        //Save it to the database
        $saveResult = $this->addTransactionToDatabase($price, $request->quantity, $request->stock_id, $request->trade_account_id, false);

        //If for some reason it did not save, send back error message to user
        if (!$saveResult)
            return $this->returnError("An error occurred saving the new transaction, please try again", 403);

        //Get total Sell Profit, without Broker Fee and Fee
        $totalSell = ($price * $request->quantity);

        //Subtract percentage based on threshold
        if ($request->quantity < $this->sellPercentageMassThreshold)
            $totalSell -= $totalSell * $this->sellPercentage;
        else
            $totalSell -= $totalSell * $this->sellPercentageMass;

        //Subtract the Broker's Fee
        $totalSell -= $this->brokerFee;

        //Update the User Balance
        $newBalance = $balance + $totalSell;
        $user->balance = $newBalance;
        $user->save();

        //If all went well, send back a nice message and a 200 status code
        $returnData = array();
        $returnData["message"] = $totalSell;
        $returnData["code"] = 200;
        return json_encode($returnData);
    }

    /**
     * Get quantity of Stock that transactions have record of for a single Trade Account
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getSingleStockQuantity(Request $request)
    {
        $error = array();

        //Check that the user is signed in
        if (!Auth::check())
        {
            $error["message"] = "User not logged in";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        //Get the stock ID of interest
        $stock_id = $request->stock_id;
        //Get the Trade Account ID of interest
        $trade_account_id = $request->trade_account_id;

        //Get all transactions that a Trade Account has of a curtain stock
        $transactions = DB::table('transactions')->where('trade_account_id', $trade_account_id)->get();

        //If the Trade Account does not have any Stocks registered in their Transactions
        //return 0 stock count with 200 OK status
        if ($transactions == null)
            return response(0, 200);

        //Loop through all transactions the Trade Account has and add up (or subtract) to the total number of Stocks held
        $stocks = 0;
        foreach ($transactions as $transaction)
        {
            if($transaction->stock_id == $stock_id)
            {
                $stocks += $transaction->bought;
                $stocks -= $transaction->sold;
            }
        }

        //Return the stock count with a 200 OK status
        return response($stocks, 200);
    }

    private function getStingleStockCount($stock_id, $trade_account_id)
    {
        $error = array();

        //Check that the user is signed in
        if (!Auth::check())
        {
            $error["message"] = "User not logged in";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        //Get all transactions that a Trade Account has of a curtain stock
        $transactions = DB::table('transactions')->where('trade_account_id', $trade_account_id)->get();

        //If the Trade Account does not have any Stocks registered in their Transactions
        //return 0 stock count with 200 OK status
        if ($transactions == null)
            return 0;

        //Loop through all transactions the Trade Account has and add up (or subtract) to the total number of Stocks held
        $stocks = 0;
        foreach ($transactions as $transaction)
        {
            if($transaction->stock_id == $stock_id)
            {
                $stocks += $transaction->bought;
                $stocks -= $transaction->sold;
            }
        }

        //Return the stock count with a 200 OK status
        return $stocks;
    }

    public function getTransactionsInDateRange(Request $request)
    {
        //TODO: Add checks for request input

        //Extract the POST info from the request
        $start = intval($request->start);
        $end = intval($request->end);
        $tradeAccountId = intval($request->trade_account_id);

        //Make a call to the transactions table to get the transactions within the specified dates and the correct Trade Account ID
        //Also join the information from the stocks table
        //Not that there has to be a select function attached when doing this join, as the updated_at and created_at columns
        //created a conflict and Laravel/SQL where guessing which table to pick from, BE EXPLICIT!!
        //Order by date (timestamp), newest first
        $transactions = DB::table('transactions')->where([
            ['trade_account_id', '=', $tradeAccountId],
            ['timestamp', '>=', $start],
            ['timestamp', '<=', $end]
        ])->join('stocks', 'transactions.stock_id', '=', 'stocks.id')
            ->select('transactions.*', 'stocks.stock_name', 'stocks.stock_symbol', 'stocks.market')
            ->orderBy('transactions.timestamp', 'desc')
            ->get();

        return response($transactions, 200);
    }

    /**
     * Add transaction to the database
     *
     * @param $price - Price the stock is at time of transaction
     * @param $quantity - Quantity that is to be bought or sold
     * @param $stock_id - ID of the Stock that is being bought or sold
     * @param $trade_account_id - Trade Account ID that the transaction will be tied to
     * @param bool $buy - Boolean, True: buying Stock, False: selling Stock
     * @return bool - Boolean, if the transaction was successful or not
     */
    private function addTransactionToDatabase($price, $quantity, $stock_id, $trade_account_id, $buy = false)
    {
        //TradeAccountId, stock_id, quantity

        //Create and fill the new Transaction object
        $data = new Transaction;
        $data->timestamp = time();

        //Check if buying or selling stocks, cant do both at the same time
        //Must be separate transactions
        if ($buy)
        {
            $data->bought = $quantity;
            $data->sold = 0;
        }
        else
        {
            $data->bought = 0;
            $data->sold = $quantity;
        }

        $data->price = $price;
        $data->waiting = false;

        //Foreign keys
        $data->stock_id = $stock_id;
        $data->trade_account_id = intval($trade_account_id);

        //Save it to the database, and return the result
        return $data->save();
    }

    private function returnError($message, $code = 404)
    {
        $error["message"] = $message;
        $error["code"] = $code;
        return response(json_encode($error), $code);
    }
}
