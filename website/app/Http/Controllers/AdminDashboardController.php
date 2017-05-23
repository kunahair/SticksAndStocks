<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Mail\AdminAlert;
use App\Message;
use App\Money;
use App\Providers\AppServiceProvider;
use App\TradeAccount;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User as User;
use Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\AccountDeleted;

class AdminDashboardController extends Controller
{
    /**
     * Get integer count of the number of users in database
     * @return mixed - Number of user rows in table
     */
    private function getUsersCount()
    {
        $users = DB::table('users')->count();
        return $users;
    }

    /**
     * Get a Collection of all the Users in the users table, pagination for each group of 5
     * @return mixed - Collection of All Users
     */
    private function getAllUsers()
    {
        $users = DB::table('users')->paginate(5);
        return $users;
    }

    /**
     * Show the user Dashboard.
     * Gets stats for the admin to be able to view and change on the dashboard, or through separate pages/AJAX
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        //Data to return to caller
        $data = array();

        $data['usersCount'] = $this->getUsersCount();
        $data["users"] = $this->getAllUsers();

        return view('admin-dashboard')->with('data', $data);
    }

    public function deleteUser(Request $request) {

        //Check that the admin is signed in
//        if (!Auth::check()) {
//            $user = Auth::user();
//            if ($user->admin == 0) {
//                $error["message"] = "Admin not logged in";
//                $error["code"] = 403;
//                return response(json_encode($error), 403);
//            }
//        }

        //Make sure this is a POST request
        if (!$request->isMethod('POST'))
        {
            $error["message"] = "Invalid call to delete user";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        Log::info("woat " . $request->userid);
        //Get the User that is to be deleted from the database
        $user = User::where('id',$request->userid)->first();
        //If the User does not exist, send back a 404 with error that the User was not found
        if ($user == null) {
            $error["message"] = "User doesn't exist";
            $error["code"] = 404;
            return response(json_encode($error), 404);
        }

        try {
            //Delete all Trade Accounts that User has
            $tradeAccounts = TradeAccount::where('user_id', $user->id)->get();
            foreach ($tradeAccounts as $tradeAccount)
            {
                //Delete all Transactions that Trade Account has
                $transactions = Transaction::where('trade_account_id', $tradeAccount->id)->get();
                foreach ($transactions as $transaction)
                {
                    $transaction->delete();
                }
                $tradeAccount->delete();
            }

            //Delete all messages User has sent or received
            $messages = Message::where('to', $user->id)->orWhere('from', $user->id)->get();
            foreach ($messages as $message)
            {
                //If the messages have Money Transfers attached to them, delete them
                $moneyTransfers = Money::where('message_id', $message->id)->get();
                if ($moneyTransfers != null)
                {
                    foreach ($moneyTransfers as $moneyTransfer)
                    {
                        $moneyTransfer->delete();
                    }
                }

                //Delete the message
                $message->delete();
            }

            //Remove all Friendships the deleted User has
            $friendships = Friend::where('to', $user->id)->orWhere('from', $user->id)->get();
            if ($friendships != null)
            {
                foreach ($friendships as $friendship)
                {
                    $friendship->delete();
                }
            }

            //Then delete their account
            $user->delete();
        }
        catch (\Exception $exception)
        {
            //If there is an error processing the User deletion, send back an error
            $error["message"] = "Unable to delete user";
            $error["code"] = 403;
            return response($exception, 403);
        }

        // If all successful, mail User to tell them of their account termination
        Mail::to($user->email)->send(new AccountDeleted());

        $returnData = array();
        $returnData["message"] = "The requested users' account has been deleted.";
        $returnData["code"] = 200;
        return json_encode($returnData);
    }

    public function modifyRole(Request $request) {
        //Check that the admin is signed in
    //        if (!Auth::check())
    //        {
    //            $error["message"] = "Admin not logged in";
    //            $error["code"] = 403;
    //            return response(json_encode($error), 403);
    //        }

        //Make sure this is a POST request
        if (!$request->isMethod('POST'))
        {
            $error["message"] = "Invalid call to change users role";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        $user = User::where('id',$request->userid)->first();

        if ($user == null) {
            $error["message"] = "User doesn't exist";
            $error["code"] = 404;
            return response(json_encode($error), 404);
        }

        if ($request->role == 'admin') {
            $user->admin = 1;
        } elseif ($request->role == 'user') {
            $user->admin = 0;
        } else {
            $error["message"] = "Role doesn't exist";
            $error["code"] = 404;
            return response(json_encode($error), 404);
        }

        $user->save();

        $returnData = array();
        $returnData["message"] = "The requested users' account has been given another role.";
        $returnData["code"] = 200;
        return json_encode($returnData);
    }

    public function emailUsers(Request $request) {
        //Check that the admin is signed in
//        if (!Auth::check())
//        {
//            $error["message"] = "Admin not logged in";
//            $error["code"] = 403;
//            return response(json_encode($error), 403);
//        }

        //Check that there is a message there.

        //Make sure this is a POST request
        if (!$request->isMethod('POST'))
        {
            $error["message"] = "Invalid call to send mail";
            $error["code"] = 403;
            return response(json_encode($error), 403);
        }

        $users = User::all();

        $content = [
            'message' => $request->message
        ];

        foreach ($users as $user) {
            // Mail the admins message to that user
            Mail::to($user->email)->send(new AdminAlert($content));
        }

        $returnData = array();
        $returnData["message"] = "The message has been sent.";
        $returnData["code"] = 200;
        return json_encode($returnData);
    }
}
