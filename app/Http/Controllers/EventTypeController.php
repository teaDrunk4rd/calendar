<?php

namespace App\Http\Controllers;

use App\EventType;

class EventTypeController extends Controller
{
    public function __invoke(EventType $eventType)
    {
        return $eventType->all();
    }
}
