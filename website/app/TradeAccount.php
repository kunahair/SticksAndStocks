<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradeAccount extends Model
{
    
    protected $fillable = ['name'];

    public function user() {
        return $this->belongsTo('App\User');
    }


    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function totalGrowth()
    {
        //Holder for grouped transactions
        $transactions = array();

        $this->groupTransactions($transactions);

//        if ($transactions != null)
//            return;

        $allStocksTotalValue = 0.00;
        $allStocksTotalCount = 0;
        $allStocksTotalGrowth = 0.00;
        $stockCount = 0;

        //Loop through each transaction group
        //Inner loop the individual transactions for that group
        //For each individual transaction that is not in a waiting state, gather statistics
        foreach ($transactions as $transactionsGroup)
        {
            //Stock stats and info
//            $stock_symbol = "";
//            $stock_name = "";
//            $stock_total_cost = 0.00;
//            $stock_owned = 0;
//            $stock_sold = 0;
//            $stock_total_growth = 0.00;
//            $stock_current_price = 0.00;
//
//            $assignOnce = 0;

            //Get the stats for current Stock
            $stockStats = $this->calculateStockStats($transactionsGroup);

            //Assign values based on Stock Stats
            $stock_total_cost = $stockStats["stockTotalCost"];
            $stock_owned = $stockStats["stockOwned"];
            $stock_sold = $stockStats["stockSold"];
            $stock_current_price = $stockStats["stockCurrentPrice"];

//            foreach ($transactionsGroup as $transaction)
//            {
//                //If the current transaction is waiting, then move onto the next one
//                if ($transaction->waiting)
//                {
//                    continue;
//                }
//
//                //To save memory, just capture the name, symbol and current price of stock group once
//                if ($assignOnce == 0)
//                {
//                    $stock_symbol = $transaction->stock->stock_symbol;
//                    $stock_name = $transaction->stock->stock_name;
//
//                    $stock_current_price = $transaction->stock->current_price;
//
//                    $assignOnce++;
//                }
//
//                //Calculate the initial cost to the user for the stock, add it to total
//                $stock_total_cost += ($transaction->price * ($transaction->bought - $transaction->sold));
//                //Get the amount of stock owned for this transaction, add it to total
//                $stock_owned += $transaction->bought;
//                //Get the amount of stock sold for this transaction, add it to total
//                $stock_sold += $transaction->sold;
//
//                //$stock_total_growth += ($stock_total_cost * ($transaction->bought - $transaction->sold));
//
//            }

            //If the stock owned is less than 1 (it should never hit below 0)
            //Then stock is not needed as it is not working for account in current state
            if ($stock_owned <= 0 || $stock_sold == $stock_owned)
            {
                continue;
            }

            //Calculate the total amount of growth that the account has for this stock (overall NOT average)
            $stock_total_growth = ($stock_total_cost / ($stock_owned - $stock_sold)) - $stock_current_price;


            $allStocksTotalValue += $stock_current_price * ($stock_owned - $stock_sold);
            $allStocksTotalCount += ($stock_owned - $stock_sold);
            $allStocksTotalGrowth -= $stock_total_growth;
            $stockCount++;

        }//End for each transaction group

        return $allStocksTotalGrowth;
    }

    public function getCurrentStock()
    {
//        $transactions = $this->transactions();

        //Holder for grouped transactions
        $transactions = array();

        $groupedStocks = array();

        $this->groupTransactions($transactions);

        $allStocksTotalValue = 0.00;
        $allStocksTotalCount = 0;
        $stockCount = 0;

        //Loop through each transaction group
        //Inner loop the individual transactions for that group
        //For each individual transaction that is not in a waiting state, gather statistics
        foreach ($transactions as $transactionsGroup)
        {
            //Stock stats and info
//            $stock_symbol = "";
//            $stock_name = "";
//            $stock_market = "";
//            $stock_total_cost = 0.00;
//            $stock_owned = 0;
//            $stock_sold = 0;
//            $stock_total_growth = 0.00;
//            $stock_current_price = 0.00;

            //Get the stats for current Stock
            $stockStats = $this->calculateStockStats($transactionsGroup);

            //Assign values based on Stock Stats
            $stock_name = $stockStats["stockName"];
            $stock_symbol = $stockStats["stockSymbol"];
            $stock_market = $stockStats["stockMarket"];
            $stock_total_cost = $stockStats["stockTotalCost"];
            $stock_owned = $stockStats["stockOwned"];
            $stock_sold = $stockStats["stockSold"];
            $stock_current_price = $stockStats["stockCurrentPrice"];

//            $assignOnce = 0;
//
//            foreach ($transactionsGroup as $transaction)
//            {
//                //If the current transaction is waiting, then move onto the next one
//                if ($transaction->waiting)
//                {
//                    continue;
//                }
//
//                //To save memory, just capture the name, symbol and current price of stock group once
//                if ($assignOnce == 0)
//                {
//                    $stock_symbol = $transaction->stock->stock_symbol;
//                    $stock_name = $transaction->stock->stock_name;
//                    $stock_market = $transaction->stock->market;
//
//                    $stock_current_price = $transaction->stock->current_price;
//
//                    $assignOnce++;
//                }
//
//                //Calculate the initial cost to the user for the stock, add it to total
//                $stock_total_cost += ($transaction->price * ($transaction->bought - $transaction->sold));
//                //Get the amount of stock owned for this transaction, add it to total
//                $stock_owned += $transaction->bought;
//                //Get the amount of stock sold for this transaction, add it to total
//                $stock_sold += $transaction->sold;
//
//                //$stock_total_growth += ($stock_total_cost * ($transaction->bought - $transaction->sold));
//
//            }

            //If the stock owned is less than 1 (it should never hit below 0)
            //Then stock is not needed as it is not working for account in current state
            if ($stock_owned <= 0 || $stock_sold == $stock_owned)
            {
                continue;
            }

            //Calculate the total amount of growth that the account has for this stock (overall NOT average)
            $stock_total_growth = ($stock_total_cost / ($stock_owned - $stock_sold)) - $stock_current_price;

            if ($stock_total_growth > 0.00)
                $stock_total_growth *= -1;
            //$stock_total_growth *= ($stock_owned - $stock_sold) * -1.00;

            //Get the growth as a percentage
            if (($stock_total_cost / ($stock_owned - $stock_sold)) == 0.00 ||
                ($stock_total_cost / ($stock_owned - $stock_sold)) == 0.0 || ($stock_total_cost / ($stock_owned - $stock_sold)) == 0)
                continue;

            $stock_total_growth_percentage = ((($stock_current_price / ($stock_total_cost / ($stock_owned - $stock_sold))) * 100)) - 100;

            $groupedStocks[$stock_symbol]["name"] = $stock_name;
            $groupedStocks[$stock_symbol]["symbol"] = $stock_symbol;
            $groupedStocks[$stock_symbol]["market"] = $stock_market;
            $groupedStocks[$stock_symbol]["total_cost"] = number_format($stock_total_cost, 2);
            $groupedStocks[$stock_symbol]["current_price"] = number_format($stock_current_price, 2);
            $groupedStocks[$stock_symbol]["total_growth"] = number_format($stock_total_growth, 2);
            $groupedStocks[$stock_symbol]["total_growth_percentage"] = number_format($stock_total_growth_percentage, 2);
            $groupedStocks[$stock_symbol]["owns"] = ($stock_owned - $stock_sold);


            $allStocksTotalValue += $stock_current_price * ($stock_owned - $stock_sold);
            $allStocksTotalCount += ($stock_owned - $stock_sold);
            $stockCount++;

        }

        $groupedStocks["stats"]["total_stock_count"] = $allStocksTotalCount;

        if ($allStocksTotalCount > 0)
            $groupedStocks["stats"]["average_stock_value"] = number_format(($allStocksTotalValue / $allStocksTotalCount),2);
        else
            $groupedStocks["stats"]["average_stock_value"] = 0.00;

        $groupedStocks["stats"]["total_stock_value"] = number_format($allStocksTotalValue, 2);


        return $groupedStocks;
    }

    /**
     * Group all the transactions into the transactions array
     * @param $transactions - Array where grouped transactions are to be stored
     */
    private function groupTransactions(&$transactions)
    {
        //Loop through all the transactions the current trade account has
        //Group all the transactions into the transactions array
        foreach ($this->transactions as $transaction)
        {
            //If the stock has not been assigned into transactions, add it
            if(!array_key_exists($transaction->stock_id, $transactions))
            {
                $transactions[$transaction->stock_id] = array();
            }
            //Add the current transaction to its transactions group
            array_push($transactions[$transaction->stock_id], $transaction);
        }

    }

    /**
     * Gather and calculate single Stock statistics based on grouped transactions for that Stock
     * Takes array reference to save memory
     *
     * @param $transactionsGroup - Array reference that has all transactions grouped together for single Stock
     * @return array - Stats of stock (stock symbol, name, market, total cost, owned, sold, current price
     */
    private function calculateStockStats(&$transactionsGroup)
    {
        //Stock stats and info
        $stock_symbol = "";
        $stock_name = "";
        $stock_market = "";
        $stock_total_cost = 0.00;
        $stock_owned = 0;
        $stock_sold = 0;
        $stock_current_price = 0.00;

        $assignOnce = 0;

        //Loop through each transaction for stock group and collect stats
        foreach ($transactionsGroup as $transaction)
        {
            //If the current transaction is waiting, then move onto the next one
            if ($transaction->waiting)
                continue;


            //To save memory, just capture the name, symbol and current price of stock group once
            if ($assignOnce == 0)
            {
                $stock_symbol = $transaction->stock->stock_symbol;
                $stock_name = $transaction->stock->stock_name;
                $stock_market = $transaction->stock->market;

                $stock_current_price = $transaction->stock->current_price;
                $assignOnce++;
            }

            //Calculate the initial cost to the user for the stock, add it to total
            $stock_total_cost += ($transaction->price * ($transaction->bought - $transaction->sold));
            //Get the amount of stock owned for this transaction, add it to total
            $stock_owned += $transaction->bought;
            //Get the amount of stock sold for this transaction, add it to total
            $stock_sold += $transaction->sold;
        }

        //Wrap up data into array due to multiple return values
        $data = array();
        $data["stockName"] = $stock_name;
        $data["stockSymbol"] = $stock_symbol;
        $data["stockMarket"] = $stock_market;
        $data["stockTotalCost"] = $stock_total_cost;
        $data["stockOwned"] = $stock_owned;
        $data["stockSold"] = $stock_sold;
        $data["stockCurrentPrice"] = $stock_current_price;

        //Return stats as array
        return $data;
    }

}
