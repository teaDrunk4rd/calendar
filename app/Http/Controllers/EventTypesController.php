<?php

namespace App\Http\Controllers;

use App\EventType;
use Illuminate\Http\Request;

class EventTypesController extends Controller
{
    public function index() {
        return EventType::all();
    }
}
