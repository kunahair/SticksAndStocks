<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use PHPHtmlParser\Dom;

class AllOrdinariesController extends Controller
{

    public function getCurrentAllOrdinaries()
    {
        /**
         * All Ordinaries definition:
         * The All Ordinaries or All Ords is a measure of the overall performance of the Australian sharemarket at any given point in time. It is made up of the share prices for 500 of the largest companies.
         */

        $url = "https://au.finance.yahoo.com/quote/%5EAORD";

        $dom = new Dom;
        $dom->load($url);

        $allOrds = $this->getPrice($dom);
        $data = array();
        $data["allOrds"] = $allOrds;
//
//        //Return data array (that then is converted to JSON by Laravel) and a 200 OK status code
        return response($data, 200);
    }

    /**
     * Extract current information from DOM
     * @param $dom - DOM containing page of interest
     * @return array - price, movement since last update (direction, amount, percentage amount)
     */
    public function getPrice($dom)
    {
        //Get the contents of the bar
        $contents = $dom->find('#quote-header-info [data-reactid=240]');

        //Try to get the current price, if this fails if means that the stock did not load, so return an error
        try
        {
            //Extract price from span
            $price = $contents->find('[data-reactid=241]')->text();
        }catch (\Exception $errorException)
        {
            //If an error occures, return a fatal error back to the user
            exit(json_encode($this->fatalError()));
        }

        //Get stock movement information
        $stockMovement = $contents->find('[data-reactid=242]')->text();
        //Put into array that has the amount moved and the percentage
        $stockMovementArray = explode(" ", $stockMovement);

        //Remove brackets from percentage movement
        $stockMovementArray[1] = str_replace("(", "", $stockMovementArray[1]);
        $stockMovementArray[1] = str_replace(")", "", $stockMovementArray[1]);

        //Get the amount that the stock moved
        $stockMovementAmount = $stockMovementArray[0];
        //Get the percentage the stock moved
        $stockMovementPercentage = $stockMovementArray[1];

        //Array that holds extra data
        $extraData = array();

        //Additional information from the two tables in Yahoo! Finance

        //Previous close
        extractCurrentTableRowFromDom($dom, $extraData, 302, 303);

        //Open
        extractCurrentTableRowFromDom($dom, $extraData, 306, 307);

        //Volume
        extractCurrentTableRowFromDom($dom, $extraData, 310, 311);


        //Table 2
        //Day's Range, heading set manually as the ' in Day's is escaped
        extractCurrentTableRowFromDom($dom, $extraData, "Days range", 318);

        //52 week range
        extractCurrentTableRowFromDom($dom, $extraData, 321, 322);

        //Avg Volume
        extractCurrentTableRowFromDom($dom, $extraData, 325, 326);


        //Load return data into structured array
        $data = array();
        $data["price"] = $price;
//        $data["direction"] = $stockMovement;
        $data["amount"] = $stockMovementAmount;
        $data["percentage"] = $stockMovementPercentage;
        $data["extraData"] = $extraData;

        //Return data
        return $data;
    }


}