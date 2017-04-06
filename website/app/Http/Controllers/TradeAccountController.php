<?php

namespace App\Http\Controllers;

use App\TradeAccount as TradeAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:trade_accounts'],
            'username' => ['required', 'max:255', Rule::unique('trade_accounts')->ignore($request->id)]
        ]);

        if ($validator->fails()) {
            return redirect('/dashboard')
                ->withErrors($validator)
                ->withInput();
        }

        $currentTA = TradeAccount::find($request->id);
        $currentTA->username = $request->username;
        $currentTA->save();
    }

}
