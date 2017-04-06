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
        $validator = Validator::make($request->all(), [
            'name' => ['required','unique:TradeAccounts']
        ])->validator();
        
        $name = $request->name;
        Auth::User()->tradingAccounts()->create(['name' => $name, 'balance' => 1000000]);

        return response("Trading Account Created", 200);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:trade_accounts'],
            'name' => ['required', 'max:255', Rule::unique('trade_accounts')->ignore($request->id)]
        ])->validator();

        $currentTA = TradeAccount::find($request->id);
        $currentTA->username = $request->name;
        $currentTA->save();

        return response("Trading Account Edited", 200);
    }

}
