<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
 * Transaction BUY call to add a new transaction that is buying stock
 * @param Request $request
 */
    public function addBuyTransaction(Request $request)
    {
        $data = new Transaction;
        $data->timestamp = time();
        $data->bought = 0;
        $data->sold = 100;
        $data->price = 13.01;
        $data->waiting = false;

        $data->stock_id = 100;
        $data->trade_account_id = 2;

        $data->save();

    }

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

        //For safety, all Database queries are surrounded by Try Catch, if there is an error, ohh yes, it will be caught
        try {
            //Get the latest price for the stock
            $stock = DB::table('stocks')->where('id', intval($request->stock_id))->first();
            $price = $stock->current_price;

            //Get the balance of this Trade Account
            $tradeAccount = DB::table('trade_accounts')->where('id', $request->TradeAccountId)->first();
            $balance = intval($tradeAccount->balance);
        }
        catch (\Exception $exception)
        {
            $error["message"] = "Unable to query Stock and Trade Account information";
            $error["code"] = 404;
            return response(json_encode($error), 404);
        }

        //If the Trade Account does not have enough to cover the cost, send back error
        if (($price * $request->quantity) > $balance)
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

        //Update the Trade Account Balance
        $newBalance = $balance - ($price * $request->quantity);
        DB::table('trade_accounts')->where('id', $request->TradeAccountId)->update(['balance' => $newBalance]);


        //If all went well, send back a nice message and a 200 status code
        $returnData = array();
        $returnData["message"] = "Transaction added";
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

//        $group = array();
        $stocks = 0;
        foreach ($transactions as $transaction)
        {
//            $key = $transaction->stock_id;
//            if (!key_exists($key, $group))
//                $group[$key] = array();

            if($transaction->stock_id == $stock_id)
            {
                $stocks += $transaction->bought;
                $stocks -= $transaction->sold;
            }
//                array_push($group, $transaction->);
        }

//        $transactions = Auth::user()->transactions;

//        var_dump($stocks);

        return response($stocks, 200);
    }
}
