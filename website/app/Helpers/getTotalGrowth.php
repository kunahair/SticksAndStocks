<?php


class Growth {

    public static function getTotalGrowth($id = null)
    {
        $error = array();

        $totalTradeAccountsGrowth = 0.00;

        try {
            //Get User from Database
            $user = App\User::find($id);

            //If the User is not null, loop through all their trade accounts, get the total growth of each account
            //Keep a count of the amount of trade accounts
            if ($user != null)
            {
                $count = 0;
                foreach ($user->tradingAccounts as $tradingAccount)
                {
                    $totalTradeAccountsGrowth += $tradingAccount->totalGrowth();
                    $count++;
                }
            }

        }
        catch (Exception $exception)
        {
            $error["message"] = "Could not get Total Growth for Users Trade Accounts";
            $error["code"] = 404;
            return $exception;
        }

        //Try to divide the trade account total growth by the number of trade accounts the user has
        try{
            return $totalTradeAccountsGrowth / $count;
        }
        //If there are not trade accounts, there will be a divide by 0 exception, just return 0.00
        catch (DivisionByZeroError $exception)
        {
            return 0.00;
        }
        //Catch any other exceptions and return 0.00
        catch (Exception $exception)
        {
            return 0.00;
        }

    }
}

