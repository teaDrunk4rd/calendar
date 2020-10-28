<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps = false;

    public static $validation = [
        'name' => 'required|max:255',
        'date' => 'required',
        'type_id' => 'required',
    ];

    public function eventType()
    {
        return $this->belongsTo('App\EventType', 'type_id', 'id');
    }
}
