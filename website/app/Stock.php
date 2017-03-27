<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
	//protected $primaryKey = "stock_symbol";
	//public $incrementing = false;

	protected $fillable = ["stock_symbol",'stock_name', 'current_price','history','group'];
	// Add function to insert stock price -- Useless by me.
	public function appendHistory($value) {
		date_default_timezone_set('Australia/Melbourne');
		$current_date = date('d-m-y');
		

		if ($this->history != null) {
			// Override current day's history
			$database_data = json_decode($this->history, true);
			$database_data[$current_date] = $value;
			$this->history = json_encode($database_data);
		} else {
			// Insert the first piece of data which isn't null
			$newData = [ $current_date => $value ];
			$this->history = json_encode($newData);
		}

		$this->save();
	}
}
