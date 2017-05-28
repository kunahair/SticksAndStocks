<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin', 'avatar', 'balance', 'portfolio'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tradingAccounts() {
        return $this->hasMany('App\TradeAccount');
    }

    //Check if User is an Admin
    public function isAdmin()
    {
        return $this->admin;
    }

    public function isFriend($authid, $id)
    {
        /**
         * 0 - not friends
         * 1 - are friends
         * 2 - same user
         * 3 - no user found
         * 4 - friend request pending
         * 5 - waiting for friend to accept
         *
         * 10 - Error, reached the end of the function with no return
         */

        //Get User who's profile is being used id
        $user = DB::table('users')->find($id);

        //If the User is null, User does not exist, so return 3
        if ($user == null)
            return  3;

        //If the Profile ID and the User who requested the isFriend are the same,
        //User is viewing own profile, return 2
        if ($authid == $id)
            return 2;

        //Check if there are any friend links in the friends table
        $friends = DB::table('friends')
            ->where([['to', $authid], ['from', $id]])
            ->orWhere([['from', $authid], ['to', $id]])
            ->first();

        //If there is no friends row, they are not friends, return 0
        if ($friends == null)
            return 0;


        //Check the pending attribute
        if ($friends->pending)
        {
            //If the request was sent to the viewing User, means they have a friend request waiting, return 5
            if ($friends->to == $authid)
                return 5;
            //Otherwise, they are the waiting on the other user to accept, so return 4
            else
                return 4;
        }
        //A check, at this point both Users exist, have a Friends row, but pending is false,
        //So they must be friends, return 1
        else if (!$friends->pending)
            return 1;


        return 10;
    }

    /**
     * Get Pending Friend Requests and Unseen Friend Request Accepts
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getFriendRequests()
    {
        //Get user ID
        $id = $this->getAuthIdentifier();

        try {
            //Get friend requests the user has pending, add the name of the user who sent the request
            $friendRequests = Friend::
                where([['to', $id], ['pending', true]])
                ->orwhere(
                    [
                        ['from', $id],
                        ['pending', false],
                        ['accept_view', false]
                    ]
                )
                ->join('users', 'users.id', 'friends.from')
                ->join('users as usrs', 'usrs.id', 'friends.to')
                ->select('friends.*', 'users.name as name_from', 'usrs.name as name_to')
                ->get();
        }
        catch (\Exception $exception)
        {
            return response("Error getting Friend Requests", 400);
        }

        return $friendRequests;
    }

    public function getFriendList($id)
    {
        try {
            //Get friend requests the user has received and not pending
            $friendsTo = Friend::
            where([['from', $id], ['pending', false]])
                ->join('users', 'to', '=', 'users.id')
                ->select('users.id', 'users.name')
                ->get();

            //Get friend requests sent and not pending
            $friendsFrom = Friend::
            where([['to', $id], ['pending', false]])
                ->join('users', 'from', '=', 'users.id')
                ->select('users.id', 'users.name')
                ->get();
        }
        catch (\Exception $exception)
        {
            return response($exception, 400);
        }

        //Combine Friends into single array
        $friends = array();

        //Add Friends that User sent requests to and accepted
        if ($friendsTo != null)
        {
            foreach ($friendsTo as $friend)
            {
                array_push($friends, $friend);
            }
        }

        //Add Friends that User received requests from and accepted
        if ($friendsFrom != null)
        {
            foreach ($friendsFrom as $friend)
            {
                array_push($friends, $friend);
            }
        }

        //Return complete friends list
        return $friends;
    }

    public function checkIfFriends($id)
    {
        //Get the current User ID
        $authid = $this->getAuthIdentifier();

        //Check if there are any friend links in the friends table
        $friends = DB::table('friends')
            ->where([['to', $authid], ['from', $id]])
            ->orWhere([['from', $authid], ['to', $id]])
            ->first();

        if ($friends != null)
            return true;

        return false;
    }

    /**
     * Get all current users unread messages
     * @return mixed
     */
    public function getUnreadMessages()
    {
        //Get user ID
        $id = $this->getAuthIdentifier();

        //Get unread messages from database, add the name of the user who sent the message
        $unreadMessages = Message::
            where([['to', '=', $id], ['read', '=', false]])
            ->join('users', 'users.id', 'messages.from')
            ->select('messages.*', 'users.name')
            ->get();

        //Return list of messages
        return $unreadMessages;
    }


    /**
     * Wrapper function to get all the notifications the user has
     * Returns unread messages and unaccepted friend requests
     * @return mixed
     */
    public function getNotifications()
    {
        //Get list of all unread messages for user
        $unreadMessages = $this->getUnreadMessages();
        //Get list of all pending friend requests
        $pendingFriendRequests = $this->getFriendRequests();

        //Merge the friend requests and the unread messages into one array
        $data = $unreadMessages->merge($pendingFriendRequests);

        //Return the merged data
        return $data;
    }

    public function updatePortfolio() {
        $buySell = Array();

        // Portfolio Value will be the ranking
        $this->portfolio = $this->balance;
        $this->save();

        foreach ($this->tradingAccounts()->get() as $tradeAccount) {
            foreach ($tradeAccount->transactions()->get() as $transaction) {
                $buySell[$transaction->stock()->first()->id] = 0;
            }
            foreach ($tradeAccount->transactions()->get() as $transaction) {
                $buySell[$transaction->stock()->first()->id] += $transaction->bought;
                $buySell[$transaction->stock()->first()->id] -= $transaction->sold;
            }
            foreach ($buySell as $key => $value) {
                // Check if the user has the stock
                if ($value > 0) {

                    // Current Value
                    $currentPrice = Stock::where('id', $key)->first()->current_price;

                    // Append to the Portfolio Value
                    $this->portfolio += $currentPrice * $value;
                    $this->save();
                }
            }
        }
    }
}
