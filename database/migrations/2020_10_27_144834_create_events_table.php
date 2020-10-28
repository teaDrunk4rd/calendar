<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 100);

            $table->text('description')->default('');

            $table->timestamp('date');

            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');

            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('event_types');

            $table->smallInteger('hour_of_day')->nullable();

            $table->smallInteger('day_of_week')->nullable();

            $table->smallInteger('day_of_month')->nullable();

            $table->smallInteger('month_of_year')->nullable();
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['type_id']);
        });
        Schema::dropIfExists('events');
    }
}
