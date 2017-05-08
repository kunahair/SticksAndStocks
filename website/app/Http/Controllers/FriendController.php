<?php

namespace App\Http\Controllers;

use App\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{

    public function sendFriendRequest(Request $request)
    {
        //Get data from the request
        $toId = $request["to"];
        $fromId = $request["from"];

        //Check that the user is not trying to add themselves as a friend
        if ($toId == $fromId)
            return response("Cant send friend request to yourself, refreshing page", 400);

        try {
            //Check if there are any friend links in the friends table
            $friends = Friend::
            where([['to', $toId], ['from', $fromId]])
                ->orWhere([['from', $toId], ['to', $fromId]])
                ->first();
        }
        catch (\Exception $exception)
        {
            return response("Friend Request Already Pending, refreshing page", 400);
        }

        //If there is a row that has them as friends, even pending, send back an error
        if ($friends != null)
        {
            return response("Friend Request Already Pending, refreshing page", 400);
        }

        //Get the current time in UNIX (EPOCH) time
        $timestamp = time();

        //Load information ready to insert into Friends table
        $friendship = new Friend();
        $friendship->to = $toId;
        $friendship->from = $fromId;
        $friendship->timestamp = $timestamp;

        //Set the default value of pending to true (waiting for response)
        $friendship->pending = true;

        //Save to the database
        $friendship->save();

        //Send back all good response
        return response("Friend Request Sent", 200);

    }

    public function acceptFriendRequest(Request $request)
    {
        //Get data from the request
        $toId = $request["to"];
        $fromId = $request["from"];

        //Check to make sure the friendship exists
        $friends = Friend::where([['to', $toId], ['from', $fromId]])->first();

        //If there is no pending friend request, sent back a 400 error with a message
        if ($friends == null)
        {
            return response("No friend request found", 400);
        }

        //Otherwise, set pending to false, friend request accepted
        $friends->pending = false;

        //Save back to the database
        $friends->save();

        //Send 200 code back to the user, with a message
        return response("Friend Request Accepted", 200);
    }

    public function view(Request $request)
    {
        $friends = Auth::user()->getFriendList(Auth::user()->id);

        return view('friends')->with('friends', $friends);
    }
}
