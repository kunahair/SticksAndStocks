<?php

/**
 * Created by: Paul Davison.
 * Authors: Paul Davidson
 */

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\User as User;

class LeaderboardController extends Controller
{

    /**
     * Update all users portfolios then render the
     * @return View - Leaderboard view with Users in order of leaderboard position based on portfolio value
     */
    public function index() {

        //Get all Users
        $users = User::all();

        //Update the portfolio of all Users
        foreach ($users as $user) {
            $user->updatePortfolio();
        }

        //Render Leaderboard view with users sorted by portfolio value
        return view('leaderboard')->with('users', $users->sortByDesc('portfolio'));
    }
}
