<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock as Stock;

class ShowStock extends Controller
{
	public function __invoke($code) {
    //Get data from database for company
		$data = Stock::where('stock_symbol', $code)->get();

		if ($data == null || count($data) == 0) {
		    return redirect('404');
        }

		//Get the current information for company
		$currentDataClass = new \CurrentCompanyStockInformation;
		$currentDataArray = $currentDataClass->currentDetails($code, $data[0]->market);
		if ($currentDataArray["curr_price"] == null)
		    return redirect('404');
		$currentData = \GuzzleHttp\json_encode($currentDataArray);

        //Update the current price in the database
		if ($data[0]->market != "ASX")
        {
            $currencyConverter = new \CurrencyConverter;
            $data[0]->current_price = $currencyConverter->USDtoAUD($currentDataArray["curr_price"]["price"]);
        }
        else
        {
            $data[0]->current_price = $currentDataArray["curr_price"]["price"];
        }

        //Save new current price to database
        $data[0]->save();

		//Update history
        $getHistory = new \GetAllCompanies;
        $getHistory->getSingleStock($code);


		//Load Blade view with database and current info
		return view('stock', ['stock' => $data[0], 'current' => $currentData])->with('currentDataArray', $currentDataArray);
	}
}
