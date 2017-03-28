<?php

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
        $companies = $this->getAllListedCompanies()["companies"];

        // Stock::getQuery()->delete();

        foreach ($companies as $value) {
            $stock = Stock::updateOrCreate(['stock_symbol' => str_replace(array("."," "), "", $value['ASX code']), 'stock_name' => $value['Company name'], 'group' => $value['GICS industry group']]);
        }

        $stock->save();
    }

    /**
     * Get a list of all companies listed on the ASX.
     * Updated Daily
     */
    function getAllListedCompanies()
    {
        //Load CSV file from asx.com.au
        $data = file_get_contents('http://www.asx.com.au/asx/research/ASXListedCompanies.csv');

        //Remove the first 2 lines in the CSV file (file info and blank space)
        $data = substr($data, strpos($data, "\n") + 2);
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
