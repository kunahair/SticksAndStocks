<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock as Stock;
use Illuminate\Support\Facades\Artisan;

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
        //If stock is in USA, also convert to AUD and update data passed to the view
		if ($data[0]->market != "ASX")
        {
            $currencyConverter = new \CurrencyConverter;
            $price = $currencyConverter->USDtoAUD($currentDataArray["curr_price"]["price"]);
            $data[0]->current_price = $price;
            $currentDataArray["curr_price"]["price"] = $price;
        }
        else
        {
            $data[0]->current_price = $currentDataArray["curr_price"]["price"];
        }

        //Save new current price to database
        $data[0]->save();

		//Update history
        $getHistory = new \GetAllCompanies;
        $historyData = $getHistory->getSingleStock($data[0]->stock_symbol);

        if ($historyData != null)
        {
            $history = array();
            $history["id"] = $data[0]->id;
            $history[1] = $historyData;
            $data[0]->addHistory($history);
        }

		//Load Blade view with database and current info
		return view('stock', ['stock' => $data[0], 'current' => $currentData])->with('currentDataArray', $currentDataArray);
	}
}
