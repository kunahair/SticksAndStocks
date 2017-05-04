<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
	//protected $primaryKey = "stock_symbol";
	//public $incrementing = false;

	protected $fillable = ["stock_symbol",'stock_name', 'current_price','history','group','top_lists'];
	// Add function to insert stock price -- Useless by me.
	public function appendHistory($value) {
		if ($value != null) {
			// Set the current price
			$this->current_price = end($value[1])['average'];

			if ($this->history != null) {
				// Override current day's history
				$database_data = json_decode($this->history, true);
				$database_data[$value[0]] = $value[1];
				$this->history = json_encode($database_data);
			} else {
				// Insert the first piece of data which isn't null
				$newData = [$value[0] => $value[1]];
				$this->history = json_encode($newData);
			}

			$this->save();
		}
	}

	public function appendTopLists($value) {
	    // Check for non-empty list
        if ($this->top_lists != null) {
            // Push into lists
            $currentLists = json_decode($this->top_lists);
            // Don't double up on lists in this array
            if (in_array($value,$currentLists) == false) {
                array_push($currentLists, $value);
            }
            $this->top_lists = json_encode($currentLists);
        // Check for empty list
        } else {
            $currentLists = [$value];
            $this->top_lists = json_encode($currentLists);
        }
        // Encode it back into String & save it into the database
        $this->save();
    }

    public function addHistory($value)
    {
        $stock_id = DB::table('stocks')->select('id')->where('stock_symbol', $value["code"])->first();

        $lastTimestamp = DB::table('stock_histories')->where('stock_id', $stock_id->id)->max('timestamp');

        if ($lastTimestamp == null)
        {
            foreach ($value[1] as $timeseries)
            {

                $history = new StockHistory;

                $history->stock_id = $stock_id->id;
//                $history->low = $timeseries["low"];
//                $history->high = $timeseries["high"];
//                $history->open = $timeseries["open"];
//                $history->time = $timeseries["time"];
                $history->timestamp = $timeseries["timestamp"];
//                $history->close = $timeseries["close"];
//                $history->volume = $timeseries["volume"];
                $history->average = $timeseries["average"];

//                var_dump($timeseries["time"]);

                $history->save();
            }
        }
        else
        {
            foreach ($value[1] as $timeseries)
            {

                if ($timeseries["timestamp"] <= $lastTimestamp)
                    continue;

                $history = new StockHistory;

                $history->stock_id = $stock_id->id;
                $history->timestamp = $timeseries["timestamp"];
                $history->average = $timeseries["average"];

                $history->save();
            }
        }

        // Last History Value = Current Price
        $this->current_price = end($value[1])['average'];
        $this->save();

        var_dump($value["code"]);

    }

    public function getHistory(){
	    return $this->hasMany('App\StockHistory');
    }

    //Get the Latest Day's History for this Stock
    public function getLatestHistory()
    {
        //Try to get the latest Timestamp of data
        //If there is no data for this stock company in the Histories Table, then return empty array
        try {
            $timestamp = $this->getHistory()->orderBy('timestamp', 'desc')->first()->timestamp;
        }
        catch (\Exception $exception)
        {
            return json_encode(array(), JSON_FORCE_OBJECT);
        }

        //Get all the history for this stock
        $allHistories = $this->getHistory()->orderBy('timestamp', 'desc')->get();

        //History holder for only the latest day, to be returned to caller
        $latestHistoryArray = array();

        //Loop through all the histories, starting at the latest day
        //If the next row of data is in the same day, add it to be returned
        //Otherwise move onto the next row
        foreach ($allHistories as $history)
        {
            if (( $timestamp - $history->timestamp) < 5000 )
            {
                $timestamp = $history->timestamp;
                array_push($latestHistoryArray, $history);
            }

        }

        //Return Latest History data to the caller in JSON format for use in Javascript
        return json_encode($latestHistoryArray, JSON_FORCE_OBJECT);

    }

}
