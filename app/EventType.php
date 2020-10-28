<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    public $timestamps = false;

    public function events()
    {
        return $this->hasMany('App\Event', 'id', 'type_id');
    }
}
