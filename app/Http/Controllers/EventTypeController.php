<?php

namespace App\Http\Controllers;

use App\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    public function __invoke() {
        return EventType::all();
    }
}
