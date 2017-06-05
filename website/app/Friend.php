<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['to', 'from', 'timestamp', 'pending', 'accept_view'];

}
