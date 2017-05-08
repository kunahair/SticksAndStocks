<?php

use PHPHtmlParser\Dom;

class CurrentCompanyStockInformation {
    /**
     * Get the current listing information for selected company
     * Returns JSON of scraped Yahoo! Finance for company
     * @param null $code - Code that the company is listed under
     * @return array
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
        // if ($market == "ASX") {
        $dom->load($base_link . '/quote/' . $code . '.AX');
        // } else {
        //   $dom->load($base_link . '/quote/' . $code);
        // }

        //Get the current information on selected company
        $data["curr_price"] = $this->getPrice($dom);

        //Return data array (that then is converted to JSON by Laravel) and a 200 OK status code
        return $data;
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
            $price = $dom->find('span[data-reactid=36]')->text();

        }catch (\Exception $errorException)
        {
            //If an error occures, return a fatal error back to the user
            return $this->fatalError("Cant get price for");
        }

        //Get stock movement information
        $stockMovement = $dom->find('span[data-reactid=37]')->text();


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

        //Load all the rows in the left table into extraData array
        $tableLeft = $dom->find('div[data-test=left-summary-table] table');
        $this->scrapeTableRow($extraData, $tableLeft);

        //Load all the rows in the right table into extraData array
        $tableRight = $dom->find('[data-test=right-summary-table] table');
        $this->scrapeTableRow($extraData, $tableRight);

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
     * Loop through each row in a given table and get the title and value pair that is held and push onto reference array.
     * This is a mutating function, does not return anything
     * @param $dataArray - Array that has the title and value pushed into
     * @param $table - DOM element that represents a table for extracting current stock data
     */
    private function scrapeTableRow(&$dataArray, $table)
    {
        //Loop through each table row
        foreach ($table->find('tr') as $tr)
        {
            //Key/Value holder to add to the dataArray
            $row = array();
            //Extract the title/heading of the selected row, assume it is the first element
            $td = $tr->find('td', 0);
            $heading = $td->find('span')->text();

            //Set the title in the holder
            $row["title"] = $heading;

            try
            {
                //Extract the value and set the value in the holder
                $row["value"] = $tr->find('td', 1)->find('span')->text();
            }
            catch (\Exception $exception)
            {
                //Extract the value and set the value in the holder
                $row["value"] = $tr->find('td', 1)->text();
            }


            //Add the holder with values to the dataArray reference
            array_push($dataArray, $row);
        }
    }

    /**
     * If there is a 404 error, don't freak the user out, just send back a 404 error with a message wrapped as a JSON string
     * @param string $message - show default message if no message is passed
     * @param int $status - Default status code 404 (not found) unless otherwise specifed
     * @return array
     */
    function fatalError($message = "Could not find ASX item", $status = 404)
    {
        $error = array();
        http_response_code($status);
        $error["message"] = $message;
        $error["code"] = 404;
        return $error;
//        exit(json_encode($error));
    }
}
