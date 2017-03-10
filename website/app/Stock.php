<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
	protected $fillable = ['stock_symbol','current_price','history'];
}
