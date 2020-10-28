<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventType;
use DateTime;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index($date) {
        $month = DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $date))->format('m');
        return Event::where('type_id', '!=', EventType::where('key', 'every_year')->first()->id)
            ->orWhere('month_of_year', $month)
            ->orderBy('hour_of_day')
            ->orderBy('type_id', 'desc')
            ->get();
    }

    public function create(Request $request) {
        $this->validate($request, Event::$validation);

        $event = new Event();
        $event = $this->editEvent($event, $request);
        $event->save();

        return response()->json($event, 201);
    }

    public function read($id) {
        return Event::with('eventType')->find($id);
    }

    public function update(Request $request) {
        $this->validate($request, Event::$validation);

        $event = Event::find($request['id']);

        if ($request['creator_id'] != $event->creator_id)
            return response()->json(['message' => 'Вы не можете изменять чужое событие']);

        $event = $this->editEvent($event, $request);
        $event->save();

        return response()->json($event, 200);
    }

    private function editEvent(Event $event, Request $request) { // Учесть даты типа 31 для месяцев с 28-29-30 днями?
        $date = DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $request['date']));

        $event->name = $request['name'];
        $event->description = $request['description'] != null ? $request['description'] : '';
        $event->date = $date;
        $event->type_id = $request['type_id'];
        $event->creator_id = $request['creator_id'];
        $event->closed_at = $request['closed_at'] != null && $request['closed_at'] != '' ? DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $request['closed_at'])) : null;
        $event->hour_of_day = $date->format('H');

        switch ($event->type_id){
            case EventType::where('key', 'every_day')->first()->id:
                $event->day_of_week = $event->day_of_month = $event->month_of_year = null;
                break;
            case EventType::where('key', 'every_week')->first()->id:
                $event->day_of_week = $date->format('N');
                $event->day_of_month = $event->month_of_year = null;
                break;
            case EventType::where('key', 'every_month')->first()->id:
                $event->day_of_month = $date->format('d');
                $event->day_of_week = $event->month_of_year = null;
                break;
            case EventType::where('key', 'every_year')->first()->id:
                $event->day_of_month = $date->format('d');
                $event->month_of_year = $date->format('m');
                $event->day_of_week = null;
                break;
        }

        return $event;
    }
}
