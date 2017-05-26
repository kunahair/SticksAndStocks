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
        if ($code == null)
        {
            return null;
        }

        $stock = \App\Stock::where('stock_symbol', $code)->select('stock_symbol', 'stock_name', 'market', 'id')->first();

        if ($stock == null)
        {
            return null;
        }

        $contents = "";

        try {
            switch ($stock->market)
            {
                case 'ASX':
                    $url = $this->baseURL . $stock->stock_symbol . '.AX' .  $this->simpleAttributes;
                    $contents = file_get_contents($url);
                    break;
                case 'NASDAQ':
                    $url = $this->baseURL . $stock->stock_symbol . $this->simpleAttributes;
                    $contents = file_get_contents($url);
                    break;
                case 'NYSE':
                    $url = $this->baseURL . $stock->stock_symbol . $this->simpleAttributes;
                    $contents = file_get_contents($url);
                    break;
                case 'AMEX':
                    $url = $this->baseURL . $stock->stock_symbol . $this->simpleAttributes;
                    $contents = file_get_contents($url);
                    break;
            }
        }
        catch (ErrorException $exception) {
            return null;
        }

        if ($contents == "")
        {
            $error["message"] = "Stock not found";
            $error["code"] = "404";
            return json_encode($error);
        }

        $array = explode(",", $contents);

        $array["name"] = $array[0];
        $array["date"] = $array[1];
        $array["price"] = $array[2];
        $array["id"] = $stock->id;
        $array["timestamp"] = time();

        unset($array[0]);
        unset($array[1]);
        unset($array[2]);

        if ($array["price"] == null || $array["price"] == "N/A\n")
            $array["price"] = 0.00;

        $array["price"] = str_replace("\n", "", $array["price"]);

        return $array;


    }

    public function getASXStocks()
    {
        //Get results where the market is ASX and their sector is not applicable
        //chunk results in groups of 200
        \App\Stock::where([['market', 'ASX'], ['group', '!=', 'Not Applic']])->take(200)->chunk(200, function ($stocks){

            foreach ($stocks as $stock)
            {

                $current = $this->getSingleStock($stock->stock_symbol);

                if ($current != null) {
                    $currentStock = \App\Stock::where('stock_symbol', $stock->stock_symbol)->first();
                    $currentStock->addHistory($current);
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
}