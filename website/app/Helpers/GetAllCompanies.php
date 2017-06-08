<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Paul Davidson and Josh Gerlach
 */

class StockNotFoundException extends ErrorException{
}

class NullStockCodeException extends ErrorException{

}

class GetAllCompanies
{
    private $baseURL = "http://finance.yahoo.com/d/quotes.csv?s=";
    private $downloadURL = "http://download.finance.yahoo.com/d/quotes.csv?s=";
    private $completeAttributes = "&f=nabl1t1c1p2ohgpwkjdqr1y";
    private $simpleAttributes = "&f=nd1l1";
    private $externalURL = "http://139.59.240.148/getPrice.php?code=";

    private $googleAPIBaseURL = "https://www.google.com/finance/getprices?i=300&p=1d&f=d,o,h,l,c,v&df=cpct&q=";

    //Facade that groups the getting of all Stock information over multiple Stock Exchanges
    public function getCompanies()
    {
        $this->getASXStocks();
        $this->getNASDAQStocks();
        $this->getNYSEStocks();
        $this->getAMEXStocks();
    }

    public function getSingleStock($code = null)
    {

        //If no stock code is provided, then return null
        if ($code == null)
            return null;

        //Get the Stock information from database
        $stock = \App\Stock::where('stock_symbol', $code)->select('stock_symbol', 'stock_name', 'market', 'id')->first();

        //If the stock does not exist, then return null
        if ($stock == null)
            return null;

        //API response holder
        $contents = "";

        //Filter by market and get contents of API call, if ASX then add .AX to the call
        try {
            switch ($stock->market)
            {
                case 'ASX':
                    $url = $this->externalURL . $stock->stock_symbol . '.AX';
                    $contents = file_get_contents($url);
                    break;
                case 'NASDAQ':
                    $url = $this->externalURL . $stock->stock_symbol;
                    $contents = file_get_contents($url);
                    break;
                case 'NYSE':
                    $url = $this->externalURL . $stock->stock_symbol;
                    $contents = file_get_contents($url);
                    break;
                case 'AMEX':
                    $url = $this->externalURL . $stock->stock_symbol;
                    $contents = file_get_contents($url);
                    break;
            }
        }
        catch (ErrorException $exception) {
            return null;
        }

        //If there was no content provided by API call, return null
        if ($contents == "")
            return null;


        //Split each row into array element
        $array = explode("\n", $contents);

        //Remove the first 6 rows as it is information about document
        for($i = 0; $i < 7; $i++)
            unset($array[$i]);

        //Copy the array into a new array so the index starts a 0 and not 7
        $newArray = call_user_func('array_merge', $array);

        //If there are no rows in the new array, return null
        if (count($newArray) < 2)
            return null;

        //Process the CSV data for the selected Stock and return the history data as an array
        return $this->processStockCSVData($newArray, $stock);


//        //Get the base time stamp, that will be used to multiply and get the time stamps for the other rows
//        $baseTimestamp = explode(",", $newArray[0])[0];
//        $baseTimestamp = str_replace("a", "", $baseTimestamp);
//        $baseTimestamp = intval($baseTimestamp);
//
//        //Return data holder
//        $dataArray = array();
//
//        //Get the current exchange rate in case we are not in ASX
//        $currencyConverter = new CurrencyConverter;
//        $exchangeRate = $currencyConverter->USDtoAUD(1.00);
//
//        $index = 0;
//        foreach ($array as $item)
//        {
//            //Split string row into array elements
//            $row = explode(",", $item);
//
//            //If we have no element at index 1 then it is invalid data and we move onto the next
//            if (!isset($row[1]))
//            {
//                $index++;
//                continue;
//            }
//
//            //If we are not in the first row, then we can get the
//            //base time stamp, and mulitply it by 300 (5 mins) and add it to the base time to give
//            //us the timestamp for this row
//            if($index != 0)
//            {
//                $timeOffset = $row[0];
//                $timeOffset = intval($timeOffset);
//                $timeOffset = $baseTimestamp + (300 * $timeOffset);
//                $row["timestamp"] = $timeOffset;
//            }else {
//                //Otherwise we are at the first element and we can just assign the base timestamp to it
//                $row["timestamp"] = $baseTimestamp;
//            }
//
//            //Set the value of the average as the closing price of this time frame
//            $row["average"] = floatval($row[1]);
//
//            //If the market is not ASX, then we assume it is US and convert the average from US to AUD
//            if ($stock->market != "ASX")
//                $row["average"] *= $exchangeRate;
//
//            //Get rid of the elements in the row that we will not be using
//            unset($row[0]);
//            unset($row[1]);
//            unset($row[2]);
//            unset($row[3]);
//            unset($row[4]);
//            unset($row[5]);
//
//            //Add this row to the data holder array
//            $dataArray[$index] = $row;
//
//            //Increment the index we are at
//            $index++;
//        }
//
//        //Return the data holder array
//        return $dataArray;

    }

    /**
     * Extract stock data provided by CSV string and Stock information
     * @param $contentsData - Array that contains historical data of a single Stock
     * @param $stock - Stock object that represents the current state of the Stock
     * @return array - Array of history data
     */
    private function processStockCSVData($contentsData, $stock)
    {
        //Get the base time stamp, that will be used to multiply and get the time stamps for the other rows
        $baseTimestamp = explode(",", $contentsData[0])[0];
        $baseTimestamp = str_replace("a", "", $baseTimestamp);
        $baseTimestamp = intval($baseTimestamp);

        //Return data holder
        $dataArray = array();

        //Get the current exchange rate in case we are not in ASX
        $currencyConverter = new CurrencyConverter;
        $exchangeRate = $currencyConverter->USDtoAUD(1.00);

        $index = 0;
        foreach ($contentsData as $item)
        {
            //Split string row into array elements
            $row = explode(",", $item);

            //If we have no element at index 1 then it is invalid data and we move onto the next
            if (!isset($row[1]))
            {
                $index++;
                continue;
            }

            //If we are not in the first row, then we can get the
            //base time stamp, and mulitply it by 300 (5 mins) and add it to the base time to give
            //us the timestamp for this row
            if($index != 0)
            {
                $timeOffset = $row[0];
                $timeOffset = intval($timeOffset);
                $timeOffset = $baseTimestamp + (300 * $timeOffset);
                $row["timestamp"] = $timeOffset;
            }else {
                //Otherwise we are at the first element and we can just assign the base timestamp to it
                $row["timestamp"] = $baseTimestamp;
            }

            //Set the value of the average as the closing price of this time frame
            $row["average"] = floatval($row[1]);

            //If the market is not ASX, then we assume it is US and convert the average from US to AUD
            if ($stock->market != "ASX")
                $row["average"] *= $exchangeRate;

            //Get rid of the elements in the row that we will not be using
            unset($row[0]);
            unset($row[1]);
            unset($row[2]);
            unset($row[3]);
            unset($row[4]);
            unset($row[5]);

            //Add this row to the data holder array
            $dataArray[$index] = $row;

            //Increment the index we are at
            $index++;
        }

        //Return the data holder array
        return $dataArray;

    }

    /**
     * Update ASX Stock information
     */
    public function getASXStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'ASX'], ['group', '!=', 'Not Applic']])->chunk(200, function ($stocks){

            foreach ($stocks as $stock)
            {
                //Get the stock code of current stock
                $code = $stock->stock_symbol;

                //Get the price of the current stock
                $price = $this->getPrice($code . ".AX");

                //If the price is N/A, move to next code
                if ($price == "N/A")
                {
                    print "No data for " . $code . " \n";
                    continue;
                }

                //Format the price so it only has 2 decimal places
                $price = number_format(floatval($price), 2);

                //Update the current price of current stock
                if ($price != null) {
                    $stock->current_price = floatval($price);
                    var_dump($stock->current_price);
                }
            }

        });
    }

    /**
     * Update NASDAQ Stock information
     */
    public function getNASDAQStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'NASDAQ'], ['group', '!=', 'Not Applic']])->chunk(200, function ($stocks) {
            $this->updateUSStock($stocks);
        });
    }

    /**
     * Update NYSE Stock information
     */
    public function getNYSEStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'NYSE'], ['group', '!=', 'Not Applic']])->chunk(200, function ($stocks) {
            $this->updateUSStock($stocks);
        });
    }

    /**
     * Update AMEX Stock information
     */
    public function getAMEXStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'AMEX'], ['group', '!=', 'Not Applic']])->chunk(200, function ($stocks) {
            $this->updateUSStock($stocks);
        });
    }

    /**
     * Updates current price for each stock in given list of stocks.
     * Does not add any market extension to API call
     * @param $stocks - Eloquent list of Stocks
     */
    private function updateUSStock($stocks)
    {
        //Get the current exchange rate of US to AUD
        $currencyConverter = new CurrencyConverter;
        $exchangeRate = $currencyConverter->USDtoAUD(1.00);

        foreach ($stocks as $stock) {

            //Get the stock code of current stock
            $code = $stock->stock_symbol;

            //Get the price of the current stock
            $price = $this->getPrice($code);

            //If the stock is N/A, move to next stock
            if ($price == "N/A")
            {
                print "No data for " . $code . " \n";
                continue;
            }

            //Convert to AUD based on current UStoAUD exchange rate
            $price= number_format($exchangeRate * floatval($price), 2);

            //Update the price of current stock
            if ($price != null)
            {
                $stock->current_price = floatval($price);
                print $stock->stock_name . "(" . $stock->stock_symbol . "): " . $price . "\n";
            }
        }
    }

    /**
     * Does API call to get the current price of a stock by code
     * @param null $code - Stock code, must include market code eg: .AX at end of code if outside US
     * @return string - price of stock
     */
    private function getPrice($code = null)
    {
        //If there is no code selected, return N/A
        if ($code == null)
            return "N/A";

        //Construct url API call
        $url = $this->downloadURL . $code . $this->simpleAttributes;

        //Get the contents of CSV file from
        $contents = file_get_contents($url);

        //Put top level into array
        $contents = explode("\n", $contents);

        //Take just the first row, which has data of concern and put all values into an array
        $contents = explode(",", $contents[0]);

        //Return the closing price
        return $contents[2];
    }
}