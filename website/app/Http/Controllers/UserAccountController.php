<?php

namespace App\Http\Controllers;

use App\TradeAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAccountController extends Controller
{

    public function edit(Request $request)
    {
        $currentUser = Auth::User();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($currentUser->id)]
        ]);
        
        if ($validator->fails()) {
            return redirect('/dashboard')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $currentUser->name = $request->name;
        $currentUser->email = $request->email;

        $currentUser->save();

    }

}
