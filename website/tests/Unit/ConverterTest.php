<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConverterTest extends TestCase
{
    /**
     * Make sure the custom MoneyConversionException is thrown when no argument is given
     * @return void
     */
    public function testUSDtoAUDNoArgument()
    {
        //Get instance of CurrencyConverter class
        $converter = new \CurrencyConverter;

        //Setup to expect MoneyConversionException to be thrown
        $this->expectException(\MoneyConversionException::class);

        //Run USDtoAUD with no argument, should throw an exception
        $converter->USDtoAUD();
    }

    /**
     * Make sure the custom MoneyConversionException is thrown when null argument is given
     * @return void
     */
    public function testUSDtoAUDNullArgument()
    {
        //Get instance of CurrencyConverter class
        $converter = new \CurrencyConverter;

        //Setup to expect MoneyConversionException to be thrown
        $this->expectException(\MoneyConversionException::class);

        //Run USDtoAUD with null argument, should throw an exception
        $converter->USDtoAUD(null);
    }

    /**
     * Make sure the custom MoneyConversionException is thrown when string argument is given
     * @return void
     */
    public function testUSDtoAUDStringArgument()
    {
        //Get instance of CurrencyConverter class
        $converter = new \CurrencyConverter;

        //Setup to expect MoneyConversionException to be thrown
        $this->expectException(\MoneyConversionException::class);

        //Run USDtoAUD with string argument, should throw an exception
        $converter->USDtoAUD("10.00");
    }

    /**
     * Convert $10.00AUD to USD. If an exception is thrown at anytime, then the test will fail
     * @return void
     */
    public function testUSDtoAUD()
    {
        //Get instance of CurrencyConverter class
        $converter = new \CurrencyConverter;

        //Convert $10.00USD to AUD
        $converted = $converter->USDtoAUD(10.00);

        //Make sure the returned value is not null
        $this->assertTrue($converted != null);

        //Make sure the returned converted value is numeric
        $this->assertTrue(is_numeric($converted));
    }


}
