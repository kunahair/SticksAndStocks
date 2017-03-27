<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;

class ShowStock extends Controller
{
	public function __invoke($code) {
		return view('stock', ['stock' => Stock::where('stock_symbol', $code)->firstOrFail()]);
	}
}
