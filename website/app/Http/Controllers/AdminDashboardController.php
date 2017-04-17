<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Get a Collection of all the Users in the users table
     * @return mixed - Collection of All Users
     */
    private function getAllUsers()
    {
        $users = DB::table('users')->get();
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
}
