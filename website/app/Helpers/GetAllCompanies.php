<?php

/**
 * Created by PhpStorm.
 * User: joshgerlach
 * Date: 25/5/17
 * Time: 10:58 AM
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

    public function getCompanies()
    {
        $this->getASXStocks();
        $this->getNASDAQStocks();
        $this->getNYSEStocks();
        $this->getAMEXStocks();
    }

    public function getSingleStock($code = null)
    {
        $error = array();

        //If no stock code is provided, then return null
        if ($code == null)
        {
            return null;
        }

        //Get the Stock information from database
        $stock = \App\Stock::where('stock_symbol', $code)->select('stock_symbol', 'stock_name', 'market', 'id')->first();

        //If the stock does not exist, then return null
        if ($stock == null)
        {
            print "Stock does not exist in database \n";
            return null;
        }

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
                    $url = $this->googleAPIBaseURL . $stock->stock_symbol;
                    $contents = file_get_contents($url);
                    break;
                case 'NYSE':
                    $url = $this->googleAPIBaseURL . $stock->stock_symbol;
                    $contents = file_get_contents($url);
                    break;
                case 'AMEX':
                    $url = $this->googleAPIBaseURL . $stock->stock_symbol;
                    $contents = file_get_contents($url);
                    break;
            }
        }
        catch (ErrorException $exception) {
            print "Error getting the data \n";
            return null;
        }

        //If there was no content provided by API call, return null
        if ($contents == "")
        {
            print "No contents provided by API \n";
            return null;
        }

        //Split each row into array element
        $array = explode("\n", $contents);

        //Remove the first 6 rows as it is information about document
        for($i = 0; $i < 7; $i++)
            unset($array[$i]);

        //Copy the array into a new array so the index starts a 0 and not 7
        $newArray = call_user_func('array_merge', $array);

        //If there are no rows in the new array, return null
        if (count($newArray) < 2)
        {
            print "No element in API to add to database \n";
            return null;
        }


        //Get the base time stamp, that will be used to multiply and get the time stamps for the other rows
        $baseTimestamp = explode(",", $newArray[0])[0];
        $baseTimestamp = str_replace("a", "", $baseTimestamp);
        $baseTimestamp = intval($baseTimestamp);

        //Return data holder
        $dataArray = array();

        //Get the current exchange rate in case we are not in ASX
        $currencyConverter = new CurrencyConverter;
        $exchangeRate = $currencyConverter->USDtoAUD(1.00);

        $index = 0;
        foreach ($array as $item)
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

    public function getNASDAQStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'NASDAQ'], ['group', '!=', 'Not Applic']])->chunk(200, function ($stocks) {

            //Get the current exchange rate of US to AUD
            $currencyConverter = new CurrencyConverter;
            $exchangeRate = $currencyConverter->USDtoAUD(1.00);

            foreach ($stocks as $stock) {

                //Get stats for current stock
                $current = $this->getSingleStock($stock->stock_symbol);

                //If the stock is not null, then update the database
                if ($current != null) {
                    //Convert to AUD based on current UStoAUD exchange rate
                    $current["price"] *= $exchangeRate;
                    //Get the stock to update
                    $currentStock = \App\Stock::where('stock_symbol', $stock->stock_symbol)->first();
                    //Update the stock history
                    $currentStock->addHistory($current);
                }
            }
        });
    }

    public function getNYSEStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'NYSE'], ['group', '!=', 'Not Applic']])->chunk(200, function ($stocks) {

            //Get the current exchange rate of US to AUD
            $currencyConverter = new CurrencyConverter;
            $exchangeRate = $currencyConverter->USDtoAUD(1.00);

            foreach ($stocks as $stock) {

                //Get stats for current stock
                $current = $this->getSingleStock($stock->stock_symbol);

                //If the stock is not null, then update the database
                if ($current != null) {
                    //Convert to AUD based on current UStoAUD exchange rate
                    $current["price"] *= $exchangeRate;
                    //Get the stock to update
                    $currentStock = \App\Stock::where('stock_symbol', $stock->stock_symbol)->first();
                    //Update the stock history
                    $currentStock->addHistory($current);
                }
            }
        });
    }

    public function getAMEXStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'AMEX'], ['group', '!=', 'Not Applic']])->chunk(200, function ($stocks) {

            //Get the current exchange rate of US to AUD
            $currencyConverter = new CurrencyConverter;
            $exchangeRate = $currencyConverter->USDtoAUD(1.00);

            foreach ($stocks as $stock) {

                //Get stats for current stock
                $current = $this->getSingleStock($stock->stock_symbol);

                //If the stock is not null, then update the database
                if ($current != null) {
                    //Convert to AUD based on current UStoAUD exchange rate
                    $current["price"] *= $exchangeRate;
                    //Get the stock to update
                    $currentStock = \App\Stock::where('stock_symbol', $stock->stock_symbol)->first();
                    //Update the stock history
                    $currentStock->addHistory($current);
                }
            }
        });
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