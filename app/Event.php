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

    public function user()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }
}
