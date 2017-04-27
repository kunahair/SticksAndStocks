<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradeAccount extends Model
{
    
    protected $fillable = ['name', 'balance'];

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
            $stock_symbol = "";
            $stock_name = "";
            $stock_total_cost = 0.00;
            $stock_owned = 0;
            $stock_sold = 0;
            $stock_total_growth = 0.00;
            $stock_current_price = 0.00;

            $assignOnce = 0;

            foreach ($transactionsGroup as $transaction)
            {
                //If the current transaction is waiting, then move onto the next one
                if ($transaction->waiting)
                {
                    continue;
                }

                //To save memory, just capture the name, symbol and current price of stock group once
                if ($assignOnce == 0)
                {
                    $stock_symbol = $transaction->stock->stock_symbol;
                    $stock_name = $transaction->stock->stock_name;

                    $stock_current_price = $transaction->stock->current_price;

                    $assignOnce++;
                }

                //Calculate the initial cost to the user for the stock, add it to total
                $stock_total_cost += ($transaction->price * ($transaction->bought - $transaction->sold));
                //Get the amount of stock owned for this transaction, add it to total
                $stock_owned += $transaction->bought;
                //Get the amount of stock sold for this transaction, add it to total
                $stock_sold += $transaction->sold;

                //$stock_total_growth += ($stock_total_cost * ($transaction->bought - $transaction->sold));

                //echo '<pre>';
                //print_r($transaction->price);
                //echo '</pre>';
            }

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
//            $stock_total_growth_percentage = ((($stock_current_price / ($stock_total_cost / ($stock_owned - $stock_sold))) * 100) - 100) * -1;

            //Add stock information to the holding table
//            echo '<tr>
//                                    <td class="col-xs-1 " style="padding: 0px"><a href="' . "../stock/". $stock_symbol . '">' . $stock_symbol . '</a></td>
//                                    <td class=col-xs-4" style="padding: 0px">' . $stock_name . '</td>
//                                    <td class=col-xs-1" style="padding: 0px">$' . number_format($stock_total_cost, 2) . '</td>
//                                    <td class=col-xs-1" style="padding: 0px">$' . number_format($stock_current_price, 2) . '</td>
//                                    <td class=col-xs-2" style="padding: 0px">$' . number_format($stock_total_growth, 2) . ' (' . number_format($stock_total_growth_percentage, 2) . '%)' . '</td>
//                                    <td class=col-xs-2" style="padding: 0px">' . ($stock_owned - $stock_sold) . '</td>
//                                    <td class=col-xs-1" style="padding: 0px">' . '<a href="#">view</a>' . '</td>
//                                 </tr>';

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

        $allStocksTotalValue = 0.00;
        $allStocksTotalCount = 0;
        $stockCount = 0;

        //Loop through each transaction group
        //Inner loop the individual transactions for that group
        //For each individual transaction that is not in a waiting state, gather statistics
        foreach ($transactions as $transactionsGroup)
        {
            //Stock stats and info
            $stock_symbol = "";
            $stock_name = "";
            $stock_total_cost = 0.00;
            $stock_owned = 0;
            $stock_sold = 0;
            $stock_total_growth = 0.00;
            $stock_current_price = 0.00;

            $assignOnce = 0;

            foreach ($transactionsGroup as $transaction)
            {
                //If the current transaction is waiting, then move onto the next one
                if ($transaction->waiting)
                {
                    continue;
                }

                //To save memory, just capture the name, symbol and current price of stock group once
                if ($assignOnce == 0)
                {
                    $stock_symbol = $transaction->stock->stock_symbol;
                    $stock_name = $transaction->stock->stock_name;

                    $stock_current_price = $transaction->stock->current_price;

                    $assignOnce++;
                }

                //Calculate the initial cost to the user for the stock, add it to total
                $stock_total_cost += ($transaction->price * ($transaction->bought - $transaction->sold));
                //Get the amount of stock owned for this transaction, add it to total
                $stock_owned += $transaction->bought;
                //Get the amount of stock sold for this transaction, add it to total
                $stock_sold += $transaction->sold;

                //$stock_total_growth += ($stock_total_cost * ($transaction->bought - $transaction->sold));

                //echo '<pre>';
                //print_r($transaction->price);
                //echo '</pre>';
            }

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

            $stock_total_growth_percentage = ((($stock_current_price / ($stock_total_cost / ($stock_owned - $stock_sold))) * 100) - 100) * -1;

            $groupedStocks[$stock_symbol]["name"] = $stock_name;
            $groupedStocks[$stock_symbol]["symbol"] = $stock_symbol;
            $groupedStocks[$stock_symbol]["total_cost"] = number_format($stock_total_cost, 2);
            $groupedStocks[$stock_symbol]["current_price"] = number_format($stock_current_price, 2);
            $groupedStocks[$stock_symbol]["total_growth"] = number_format($stock_total_growth, 2);
            $groupedStocks[$stock_symbol]["total_growth_percentage"] = number_format($stock_total_growth_percentage, 2);
            $groupedStocks[$stock_symbol]["owns"] = ($stock_owned - $stock_sold);

            //Add stock information to the holding table
//            echo '<tr>
//                                    <td class="col-xs-1 " style="padding: 0px"><a href="' . "../stock/". $stock_symbol . '">' . $stock_symbol . '</a></td>
//                                    <td class=col-xs-4" style="padding: 0px">' . $stock_name . '</td>
//                                    <td class=col-xs-1" style="padding: 0px">$' . number_format($stock_total_cost, 2) . '</td>
//                                    <td class=col-xs-1" style="padding: 0px">$' . number_format($stock_current_price, 2) . '</td>
//                                    <td class=col-xs-2" style="padding: 0px">$' . number_format($stock_total_growth, 2) . ' (' . number_format($stock_total_growth_percentage, 2) . '%)' . '</td>
//                                    <td class=col-xs-2" style="padding: 0px">' . ($stock_owned - $stock_sold) . '</td>
//                                    <td class=col-xs-1" style="padding: 0px">' . '<a href="#">view</a>' . '</td>
//                                 </tr>';

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

//        echo '</tbody></table>';
//
//        //Show the average Stock value of this Trade Account
//        echo '<div class="col-xs-12" style="padding-left: 0">';
//
//        if ($allStocksTotalCount > 0)
//            echo '<h4>Stock Average Value: $' . number_format(($allStocksTotalValue / $allStocksTotalCount),2) . 'AUD</h4>';
//
//        echo '</div>';
//
//        //Show the total Stock value of this Trade Account
//        echo '<div class="col-xs-12" style="padding-left: 0">';
//
//        echo '<h4>Stock Total Value: $' . number_format($allStocksTotalValue, 2) . 'AUD</h4>';
//
//        echo '</div>';

        return $groupedStocks;
    }

}
