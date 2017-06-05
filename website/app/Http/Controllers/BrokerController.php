<?php

/**
 * Created by: Paul Davidson.
 * Authors: Paul Davidson
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrokerController extends Controller
{

    public function buy($request) {
        // Validate the request data
        $this->validate($request, [
            'stock_symbol' => 'required|max:6|exists:stocks',
            'amount' => 'required'
        ]);
        // Pass it to the Broker

    }

    public function sell($request) {
        // Validate the request data
        $this->validate($request, [
            'stock_symbol' => 'required|max:6|exists:stocks',
            'amount' => 'required'
        ]);
        // Pass it to the Broker

    }

}
