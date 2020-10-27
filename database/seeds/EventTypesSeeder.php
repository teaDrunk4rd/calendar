<?php

use App\EventType;
use Illuminate\Database\Seeder;

class EventTypesSeeder extends Seeder
{
    public function run()
    {
        EventType::create([
            'name' => 'Каждый день',
            'key' => 'every_day'
        ]);
        EventType::create([
            'name' => 'Каждую неделю',
            'key' => 'every_week'
        ]);
        EventType::create([
            'name' => 'Каждый месяц',
            'key' => 'every_month'
        ]);
        EventType::create([
            'name' => 'Каждый год',
            'key' => 'every_year'
        ]);
    }
}
