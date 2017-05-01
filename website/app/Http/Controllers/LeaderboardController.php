<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;

class LeaderboardController extends Controller
{
    public function index() {
        // Recalculate Portfolio

        $users = User::all();

        foreach ($users as $user) {
            $user->updatePortfolio();
        }

        return view('leaderboard')->with('users', $users->sortByDesc('portfolio'));
    }
}
