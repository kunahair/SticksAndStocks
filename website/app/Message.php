<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['to', 'from', 'message', 'timestamp'];

    public function money()
    {
        $this->hasOne('App\Money');
    }
}
