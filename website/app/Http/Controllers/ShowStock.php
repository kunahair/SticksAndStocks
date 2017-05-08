<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock as Stock;

class ShowStock extends Controller
{
	public function __invoke($code) {
    //Get data from database for company
		$data = Stock::where('stock_symbol', $code)->get();

		if ($data == null) {
		    return view('404');
    }

		//Get the current information for company
		$currentDataClass = new \CurrentCompanyStockInformation;
		$currentDataArray = $currentDataClass->currentDetails($code, $data[0]->market);
		$currentData = \GuzzleHttp\json_encode($currentDataArray);

//		$currentDataCollection = collect($currentDataArray);

		//Load Blade view with database and current info
		return view('stock', ['stock' => $data[0], 'current' => $currentData])->with('currentDataArray', $currentDataArray);
	}
}
