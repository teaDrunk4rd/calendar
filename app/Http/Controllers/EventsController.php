<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventType;
use DateTime;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index() {
        return Event::orderBy('hour_of_day')
            ->orderBy('type_id', 'desc')
            ->get();
    }

    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'date' => 'required',
            'type_id' => 'required',
        ]);

        $event = new Event(); // Event::create($request->all());
        $this->editEvent($event, $request);
        $event->save();

        return response()->json($event, 201);
    }

    public function read(Request $request) {
        return Event::find($request['id']);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'date' => 'required',
            'type_id' => 'required',
        ]);

        $event = Event::find($request['id']);
        $this->editEvent($event, $request);
        $event->save();

        return response()->json($event, 200);
    }

//    public function delete() {
//
//        $event->delete();
//        return response()->json(null, 204);
//    }

    private function editEvent(Event $event, Request $request) {
        $date = DateTime::createFromFormat("Y-m-d H:i:s", $request['date']); // TODO: check it

        $event->name = $request['name'];
        $event->description = $request['description'];
        $event->date = $date;
        $event->type_id = $request['type_id'];
        $event->hour_of_day = $date->format('H');

        switch ($event->type_id){
            case EventType::where('key', 'every_day')->first()->id:
                $event->day_of_week = $event->day_of_month = $event->month_of_year = null;
                break;
            case EventType::where('key', 'every_week')->first()->id:
                $event->day_of_week = $date->format('w');
                $event->day_of_month = $event->month_of_year = null;
                break;
            case EventType::where('key', 'every_month')->first()->id:
                $event->day_of_week = $date->format('w');
                $event->day_of_month = $date->format('d');
                $event->month_of_year = null;
                break;
            case EventType::where('key', 'every_year')->first()->id:
                $event->day_of_month = $date->format('d');
                $event->month_of_year = $date->format('m');
                $event->day_of_week = null;
                break;
        }
    }
}
