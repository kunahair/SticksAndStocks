<?php

namespace App\Http\Controllers;

use App\TradeAccount;
use Dotenv\Exception\ValidationException;
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
        ])->validate();
        
        $currentUser->name = $request->name;
        $currentUser->email = $request->email;

        $currentUser->save();

        return response('User Account Edited.', 200);
    }

    /**
     * API edit to get update a users information
     * NOTE: It says API but it is routed through the web routes for ease of use
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function apiEdit(Request $request)
    {
        //Get the current user
        $currentUser = Auth::User();

        //Validate the request that is to be completed
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($currentUser->id)]
        ]);

        //If it fails send a 400 error to the caller
        if ($validator->fails()) {
            $response = array();
            $response["code"] = 400;
            return response(json_encode($response), 400);
        }

        //Set the fields that have been edited
        $currentUser->name = $request->name;
        $currentUser->email = $request->email;

        //Commit/make the changes to the database
        $currentUser->save();

        //Create a response array that only has 200 status code to be read if needed on caller side
        $response = array();
        $response["code"] = 200;

        //Return to the caller with a 200 response
        return response(json_encode($response), 200);
    }

}
