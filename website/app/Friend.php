<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['to', 'from', 'timestamp', 'pending', 'accept_view'];

}
