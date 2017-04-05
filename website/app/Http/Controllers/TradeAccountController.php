<?php

namespace App\Http\Controllers;

use App\TradeAccount;
use Illuminate\Http\Request;

class TradeAccountController extends Controller
{

    public function create(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:TradeAccounts,username'
        ]);
        
        $username = $request->username;
        Auth::User()->tradingAccounts()->create(['username' => $username, 'balance' => 1000000]);
    }

}
