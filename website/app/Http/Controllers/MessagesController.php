<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

namespace App\Http\Controllers;

use App\Friend;
use App\Message;
use App\Money;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    /**
     * View messages from selected friend
     *
     * @param Request $request - Not used, put as argument for consistency over the Laravel Framework
     * @param null $id - ID of the User who is a friend to view messages
     * @param null $error - If there are any errors from previous calculations
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector - View to ultimately render
     */
    public function view(Request $request, $id = null, $error = null)
    {
        $data = array();

        //If there is no user id supplied, return user to dashboard
        if ($id == null)
            return redirect('dashboard');

        //Get the information of the Friend to make displaying data easier
        $user = DB::table('users')->select('name', 'id')->where('id', $id)->first();

        //TODO: add check for all variables to make sure they are not null

        if ($user == null)
            return redirect('dashboard');

        //If the User and the selected User are not friends, or the User id is the same as Friend id, go to dashboard
        if (!Auth::user()->checkIfFriends($id) || $id == Auth::user()->id)
            return redirect('dashboard');

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
            $data["error"] = $exception;//"Unable to get Messages from User";

            //Return all the data needed for view
            return view('messages', ['user' => $user], ['id' => $id])->with('data', $data);
        }

        try
        {
            //Change all the un-read messages JUST FOR THIS USER (i.e. messages they have received) to read
            DB::table('messages')
                ->where([['from', $id], ['to', Auth::user()->id]])
                ->update(['read' => true]);

            Friend::
            where([['to', $id], ['from', Auth::user()->id], ['pending', false]])
                ->update(['accept_view' => true]);
        }
        catch (\Exception $exception)
        {
            $data["messages"] = $messages;
            $data["error"] = "Unable to update unread Messages";
            return view('messages', ['user' => $user], ['id' => $id])->with('data', $data);
        }

        $data["moneyTransfers"] = null;

        try
        {
            //Get all money Transfers between User and Friend
            $moneyTransfers = Money::where([['from', $id], ['to', Auth::user()->id]])
                ->orWhere(([['to', $id], ['from', Auth::user()->id]]))
                ->get();

            $moneyTransfersArray = array();

            //Get all the Money Transfers and add to array to return
            foreach ($moneyTransfers as $moneyTransfer)
            {
                $moneyTransfersArray[$moneyTransfer->message_id] = $moneyTransfer;
            }

            //Add Money Transfers to the data to send back
            $data["moneyTransfers"] = $moneyTransfersArray;
        }
        catch (\Exception $exception)
        {
            //TODO: If there is an error, pass it back to the User, will also have to remove the last message from DB
        }

        //Bundle return data into array
        $data["messages"] = $messages;
        $data["error"] = $error;

        //Return all the data needed for view
        return view('messages', ['user' => $user], ['id' => $id])->with('data', $data);
    }

    /**
     * Send a message to selected user, API call
     *
     * @param Request $request - Contains the message to send as POST data
     * @param null $id - User ID of the User that the message is to be sent to
     * @return MessagesController|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function sendMessage(Request $request, $id = null)
    {
        //Check that the Friend ID has been supplied
        if ($id == null)
            return view('/dashboard');

        //Make sure that the person sending the message and the reciever are Friends
        if (!Auth::user()->checkIfFriends($id) && $id != Auth::user()->id)
        {
            var_dump(Auth::user()->checkIfFriends($id));
            return $this->view($request, $id, "You are no Friends, so you cant send messages");
        }


        //Check that the message is not empty
        if ($request->message == null)
        {
            return $this->view($request, $id, "Message cannot be empty");
        }

        //If the message and ID are set, add it to database that "sends" the message
        if ($request->message != null && $request->id != null)
        {
            try
            {
                $this->saveMessage($request->message, $id);
            }
            catch (\Exception $exception)
            {
                return $this->view($request, $id, "Unable to send message, please try again");
            }
        }

        //If there is money, add it
        if (is_numeric($request->money))
        {
            if (floatval($request->money) != 0)
            {
                $moneySent = floatval($request->money);
                if ($moneySent <= Auth::user()->balance && $moneySent > 0)
                {
                    //Get the last message from the User, as that will be the message attached to the sending money
                    $latestMessage = Message::where('from', Auth::user()->id)
                        ->orderBy('timestamp', 'desc')
                        ->first();

                    //Create new Money Object, to load and save to the database
                    $money = new Money;

                    $money->to = $id;
                    $money->from = $request->id;

                    $money->to_message = $request->message;

                    $money->timestamp = time();

                    $money->message_id = $latestMessage->id;

                    $money->amount = $request->money;

                    $money->save();

                    //Update the Balance of the User who sent the money
                    $user = Auth::user();
                    $user->balance -= $moneySent;

                    $user->save();
                }

                return redirect()->action('MessagesController@view', ['id' => $id]);
            }
        }


        //Send the user back to the messages page for this friend, will show new message up the top
        return redirect()->action('MessagesController@view', ['id' => $id]);

    }

    /**
     * Save a new sent message to the database
     * @param $message - String message to be saved/sent
     * @param $id - User ID of the recipient
     * @return bool - true if the message saved to the database, false otherwise
     */
    private function saveMessage($message, $id)
    {

        //Create a new Message Model
        $newMessage = new Message;

        //Set the to and from IDs
        $newMessage->to = $id;
        $newMessage->from = Auth::user()->id;

        //Set the message contents
        $newMessage->message = $message;

        //Set the time on the message
        $newMessage->timestamp = time();

        //Save to the database
        return $newMessage->save();

    }

    /**
     * Load the first User in the friends list and show their messages
     * @param Request $request - Not Used, part of the Laravel Framework
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function first(Request $request)
    {
        //Get all the users friends
        $friends = Auth::user()->getFriendList(Auth::user()->id);

        //Declare null friend first
        $friend = null;

        //If there is at least one friend, then get the first one
        if (count($friends) > 0)
            $friend = $friends[0]->first();

        //If the friend is not null, route user to their messages
        if ($friend != null)
        {
            if ($friend["to"] != Auth::user()->getAuthIdentifier())
                return redirect()->action('MessagesController@view', ['id' => $friend["to"]]);
            return redirect()->action('MessagesController@view', ['id' => $friend["from"]]);
        }

        //Default, if they have no friends, route back to dashboard
        return view('/dashboard');

    }

    /**
     * Accept money sent from a friend. Allows partial amount to be accepted
     * @param Request $request - Contains the amount to be accepted
     * @param null $id - User ID of the User who sent it
     * @return MessagesController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function acceptMoney(Request $request, $id = null)
    {
        //Check if the money accepted is numeric
        if (!is_numeric($request->moneyAccept))
            return $this->view($request, $request->friend_id, "Amount must be numberic");

        //Get amount accepted as a float
        $acceptAmount = floatval($request->moneyAccept);

        //Get the money transfer row from database
        $money = Money::where('id', $request->money_id)->first();

        //If the accepted amount is less than 0 or greater than the amount sent, return an error
        if ($acceptAmount < 0 || $acceptAmount > $money->amount)
            return $this->view($request, $request->friend_id, "Amount must not be negative and up to amount offered");

        $user = Auth::user();
        $friend = User::where('id', $id)->first();

        //Update User balance
        $user->balance += $acceptAmount;
        $user->save();

        //Update Friends Balance
        $friend->balance += $money->amount - $acceptAmount;
        $friend->save();

        //Update the money message to be saved
        $money->to_read = true;
        $money->taken = $acceptAmount;
        $money->save();

        //Redirect back to Message View, shows that the the current user accepted an amount
        return redirect()->action('MessagesController@view', ['id' => $id]);

    }
}
