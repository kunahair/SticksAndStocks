<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    protected $fillable = ['to', 'from', 'amount', 'taken', 'to_read', 'from_read', 'timestamp',
        'message_id', 'from_message', 'to_message'];
}
