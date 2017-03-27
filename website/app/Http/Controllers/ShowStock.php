<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock as Stock;

class ShowStock extends Controller
{
	public function __invoke($code) {
		$data = Stock::where('stock_symbol', $code)->get();
		// $data = Stock::all();
		return view('stock', ['stock' => $data[0]]);
	}
}
