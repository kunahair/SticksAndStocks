<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use PHPHtmlParser\Dom;


class Top20Controller extends Controller
{
    /**
     * Get the list of top 20 companies by market capitalisation, sort list by market-cap and return JSON
     * Includes company code, company name, company sector, market cap and weight in percentage
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getList()
    {
        //Load the top20 webpage into DOM
        $url = "https://www.asx20list.com/";
        $dom = new Dom();

        try {
            $dom->load($url);
        }catch (\Exception $exception){
            return $this->fatalError("Unable to load top 20 list", 404);
        }


        //Get the table that lists the top 20
        $table = $dom->find('table', 0);

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
        usort($list, function($a, $b) {
           return $a["market-cap"] <=> $b["market-cap"];
        });

        //Change the values of market-cap from int back to string with commas every thousand
        $index = 0;
        foreach ($list as $row)
        {
            $marketCapFormat = number_format($row["market-cap"]);
            $list[$index]["market-cap"] = $marketCapFormat;
        }

        //Create new wrapper array to return formatted data
        $data = array();
        //Add top20 key and set value as list of captured companies
        $data["top20"] = $list;

        //Return the list with a 200 status code
        return response($data, 200);
    }

    /**
     * If there is a 404 error, don't freak the user out, just send back a 404 error with a message wrapped as a JSON string
     * @param string $message - show default message if no message is passed
     * @param int $status - Default status code 404 (not found) unless otherwise specifed
     */
    function fatalError($message = "Could not find ASX item", $status = 404)
    {
        $error = array();
        http_response_code($status);
        $error["message"] = $message;
        $error["code"] = 404;
        exit(json_encode($error));
    }
}