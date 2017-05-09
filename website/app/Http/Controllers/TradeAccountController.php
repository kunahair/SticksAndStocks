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
        //todo: validator does not work here, needs to be fixed, will probs need a try catch around it
//        $validator = Validator::make($request->all(), [
//            'name' => ['required','unique:TradeAccounts']
//        ])->validator();

        //Get the name for the new Trade Account
        $name = $request->name;

        //Through the Authenticated user, create a new Trade Account and associate with current User
        Auth::User()->tradingAccounts()->create(['name' => $name, 'balance' => 1000000]);

        //Create a response array that returns the account was created
        $response = array();
        $response["message"] = "Trade Account Created";
        $response["code"] = 200;

        //Return the response in JSON with a 200 status (OK)
        return response(json_encode($request), 200);
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

    /**
     * Show the Trade Account Page given an accountId in the request
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function view(Request $request)
    {
        //Get the Account ID from the Request and convert to an INT
        $accountIdString = $request->accountId;
        $accountIdInt = intval($accountIdString);

        //Query the TradeAccount table and get the correct Trade Account row by id
        $tradeAccount = TradeAccount::where('id', $accountIdInt)->get();

        //If the trade account retrieved is not owned by the user who called for it, then return them to the Dashboard
        if ($tradeAccount[0]->user_id != Auth::user()->id)
            return redirect('/dashboard');

        //Return, loading the view into memory and passing the tradeAccount as an array (for Blade)
        return view('trade-account')->with('tradeAccount', $tradeAccount[0]);
    }

}
