<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'date',
        'creator_id',
        'type_id',
        'hour_of_day',
        'day_of_week',
        'day_of_month',
        'month_of_year',
        'closed_at'
    ];

    public function eventType()
    {
        return $this->belongsTo('App\EventType', 'type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }
}
