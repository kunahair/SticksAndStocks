<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    public function view(Request $request, $id = null, $error = null)
    {
        $data = array();

        //If there is no user id supplied, return user to dashboard
        if ($id == null)
            return view('/dashboard');

        //Get the information of the Friend to make displaying data easier
        $user = DB::table('users')->select('name', 'id')->where('id', $id)->first();

        //TODO: add check for all variables to make sure they are not null

        if (Auth::user()->checkIfFriends($id) && $id != Auth::user()->id)
            return view('/dashboard');

        try
        {
            //Get all the messages the user has sent and received by date
            $messages = DB::table('messages')
                ->where([['to', $id], ['from', Auth::user()->id]])
                ->orWhere([['from', $id], ['to', Auth::user()->id]])
                ->orderBy('timestamp', 'desc')
                ->get();
        }
        catch (\Exception $exception)
        {
            $data["messages"] = array();
            $data["error"] = "Unable to get Messages from User";

            //Return all the data needed for view
            return view('messages', ['user' => $user], ['id' => $id])->with('data', $data);
        }

        try
        {
            //Change all the un-read messages JUST FOR THIS USER (i.e. messages they have received) to read
            DB::table('messages')
                ->where([['from', $id], ['to', Auth::user()->id]])
                ->update(['read' => true]);
        }
        catch (\Exception $exception)
        {
            $data["messages"] = $messages;
            $data["error"] = "Unable to update unread Messages";
            return view('messages', ['user' => $user], ['id' => $id])->with('data', $data);
        }

        //Bundle return data into array
        $data["messages"] = $messages;
        $data["error"] = $error;

        //Return all the data needed for view
        return view('messages', ['user' => $user], ['id' => $id])->with('data', $data);
    }

    public function sendMessage(Request $request, $id = null)
    {
        //Check that the Friend ID has been supplied
        if ($id == null)
            return view('/dashboard');

        if (Auth::user()->checkIfFriends($id) && $id != Auth::user()->id)
            return $this->view($request, $id, "You are no Friends, so you cant send messages");

        //If the message and ID are set, add it to database that "sends" the message
        if ($request->message != null && $request->id != null)
        {
            //Get the contents of the Message
            $message = $request->message;

            //Create a new Message Model
            $newMessage = new Message;

            //Set the to and from IDs
            $newMessage->to = $id;
            $newMessage->from = $request->id;

            //Set the message contents
            $newMessage->message = $message;

            //Set the time on the message
            $newMessage->timestamp = time();

            //Save to the database
            $newMessage->save();

        }

        //Send the user back to the messages page for this friend, will show new message up the top
        return $this->view($request, $id);

    }
}
