<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventType;
use App\Http\Requests\EventFormRequest;
use DateTime;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private $event;
    private $eventType;
    public function __construct(Event $event, EventType $eventType)
    {
        $this->event = $event;
        $this->eventType = $eventType;
    }

    public function index($date)
    {
        $month = DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $date))->format('m');
        return $this->event::where('type_id', '!=', $this->eventType::where('key', 'every_year')->first()->id)
            ->orWhere('month_of_year', $month)
            ->orderBy('hour_of_day')
            ->orderBy('type_id', 'desc')
            ->get();
    }

    public function store(EventFormRequest $request)
    {
        $validatedRequest = $this->editRequest($request->validated());
        $validatedRequest['creator_id'] = Auth::user()->id;
        $event = $this->event::create($validatedRequest);
        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        return $event;
    }

    public function update(EventFormRequest $request)
    {
        $validatedRequest = $this->editRequest($request->validated());
        $event = $this->event::find($validatedRequest['id']);
        $this->authorize('update', $event);
        $event->update($validatedRequest);
        return response()->json($event, 200);
    }

    private function editRequest(array $request)
    { // Учесть даты типа 31 для месяцев с 28-29-30 днями?
        $date = DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $request['date']));

        $request['description'] = $request['description'] != null ? $request['description'] : '';
        $request['date'] = $date;
        $request['closed_at'] = $request['closed_at'] != null && $request['closed_at'] != '' ?
            DateTime::createFromFormat("Y-m-d H:i:s", date('Y-m-d H:i:s', $request['closed_at'])) : null;
        $request['hour_of_day'] = $date->format('H');

        switch ($request['type_id']) {
            case $this->eventType::where('key', 'every_day')->first()->id:
                $request['day_of_week'] = $request['day_of_month'] = $request['month_of_year'] = null;
                break;
            case $this->eventType::where('key', 'every_week')->first()->id:
                $request['day_of_week'] = $date->format('N') != 7 ? $date->format('N') : 0;
                $request['day_of_month'] = $request['month_of_year'] = null;
                break;
            case $this->eventType::where('key', 'every_month')->first()->id:
                $request['day_of_month'] = $date->format('d');
                $request['day_of_week'] = $request['month_of_year'] = null;
                break;
            case $this->eventType::where('key', 'every_year')->first()->id:
                $request['day_of_month'] = $date->format('d');
                $request['month_of_year'] = $date->format('m');
                $request['day_of_week'] = null;
                break;
        }

        return $request;
    }
}
