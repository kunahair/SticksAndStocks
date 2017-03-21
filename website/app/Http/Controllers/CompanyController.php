<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Faker\Provider\cs_CZ\DateTime;
use Psy\Util\Json;

class CompanyController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $code
     * @return Response
     */
    public function historyHour($code)
    {
        return response($this->hourly($code), 200);
    }

    private function hourly($code = null)
    {
        //http://chartapi.finance.yahoo.com/instrument/1.0/NAB.AX/chartdata;type=quote;range=10d/json

        date_default_timezone_set('Australia/Melbourne');

        if ($code != null)
            $code = $code . ".AX";
        else
            $code = "NAB.AX";

        $url = "http://chartapi.finance.yahoo.com/instrument/1.0/" . $code . "/chartdata;type=quote;range=1d/json";


        $contents = file_get_contents($url);

        $contents = str_replace("finance_charts_json_callback( ", "", $contents);
        $contents = str_replace(")", "", $contents);

//        echo $contents;

//        unserialize($contents);
        $historyJSON = \GuzzleHttp\json_decode($contents, true);

        $series = $historyJSON["series"];

        $hrArray = array();

        //"Timestamp" :1488928197,"close" :32.3400,"high" :32.3700,"low" :32.3200,"open" :32.3600,"volume" :59700
        $index = 0;
        foreach ($series as $detail)
        {
            $avg = ($detail["high"] + $detail["low"]) / 2.00;
            $avg = round($avg, 2);
            $avg = number_format($avg, 2, '.', '');

            $avg = (float) $avg;

            $date = new \DateTime();
            $date->setTimestamp($detail["Timestamp"]);

            $hrArray[$index] = array(
                "time" => $date->format("H:i:s"),
                "average" => $avg,
                "close" => $detail["close"],
                "high" => $detail["high"],
                "low" => $detail["low"],
                "open" => $detail["open"],
                "volume" => $detail["volume"]
            );

            $index++;
        }

        if (empty($hrArray))
            return "";

        $data = array();
        $data[$code] = $hrArray;

        return $data;

//        echo $history;
//        var_dump($history);
//        exit();
    }
}



































