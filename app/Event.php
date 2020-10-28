<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps = false;

    public function eventType()
    {
        return $this->belongsTo('App\EventType', 'type_id', 'id');
    }
}
