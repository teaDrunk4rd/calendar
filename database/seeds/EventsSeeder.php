<?php

use App\Event;
use App\EventType;
use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    public function run()
    {
        Event::create([
            'name'=> '123',
            'description' => '123123123123',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 1,
            'type_id'=> EventType::where('key', 'every_day')->first()->id,
            'hour_of_day'=> 16,
            'day_of_week'=> null,
            'day_of_month'=> null,
            'month_of_year'=> null
        ]);
        Event::create([
            'name'=> 'Вебинар по саморазвитию',
            'description' => 'Вебинары по саморазвитию и личностному росту. Участие в онлайн-семинарах по саморазвитию поможет вывести жизнь на совершенно новый ...',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 1,
            'type_id'=> EventType::where('key', 'every_week')->first()->id,
            'hour_of_day'=> 12,
            'day_of_week'=> 1,
            'day_of_month'=> null,
            'month_of_year'=> null
        ]);
        Event::create([
            'name'=> 'Ежемесячный вебинар по ремеслу войны',
            'description' => 'Привет всем, кто это читает',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 2,
            'type_id'=> EventType::where('key', 'every_month')->first()->id,
            'hour_of_day'=> 12,
            'day_of_week'=> 1,
            'day_of_month'=> 25,
            'month_of_year'=> null
        ]);
        Event::create([
            'name'=> 'Пятничный кутёж',
            'description' => '',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 1,
            'type_id'=> EventType::where('key', 'every_week')->first()->id,
            'hour_of_day'=> 20,
            'day_of_week'=> 5,
            'day_of_month'=> null,
            'month_of_year'=> null
        ]);
        Event::create([
            'name'=> 'Ежемесячный корпоратив в компании Mint Rocket',
            'description' => 'Классы 228 на сайте',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 4,
            'type_id'=> EventType::where('key', 'every_month')->first()->id,
            'hour_of_day'=> 19,
            'day_of_week'=> null,
            'day_of_month'=> 24,
            'month_of_year'=> null
        ]);
        Event::create([
            'name'=> 'Эфир Владимира Соловьева',
            'description' => 'Как любить родину',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 3,
            'type_id'=> EventType::where('key', 'every_week')->first()->id,
            'hour_of_day'=> 16,
            'day_of_week'=> 0,
            'day_of_month'=> null,
            'month_of_year'=> null
        ]);
        Event::create([
            'name'=> 'Воскресенские беседы с батюшкой',
            'description' => 'Как любить родину',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 3,
            'type_id'=> EventType::where('key', 'every_week')->first()->id,
            'hour_of_day'=> 17,
            'day_of_week'=> 0,
            'day_of_month'=> null,
            'month_of_year'=> null
        ]);
        Event::create([
            'name'=> 'Пришествие Макаронного Монстра',
            'description' => 'Надевайте дуршлаги',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 4,
            'type_id'=> EventType::where('key', 'every_year')->first()->id,
            'hour_of_day'=> 16,
            'day_of_week'=> null,
            'day_of_month'=> 29,
            'month_of_year'=> 10
        ]);
        Event::create([
            'name'=> 'День холостяка',
            'description' => 'Вечеринка у Децла дома',
            'date' => date('Y-m-d H:i:s'),
            'creator_id'=> 4,
            'type_id'=> EventType::where('key', 'every_year')->first()->id,
            'hour_of_day'=> 16,
            'day_of_week'=> null,
            'day_of_month'=> 11,
            'month_of_year'=> 11
        ]);
    }
}
