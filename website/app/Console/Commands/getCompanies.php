<?php

/**
 * Created by: Paul Davidson.
 * Authors: Paul Davidson and Josh Gerlach
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Provider\cs_CZ\DateTime;
use Psy\Util\Json;
use PHPHtmlParser\Dom;
use App\Stock as Stock;

class getCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:getAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all companies on the ASX';

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
        // Delete all stocks
        Stock::getQuery()->delete();

        //Get ASX stock companies
        $companies_asx = $this->getAllListedCompanies('http://www.asx.com.au/asx/research/ASXListedCompanies.csv',true)["companies"];
        //Get NASDAQ companies
        $companies_nasdaq = $this->getAllListedCompanies('http://www.nasdaq.com/screening/companies-by-name.aspx?letter=0&exchange=nasdaq&render=download',false)["companies"];
        //Get NYSE companies
        $companies_nyse = $this->getAllListedCompanies('http://www.nasdaq.com/screening/companies-by-name.aspx?letter=0&exchange=nyse&render=download',false)["companies"];
        //Get AMEX companies
        $companies_amex = $this->getAllListedCompanies('http://www.nasdaq.com/screening/companies-by-name.aspx?letter=0&exchange=amex&render=download',false)["companies"];

        //Default market to ASX
        $market = "ASX";
        $stockCount = 0;
        //Put all ASX Companies into Database
        foreach ($companies_asx as $value) {
            $stockCount = ++$stockCount;
            print($stockCount . "\t[ " . $market . " ]\t" . $value['ASX code'] . "\t" . $value['Company name'] . "\n");
            $stock = Stock::updateOrCreate(['stock_symbol' => str_replace(array("."," "), "", $value['ASX code']), 'stock_name' => $value['Company name'], 'group' => $value['GICS industry group'], 'market' => $market]);
        }

        $i = 0;
        //Loop through all companies in US stock exchanges
        //Group all the US stock exchanges into an array, then for each one, go through each company and insert into DB
        foreach ([$companies_nasdaq, $companies_nyse, $companies_amex] as $companies) {
          $i = ++$i;
          $stockCount = ++$stockCount;
          //Get the current Stock Exchange list to be inserted into DB
          switch ($i) {
            case 1:
              $market = "NASDAQ";
              break;
            case 2:
              $market = "NYSE";
              break;
            case 3:
              $market = "AMEX";
              break;
          }
          //Insert the companies for current Stock Exchange into Database
          foreach ($companies as $value) {
              $stockCount = ++$stockCount;
              print($stockCount . "\t[ " . $market . " ]\t" . $value['Symbol'] . "\t" . $value['Name'] . "\n");
              $stock = Stock::updateOrCreate(['stock_symbol' => str_replace(array("."," "), "", $value['Symbol']), 'stock_name' => $value['Name'], 'group' => $value['Sector'], 'market' => $market]);
          }
          //When each Stock Exchange has finished, display message
          switch ($i) {
            case 1:
              print("Nasdaq done!\n");
              break;
            case 2:
              print("Nyse done!\n");
              break;
            case 3:
              print("Amex done!\n");
              print("Total Stock Count: " . $stockCount);
              break;
          }
        }
        //Commit all Stocks created to the Databse
        $stock->save();
    }

    /**
     * Get a list of all companies listed on the ASX.
     * Updated Daily
     */
    function getAllListedCompanies($address, $firstLines)
    {
        //Load CSV file from asx.com.au
        $data = "";
        $data = file_get_contents($address);

        //If the list is not retrievable from ASX, then use the local CSV file
        //If the list is available, overwrite the existing csv file.
        // if ($data == false) {
        //     print("Using backup, because ASX is offline. \n");
        //     //Use local backup
        //     $data = file_get_contents('ASXListedCompanies.csv', true);
        // } else {
        //     print("Making Backup, ASX must be online. \n");
        //     //Make a backup of the file with the new companies list
        //     $fp = fopen('ASXListedCompanies.csv', 'w');
        //     fwrite($fp, $data);
        //     fclose($fp);
        // }

        if ($firstLines) {
          //Remove the first 2 lines in the CSV file (file info and blank space)
          $data = substr($data, strpos($data, "\n") + 2);
        }
        //Make sure the start of the CSV file is not a new line
        $dataCSV = str_replace("\nCompany name,", "Company name", $data);
        //Get rid of any quotation marks
        $dataCSV = str_replace("\"", "", $dataCSV);
        //Remove all \r (return character)
        $dataCSV = str_replace("\r", "", $dataCSV);
        //Make sure this is a comma between Company name and SSX code
        $dataCSV = str_replace("nameASX", "name,ASX", $dataCSV);

        //Move all rows into an array
        $rows = explode("\n", $dataCSV);
        //New return array
        $array = array();
        //Array to store all headings in
        $headings = array();
        //Row holder for current CSV Row
        $csvRow = array();
        //Index tracking for current row
        $index = 0;

        foreach ($rows as $row)
        {
            //If the row is blank move the to next
            if ($row == "")
            {
                continue;
            }

            //If this is the first time through the loop
            //The first row is the headings so capture them in a different array, increase index and continue
            if ($index == 0)
            {
                $headings = str_getcsv($row);
                $index++;
                continue;
            }

            //Load the selected row into csvRow
            $csvRow[0] = str_getcsv($row);

            //Create a new companyRow variable
            $companyRow = array();
            //For every heading, extract the corresponding value from current row and load into companyRow array
            for ($j = 0; $j < count($headings); $j++)
                $companyRow[$headings[$j]] = $csvRow[0][$j];

            //If there are more elements in the current row than there are headings, then assume that is extra company data
            //and add to the end of the last element, separated by a commma
            if (count($csvRow[0]) > count($headings))
            {
                for ($j = 3; $j < count($csvRow[0]); $j++)
                    $companyRow[$headings[2]] = $companyRow[$headings[2]] . ", " . $csvRow[0][$j];
            }

            //Add the companyRow to the holding Array (array)
            $array[$index - 1] = $companyRow;

            //Increment the index
            $index++;
        }

        //Wrap up the companies data into a nice array
        $data = array();
        $data["companies"] = $array;
        //Respond and send data as JSON String
        return $data;
    }
}
