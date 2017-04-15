<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

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
}
