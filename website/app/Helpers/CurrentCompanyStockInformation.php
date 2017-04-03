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
        $dom->load($base_link . '/quote/' . $code . '.AX');

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
            $price = $contents->find('[data-reactid=241]')->text();
        }catch (\Exception $errorException)
        {
            //If an error occures, return a fatal error back to the user
            return $this->fatalError();
//            exit(json_encode($this->fatalError()));
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


        $tableLeft = $dom->find('[data-test=left-summary-table] table');

//        echo($tableLeft);

        foreach ($tableLeft->find('tr') as $tr)
        {
            $row = array();
            $td = $tr->find('td', 0);
            $heading = $td->find('span')->text();

            $row["title"] = $heading;
            $row["value"] = $tr->find('td', 1)->text();

            array_push($extraData, $row);

//            $extraData[$heading] = $tr->find('td', 1)->text();
        }

        $tableRight = $dom->find('[data-test=right-summary-table] table');

        foreach ($tableRight->find('tr') as $tr)
        {
            $row = array();
            $td = $tr->find('td', 0);
            $heading = $td->find('span')->text();

            $row["title"] = $heading;
            $row["value"] = $tr->find('td', 1)->text();

            array_push($extraData, $row);

//            $extraData[$heading] = $tr->find('td', 1)->text();
        }

//        var_dump($extraData);

//        //Previous close
//        extractCurrentTableRowFromDom($dom, $extraData, 321, 322);
////        $heading = $dom->find('[data-reactid=321]')->text();
////        $value = $dom->find('[data-reactid=322]')->text();
////        $extraData[$heading] = $value;
//
//        //Open
//        extractCurrentTableRowFromDom($dom, $extraData, 325, 326);
////        $heading = $dom->find('[data-reactid=325]')->text();
////        $value = $dom->find('[data-reactid=326]')->text();
////        $extraData[$heading] = $value;
//
//        //Bid
//        extractCurrentTableRowFromDom($dom, $extraData, 329, 330);
////        $heading = $dom->find('[data-reactid=329]')->text();
////        $value = $dom->find('[data-reactid=330]')->text();
////        $extraData[$heading] = $value;
//
//        //Ask
//        extractCurrentTableRowFromDom($dom, $extraData, 333, 334);
////        $heading = $dom->find('[data-reactid=333]')->text();
////        $value = $dom->find('[data-reactid=334]')->text();
////        $extraData[$heading] = $value;
//
//        //Days Range
//        extractCurrentTableRowFromDom($dom, $extraData, "Days range", 338);
////        $heading = 'Days range';
////        $value = $dom->find('[data-reactid=338]')->text();
////        $extraData[$heading] = $value;
//
//        //52 Week Range
//        extractCurrentTableRowFromDom($dom, $extraData, "Year week range", 342);
////        $heading = $dom->find('[data-reactid=341]')->text();
////        $value = $dom->find('[data-reactid=342]')->text();
////        $extraData[$heading] = $value;
//
//        //Volume
//        extractCurrentTableRowFromDom($dom, $extraData, 345, 346);
////        $heading = $dom->find('[data-reactid=345]')->text();
////        $value = $dom->find('[data-reactid=346]')->text();
////        $extraData[$heading] = $value;
//
//        //Average Volume
//        extractCurrentTableRowFromDom($dom, $extraData, 349, 350);
////        $heading = $dom->find('[data-reactid=349]')->text();
////        $value = $dom->find('[data-reactid=350]')->text();
////        $extraData[$heading] = $value;
//
//        //Table 2
//        //Market Cap
//        extractCurrentTableRowFromDom($dom, $extraData, 356, 357);
////        $heading = $dom->find('[data-reactid=356]')->text();
////        $value = $dom->find('[data-reactid=357]')->text();
////        $extraData[$heading] = $value;
//
//        //Beta
//        extractCurrentTableRowFromDom($dom, $extraData, 360, 361);
////        $heading = $dom->find('[data-reactid=360]')->text();
////        $value = $dom->find('[data-reactid=361]')->text();
////        $extraData[$heading] = $value;
//
//        //PE ratio (TTM)
//        extractCurrentTableRowFromDom($dom, $extraData, "PEratio", 365);
////        $heading = $dom->find('[data-reactid=364]')->text();
////        $value = $dom->find('[data-reactid=365]')->text();
////        $extraData[$heading] = $value;
//
//        //EPS (TTM)
////        $heading = $dom->find('[data-reactid=368]')->text();
////        $value = $dom->find('[data-reactid=369]')->text();
////        $extraData[$heading] = $value;
//
//        //Earnings Data
////        $heading = $dom->find('[data-reactid=372]')->text();
////        $value = $dom->find('[data-reactid=373]')->text();
////        $extraData[$heading] = $value;
//
//        //Dividend and Yield
//        $value = $dom->find('[data-reactid=377]')->text();
//
//        //Separate values into array
//        $dayArray = explode(" ", $value);
//
//
//        //Dividend
//        $heading = "Dividend";
//        $value = $dayArray[0];
//        $extraData[$heading] = $value;
//
//        //Remove brackets from Yield
//        $yield = $dayArray[1];
//        $yield = str_replace("(", "", $yield);
//        $yield = str_replace(")", "", $yield);
//
//        //Yield
//        $heading = "Yield";
//        $value = $yield;
//        $extraData[$heading] = $value;
//
//
//        //Ex-dividend Date
////        $heading = $dom->find('[data-reactid=380]')->text();
////        $value = $dom->find('[data-reactid=381]')->text();
////        $extraData[$heading] = $value;
//
//        //1year target estimation
//        extractCurrentTableRowFromDom($dom, $extraData, 384, 385);
////        $heading = $dom->find('[data-reactid=384]')->text();
////        $value = $dom->find('[data-reactid=385]')->text();
////        $extraData[$heading] = $value;


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