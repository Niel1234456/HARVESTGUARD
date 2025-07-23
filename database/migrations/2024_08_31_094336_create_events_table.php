<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable(); // Allows for a longer description
            $table->date('start');
            $table->date('end');
            $table->time('start_time')->nullable(); // Stores time part of the start datetime
            $table->time('end_time')->nullable(); // Stores time part of the end datetime
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
