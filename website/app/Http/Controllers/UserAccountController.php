<?php

namespace App\Http\Controllers;

use App\Friend;
use App\TradeAccount;
use App\User;
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

    /**
     * Search Users by incoming query, searches by name and puts in ascending order, gets first 5 results
     * @param Request $request
     * @param null $query
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function searchUserWithQuery(Request $request, $query = null)
    {
        //If there is no query value set, return null
        if ($query == null)
            return response(null, 200);

        //Get the results where the query is is contained in either a name or email address
        //Select only the id and name to send back, limit to first 5 results
        //Sort by Name
        $results = User::where('name', 'like', '%' . $query . '%')
//       ->orWhere('email', 'like', '%' . $query . '%')
            ->select('id', 'name')
            ->take(5)
            ->orderBy('name', 'asc')
            ->get();

        //Return the results as JSON
        return response($results, 200);
    }

    public function showUser(Request $request, $id = null)
    {

        //Get the user that is being requested
        $user = User::find($id);

        //If the user does not exist, go to the dashboard
        if ($user == null)
            return redirect('/dashboard');

        //Get users total growth
        $growth = \Growth::getTotalGrowth($id);

        //Update accepted friend request view, that is, any now friend has accepted the friend request but
        //current user has not seen, change to seen
        try
        {
            Friend::
                where([['to', $id], ['from', Auth::user()->id], ['pending', false]])
                ->update(['accept_view' => true]);
        }
        catch (\Exception $exception)
        {
            //If there is an error updating, redirect to the dashboard
            return redirect('/dashboard');
        }

        return view('profile', ['growth' => $growth, 'user' => $user]);
    }

}
