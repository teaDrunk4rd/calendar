<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventType;
use App\Http\Requests\EventFormRequest;
use DateTime;

class EventController extends Controller
{
    public function index($date) {
        $month = DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $date))->format('m');
        return Event::where('type_id', '!=', EventType::where('key', 'every_year')->first()->id)
            ->orWhere('month_of_year', $month)
            ->orderBy('hour_of_day')
            ->orderBy('type_id', 'desc')
            ->get();
    }

    public function store(EventFormRequest $request) {
        $validatedRequest = $this->editRequest($request->validated());

        $event = Event::create($validatedRequest);

        return response()->json($event, 201);
    }

    public function show($id) {
        return Event::with('eventType')->find($id);
    }

    public function update(EventFormRequest $request) {
        $validatedRequest = $this->editRequest($request->validated());

        $event = Event::find($validatedRequest['id'])->update($validatedRequest);

        return response()->json($event, 200);
    }

    private function editRequest(array $request) { // Учесть даты типа 31 для месяцев с 28-29-30 днями?
        $date = DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $request['date']));

        $request['description'] = $request['description'] != null ? $request['description'] : '';
        $request['date'] = $date;
        $request['closed_at'] = $request['closed_at'] != null && $request['closed_at'] != '' ?
            DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $request['closed_at'])) : null;
        $request['hour_of_day'] = $date->format('H');

        switch ($request['type_id']){
            case EventType::where('key', 'every_day')->first()->id:
                $request['day_of_week'] = $request['day_of_month'] = $request['month_of_year'] = null;
                break;
            case EventType::where('key', 'every_week')->first()->id:
                $request['day_of_week'] = $date->format('N') != 7 ? $date->format('N') : 0;
                $request['day_of_month'] = $request['month_of_year'] = null;
                break;
            case EventType::where('key', 'every_month')->first()->id:
                $request['day_of_month'] = $date->format('d');
                $request['day_of_week'] = $request['month_of_year'] = null;
                break;
            case EventType::where('key', 'every_year')->first()->id:
                $request['day_of_month'] = $date->format('d');
                $request['month_of_year'] = $date->format('m');
                $request['day_of_week'] = null;
                break;
        }

        return $request;
    }
}
