<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
	//protected $primaryKey = "stock_symbol";
	//public $incrementing = false;

	protected $fillable = ["stock_symbol",'stock_name', 'current_price','history'];
	// Add function to insert stock price
	public function appendHistory($value) {
		$this->history = json_encode($value + json_decode($this->history));
	}
}
