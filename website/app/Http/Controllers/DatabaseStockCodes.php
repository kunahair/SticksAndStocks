<?php

/**
 * Created by: Paul Davidson.
 * Authors: Paul Davidson and Josh Gerlach
 */

namespace App\Http\Controllers;

use App\Stock;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

header('Access-Control-Allow-Origin: *');

class DatabaseStockCodes extends Controller
{
    /**
     * Get all stock listings. Gets Stocks Symbol, Stock Name and Stock Market
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getAllStockCodesAndCompanyNames()
    {
        //Call the stocks table and get all stock names and symbol and market
        $stockListing = DB::table('stocks')->select('stock_name', 'stock_symbol', 'market')->get();
        //Return JSON array of data with status code 200
        return response($stockListing, 200);
    }

}