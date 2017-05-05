<?php

/**
 * Created by PhpStorm.
 * User: joshgerlach
 * Date: 5/5/17
 * Time: 4:15 PM
 */

class MoneyConversionException extends Exception {
    protected $code = 500;
    protected $message = "There was a problem converting currency";
}

class CurrencyConverter
{

    /**
     * Convert amount USD to AUD
     * @param null $amount
     * @return float
     * @throws Exception
     * @throws MoneyConversionException
     */
    public function USDtoAUD($amount = null)
    {
        //Make sure amount is provided, otherwise throw exception
        if ($amount == null)
            throw new MoneyConversionException();

        //Check that the amount is a number and not a string, throw exception if so
        if (!is_numeric($amount) || is_string($amount))
            throw new MoneyConversionException();

        try
        {
            //Get the conversion info from API
            $convertAPIJSONString = file_get_contents('http://api.fixer.io/latest?base=USD&symbols=AUD');

            //Convert API string contents to array
            $convertAPIJSON = json_decode($convertAPIJSONString, true);
        }
        catch (Exception $exception)
        {
            //Catch any exception that might be thrown if conversion API is not available
            throw $exception;
        }

        //If the array is null, throw exception
        if ($convertAPIJSON == null)
            throw new MoneyConversionException();

        try
        {
            //Get the current AUD to USD value
            $aud = $convertAPIJSON["rates"]["AUD"];
        }
        catch (Exception $exception)
        {
            //Catch any exceptions that might get thrown
            throw $exception;
        }

        //If the AUD value is not a number, throw exception
        if (!is_numeric($aud))
            throw new MoneyConversionException();

        //Return the AUD amount multiplied by the USD argument amount
        return $aud * $amount;
    }
}