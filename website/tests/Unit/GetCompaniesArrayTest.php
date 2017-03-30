<?php

namespace Tests\Unit;

use App\Console\Commands\getCompanies;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetCompaniesArrayTest extends TestCase
{

    /**
     * Test the Get All Companies function that is expected to return an array of companies
     */
    public function testGetAllCompaniesArray()
    {
        //Get the getCompanies Command object
        $getCompaniesCommand = new getCompanies();

        //Get the list of all companies currently in stock exchange
        $companiesList = $getCompaniesCommand->getAllListedCompanies();

        //Test that the array of companies is greater than 0
        self::assertTrue($this->count($companiesList) > 0);
    }
}
