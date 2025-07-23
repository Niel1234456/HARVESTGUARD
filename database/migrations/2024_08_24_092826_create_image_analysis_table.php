<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_analysis', function (Blueprint $table) {
            $table->id();
            $table->string('disease_name');
            $table->integer('detection_count')->default(1);
            $table->decimal('average_confidence', 5, 2);
            $table->date('date_analyzed');
            $table->integer('total_analyses')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_analysis');
    }
}
