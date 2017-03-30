<?php

namespace Tests\Unit;

use App\Console\Commands\getCompany;
use Tests\TestCase;

class GetCompanyHourly extends TestCase
{

    /**
     * Test the Get Hourly data for an individual company by ASX stock code
     */
    public function testGetHourly()
    {
        $getCompany = new getCompany();

        //Test if we get a list of hourly data from a valid company
        $hourlyDataNAB = $getCompany->historyHour('NAB');

        //Test that the data in expected key is there
        $this->assertTrue(!empty($hourlyDataNAB['NAB.AX']));

        //Test that an incorrect company code has been entered, there should be a JSON error returned
        $hourlyDataNABB = $getCompany->historyHour('NABB');

        var_dump($hourlyDataNABB);

        //Make sure the test is not null
        $this->assertTrue($hourlyDataNABB != null);

        //Test that there is a 404 error in JSON
        self::assertTrue($hourlyDataNABB["code"] == 404);
        //Test that there is a message in the JSON
        self::assertTrue($hourlyDataNABB["message"] != null);

    }
}
