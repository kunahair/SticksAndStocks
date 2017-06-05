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
        $error = array();

        //Check that the user is signed in
        if (!Auth::check())
        {
            $error["message"] = "User not logged in";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        //Make sure this is a POST request
        if (!$request->isMethod('POST'))
        {
            $error["message"] = "Invalid call to add buy transaction";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        $quantity = $request->quantity;

        //If the quantity is not an integer, return an error
        if (!ctype_digit($quantity))
            return response("Stock Quantity must be an integer", 412);

        else if ($quantity >! 0)
            return response("Stock Quantity must be greater than 0", 412);

        $user = Auth::user();

        //For safety, all Database queries are surrounded by Try Catch, if there is an error, ohh yes, it will be caught
        try {
            //Get the latest price for the stock
            $stock = DB::table('stocks')->where('id', intval($request->stock_id))->first();
            $price = $stock->current_price;

            //Get the balance of this Trade Account
//            $tradeAccount = DB::table('trade_accounts')->where('id', $request->TradeAccountId)->first();
            $balance = $user->balance;
        }
        catch (\Exception $exception)
        {
            $error["message"] = "Unable to query Stock and Trade Account information";
            $error["code"] = 404;
            return response(json_encode($error), 404);
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
        {
            $error["message"] = "Not enough funds for transaction";
            $error["code"] = 404;
            return response(json_encode($error), 404);
        }


        //TradeAccountId, stock_id, quantity

        //Create and fill the new Transaction object
        $data = new Transaction;
        $data->timestamp = time();
        $data->bought = $request->quantity;
        $data->sold = 0;
        $data->price = $price;
        $data->waiting = false;

        //Foreign keys
        $data->stock_id = $request->stock_id;
        $data->trade_account_id = $request->TradeAccountId;

        //Save it to the database
        $saveResult = $data->save();

        //If for some reason it did not save, send back error message to user
        if (!$saveResult)
        {
            $error["message"] = "An error occurred saving the new transaction, please try again";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

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
        $error = array();

        $stock_id = $request->stock_id;

        //Check that the user is signed in
        if (!Auth::check())
        {
            $error["message"] = "User not logged in";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        //Make sure this is a POST request
        if (!$request->isMethod('POST'))
        {
            $error["message"] = "Invalid call to add buy transaction";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        $user = Auth::user();

        //For safety, all Database queries are surrounded by Try Catch, if there is an error, ohh yes, it will be caught
        try {
            //Get the latest price for the stock
            $stock = DB::table('stocks')->where('id', intval($request->stock_id))->first();
            $price = $stock->current_price;

            //Get the balance of this Trade Account
            $tradeAccount = DB::table('trade_accounts')->where('id', intval($request->trade_account_id))->first();
//            $balance = intval($tradeAccount->balance);

            $balance = $user->balance;

            //Get the quantity of selected stock this trading account has
            $transactions = DB::table('transactions')->where('trade_account_id', $tradeAccount->id)->get();

            $stocks = 0;
            foreach ($transactions as $transaction)
            {
                if($transaction->stock_id == $stock_id)
                {
                    $stocks += $transaction->bought;
                    $stocks -= $transaction->sold;
                }
            }
        }
        catch (\Exception $exception)
        {
            $error["message"] = "Unable to query Stock, Transaction and Trade Account information";
            $error["code"] = 404;
            return response(json_encode($error), 404);
        }

        //If trade account does not have enough stocks for the quantity to sell, return an error.
        if ($stocks < $request->quantity)
        {
            $error["message"] = "Sell Quantity must be equal to or less than the number of Stocks Held";
            $error["code"] = 404;
            return response(json_encode($error), 404);
        }


        //TradeAccountId, stock_id, quantity

        //Create and fill the new Transaction object
        $data = new Transaction;
        $data->timestamp = time();
        $data->bought = 0;
        $data->sold = $request->quantity;
        $data->price = $price;
        $data->waiting = false;

        //Foreign keys
        $data->stock_id = $request->stock_id;
        $data->trade_account_id = intval($request->trade_account_id);

        //Save it to the database
        $saveResult = $data->save();

        //If for some reason it did not save, send back error message to user
        if (!$saveResult)
        {
            $error["message"] = "An error occurred saving the new transaction, please try again";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

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
        return json_encode($data);
    }

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

        $stock_id = $request->stock_id;
        $trade_account_id = $request->trade_account_id;

        $transactions = DB::table('transactions')->where('trade_account_id', $trade_account_id)->get();

        $stocks = 0;
        foreach ($transactions as $transaction)
        {
            if($transaction->stock_id == $stock_id)
            {
                $stocks += $transaction->bought;
                $stocks -= $transaction->sold;
            }
        }


        return response($stocks, 200);
    }

    public function getTransactionsInDateRange(Request $request)
    {
        //TODO: Add auth check, move Route from API to web so we can check user has the auth to access this info

        //Extract the POST info from the request
        $start = intval($request->start);
        $end = intval($request->end);
        $tradeAccountId = intval($request->trade_account_id);

        //Make a call to the transactions table to get the transactions within the specified dates and the correct Trade Account ID
        //Also join the information from the stocks table
        //Not that there has to be a select function attached when doing this join, as the updated_at and created_at columns
        //created a conflict and Laravel/SQL where guessing which table to pick from, BE EXPLICIT!!
        $transactions = DB::table('transactions')->where([
            ['trade_account_id', '=', $tradeAccountId],
            ['timestamp', '>=', $start],
            ['timestamp', '<=', $end]
        ])->join('stocks', 'transactions.stock_id', '=', 'stocks.id')
            ->select('transactions.*', 'stocks.stock_name', 'stocks.stock_symbol', 'stocks.market')
            ->get();

        return response($transactions, 200);
    }
}
