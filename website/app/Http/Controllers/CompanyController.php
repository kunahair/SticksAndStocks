<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Faker\Provider\cs_CZ\DateTime;
use Psy\Util\Json;
use PHPHtmlParser\Dom;

/**
 * Class CompanyController
 * Libraries used:
 *      - PHPHtmlParser: https://github.com/paquettg/php-html-parser
 *          Used to perform CSS like selections from a loaded webpage
 *
 * APIs Used
 *      - Yahoo! Finance (australian listing): https://au.finance.yahoo.com
 *      Web page scraped for information
 * @package App\Http\Controllers
 */
class CompanyController extends Controller
{

    /**
     * Get the current listing information for selected company
     * Returns JSON of scraped Yahoo! Finance for company
     * @param null $code - Code that the company is listed under
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response - JSON representation of selected company, or error JSON
     */
    public function currentDetails($code = null)
    {
        //Base URL for code readability
        $base_link = 'https://au.finance.yahoo.com';

        //Returned data array
        $data = array();

        //If there is no code selected, set to NAB as default
        //todo: send user fatal error JSON
        if ($code == null)
            $code = "NAB";

        //Create new DOM object to store page to be scraped
        $dom = new Dom;
        //Load the company Yahoo! Finance listing
        //https://au.finance.yahoo.com/quote/NAB.AX
        $dom->load($base_link . '/quote/' . $code . '.AX');

        //Get the current information on selected company
        $data["curr_price"] = $this->getPrice($dom);

        //Return data array (that then is converted to JSON by Laravel) and a 200 OK status code
        return response($data, 200);
    }

    /**
     * Extract current information from DOM
     * @param $dom - DOM containing page of interest
     * @return array - price, movement since last update (direction, amount, percentage amount)
     */
    function getPrice($dom)
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
        $this->getDataFromRow(321, 322, $extraData, $dom);

        //Open
        $this->getDataFromRow(325, 326, $extraData, $dom);

        //Bid
        $this->getDataFromRow(329, 330, $extraData, $dom);

        //Ask
        $this->getDataFromRow(333, 334, $extraData, $dom);

        //Days Range
        $this->getDataFromRow(321, 322, $extraData, $dom, 'Days range');

        //52 Week Range
        $this->getDataFromRow(341, 342, $extraData, $dom);

        //Volume
        $this->getDataFromRow(345, 346, $extraData, $dom);

        //Average Volume
        $this->getDataFromRow(349, 350, $extraData, $dom);

        //Table 2
        //Market Cap
        $this->getDataFromRow(356, 357, $extraData, $dom);

        //Beta
        $this->getDataFromRow(360, 361, $extraData, $dom);

        //PE ratio (TTM)
        $this->getDataFromRow(364, 365, $extraData, $dom);

        //Dividend and Yield
        $value = $dom->find('[data-reactid=377]')->text();

        //Separate values into array
        $dayArray = explode(" ", $value);

        //Dividend
        $heading = "Dividend";
        $value = $dayArray[0];
        $extraData[$heading] = $value;

        //Remove brackets from Yield
        $yield = $dayArray[1];
        $yield = str_replace("(", "", $yield);
        $yield = str_replace(")", "", $yield);

        //Yield
        $heading = "Yield";
        $value = $yield;
        $extraData[$heading] = $value;

        //1year target estimation
        $this->getDataFromRow(384, 385, $extraData, $dom);


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

    /**
     * If there is a 404 error, don't freak the user out, just send back a 404 error with a message wrapped as a JSON string
     * @param string $message - show default message if no message is passed
     * @param int $status - Default status code 404 (not found) unless otherwise specifed
     */
    function fatalError($message = "Could not find ASX item", $status = 404)
    {
        $error = array();
        http_response_code($status);
        $error["message"] = $message;
        $error["code"] = 404;
        exit(json_encode($error));
    }

    /**
     * Get Data from selected Row in Yahoo! Finance company page
     *
     * @param $headingReactID - React ID of heading
     * @param $valueReactID - React ID of the value
     * @param $extraData - Array to load the data into
     * @param $dom - The DOM to extract the data from
     * @param $heading - Optional heading if heading is not available in DOM row
     */
    private function getDataFromRow($headingReactID, $valueReactID, &$extraData, &$dom, $heading = null)
    {
        //If the heading has not been set, then get the heading from he DOM
        if ($heading == null)
            $heading = $dom->find('[data-reactid=' . $headingReactID . ']')->text();
        //Get the value from the DOM by react ID
        $value = $dom->find('[data-reactid=' . $valueReactID . ']')->text();

        //Set the value under the heading in the data array
        $extraData[$heading] = $value;
    }
}



































