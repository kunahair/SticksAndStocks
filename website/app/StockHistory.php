<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    /**
     *
     *
     * {"13-04-17":
        [
            {
                "low": 32.05,
                "high": 32.1,
                "open": 32.1,
                "time": "10:00:04",
                "close": 32.05,
                "volume": 190700,
                "average": 32.08
            }
     *   ]
       }
     * @var array
     */
    protected $fillable = ['timestamp', 'average'];

    public $timestamps = false;

    public function stock()
    {
        return $this->belongsTo('App\Stock');
    }
}
