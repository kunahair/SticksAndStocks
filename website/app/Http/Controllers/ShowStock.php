<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock as Stock;

class ShowStock extends Controller
{
	public function __invoke($code) {
		$data = Stock::where('stock_symbol', $code)->get();
		// $data = Stock::all();
		// print_r(array_keys(json_decode($data[0]->history, true)));
		return view('stock', ['stock' => $data[0]]);
	}
}
