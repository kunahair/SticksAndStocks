<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //timestamp: Int - UNIX Timestamp
    //bought: Int - amount of Stock purchased
    //sold: Int - amount of Stock sold
    //price: float - price that the share was traded at time of transaction
    //waiting: Bool - is transaction waiting to be processed
    protected $fillable = ['timestamp', 'bought', 'sold', 'price', 'waiting'];

    //trade_account_id - foreign key
    public function trade_account()
    {
        return $this->belongsTo('App\TradeAccount');
    }

    //stock_id - foreign key
    public function stock()
    {
        return $this->belongsTo('App\Stock');
    }
}
