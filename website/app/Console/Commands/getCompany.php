<?php

namespace App\Console\Commands;

require "vendor/autoload.php";

use Illuminate\Console\Command;
use Faker\Provider\cs_CZ\DateTime;
use Psy\Util\Json;
use PHPHtmlParser\Dom;

class getCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:get {code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get today\'s stock values for a company.';

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
        $stock_code = $this->argument('code');
        $indexString = $stock_code . ".AX";

        // print_r($this->historyHour($stock_code)[$indexString]);

        return $this->historyHour($stock_code)[$indexString];
    }

    /**
     * Get the current (or earliest available) hour by hour data for selected listee
     *
     * @param  int  $code
     * @return Response
     */
    public function historyHour($code)
    {
        // return response($this->hourly($code), 200);
        return $this->hourly($code);
    }

    /**
     * Get the hourly data for selected company
     * @param null $code
     * @return array|string
     */
    private function hourly($code = null)
    {
        //http://chartapi.finance.yahoo.com/instrument/1.0/NAB.AX/chartdata;type=quote;range=1d/json

        //Set time and date to Melbourne, needed for Timestamp conversion from Epoch to human readable
        date_default_timezone_set('Australia/Melbourne');

        //Check if the input code is null, if it has not been set default to NAB for testing
        //todo: call fatal error on no $code set
        if ($code != null)
            $code = $code . ".AX";
        else
            $code = "NAB.AX";

        //Base url with input from code to get retrieve data
        $url = "http://chartapi.finance.yahoo.com/instrument/1.0/" . $code . "/chartdata;type=quote;range=1d/json";

        //Get the information for current listee
        $contents = file_get_contents($url);

        //Remove some of the wrapping code that Yahoo! adds
        $contents = str_replace("finance_charts_json_callback( ", "", $contents);
        $contents = str_replace(")", "", $contents);

        if (json_decode($contents) === null) {
            return null;
        }

        // print $contents;

        //Convert the retrieved data to JSON
        $historyJSON = \GuzzleHttp\json_decode($contents, true);

        //We are only concerned with the time and value data, so this key gives us those
        if (array_key_exists("series",$historyJSON) == false) {
            return null;
        }
        $series = $historyJSON["series"];

        //Create outer array to capture data
        $hrArray = array();

        //Loop through each item in the series key, get all data out (see below) and also calculate the average
        //"Timestamp" :1488928197,"close" :32.3400,"high" :32.3700,"low" :32.3200,"open" :32.3600,"volume" :59700
        $index = 0;
        foreach ($series as $detail)
        {
            //Get the Average and convert to String (for max 2 places )
            $avg = ($detail["high"] + $detail["low"]) / 2.00;
            $avg = round($avg, 2);
            $avg = number_format($avg, 2, '.', '');

            //Convert to a float to keep consistent with other values (note that 2.10 will give 2.1)
            $avg = (float) $avg;

            //Convert the timestamp to a human readable date
            $date = new \DateTime();
            $date->setTimestamp($detail["Timestamp"]);

            //Add all values to array with index of current position
            $hrArray[$index] = array(
                "time" => $date->format("H:i:s"),
                "average" => $avg,
                "close" => $detail["close"],
                "high" => $detail["high"],
                "low" => $detail["low"],
                "open" => $detail["open"],
                "volume" => $detail["volume"]
            );

            //Increment the position index
            $index++;
        }

        //If the array is empty something went wrong, so return an error
        if (empty($hrArray))
            return $this->fatalError();

        //Create array to be an outer layer for the data
        //this puts the list of series in the hrArray into a JSON array (better for handling on front end)
        $data = array();
        $data[$code] = $hrArray;

        //Return the data
        return $data;

    }

    /**
     * If there is a 404 error, don't freak the user out, just send back a 404 error with a message wrapped as a JSON string
     */
    function fatalError()
    {
        $error = array();
        http_response_code(404);
        $error["message"] = "Could not find ASX item";
        $error["code"] = 404;
        exit(json_encode($error));
    }
}
