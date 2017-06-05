<?php

/**
 * Created by: Paul Davidson.
 * Authors: Paul Davidson
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Stock as Stock;
use Faker\Provider\cs_CZ\DateTime;
use Psy\Util\Json;
use PHPHtmlParser\Dom;

class updateTopLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:updateTopLists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all of the companies in top ASX {number} lists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Clear out all top lists first for consistency
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            $allStocks = Stock::where('stock_symbol', $stock->stock_symbol)->update(['top_lists' => null]);
        }

        // Then iterate over all top lists
        $topNumbers = [20,50,100,200,300];

        foreach($topNumbers as $number) {
            $currentList = $this->getList($number);
            print 'ASX' . $number . "\n";
            foreach ($currentList as $company) {
                print $company['code'] . "\n";
                $currentStock = Stock::where('stock_symbol', $company['code'])->first();
                // If stock is not in the database, we're not going to touch it
                if ($currentStock != null) {
                    $currentStock->appendTopLists('ASX' . $number);
                }
            }
        }
    }

    public function getList($count = 20)
    {
        //Load the top20 webpage into DOM
        $url = "https://www.asx20list.com/";

        //Check what list is needed and set the URL accordingly
        //Return fatal error if the count is not valid
        if ($count == 20)
            ;
        else if ($count == 50)
            $url = "https://www.asx50list.com/";
        else if ($count == 100)
            $url = "https://www.asx100list.com/";
        else if ($count == 200)
            $url = "http://www.asx200list.com/";
        else if ($count == 300)
            $url = "https://www.asx300list.com/";
        else
            return $this->fatalError("Unable to load Top " . $count . " List", 404);

        $dom = new Dom();

        //Try to load data from correct site into the DOM, if there is an error return JSON of error with 404 code
        try {
            $dom->load($url);
        }catch (\Exception $exception){
            return $this->fatalError("Unable to load Top " . $count . " List", 404);
        }


        //Get the table that lists the top companies
        $table = $dom->find('table', 0);

        //If the count is 200, the page is layed out differently, so it is the 9th table, not the 0th
        if ($count == 200)
            $table = $dom->find('table', 9);

        //Create a new list to store extracted data
        $list = array();
        //List of headings for data readablility
        $headings = ["code", "company", "sector", "market-cap", "weight(%)"];

        //Loop through each row in the table
        //The first is the headings so ignore that
        //Then get each value in the table and add it to the list, but convert market-cap for sorting
        $index = 0;
        foreach ($table->find('tr') as $tr)
        {
            //Ignore fist row as it is just headings
            if ($index == 0)
            {
                $index++;
                continue;
            }

            //Array to store data from current row
            $data = array();

            //Get the company code, the company name, company sector, and weight in percentage from row
            $data[$headings[0]] = $tr->find("td", 0)->text();
            $data[$headings[1]] = $tr->find("td", 1)->text();
            $data[$headings[2]] = $tr->find("td", 2)->text();
            $data[$headings[4]] = $tr->find("td", 4)->text();

            //Get the market-cap, remove the commas and convert to an Integer for sorting
            $marketCap = $tr->find("td", 3)->text();
            $marketCap = str_replace(",", "", $marketCap);
            $marketCapInt = intval($marketCap);
            $data[$headings[3]] = $marketCapInt;

            //Add the captured row to the list
            $list[$index - 1] = $data;

            //Increment the index counter
            $index++;
        }

        //Sort list by market capitalisation
        //NOTE: Needs PHP 7+ to run as <=> "spaceship" operator is not in PHP 5
        usort($list, function($a, $b) {
            return $a["market-cap"] <=> $b["market-cap"];
        });

        //Change the values of market-cap from int back to string with commas every thousand
        $index = 0;
        foreach ($list as $row)
        {
            $marketCapFormat = number_format($row["market-cap"]);
            $list[$index]["market-cap"] = $marketCapFormat;
            $index++;
        }

        //Create new wrapper array to return formatted data
        $data = array();
        //Add top20 key and set value as list of captured companies
        $data["top" . $count] = $list;

        //Return the list with a 200 status code
        return $list;
    }
}
