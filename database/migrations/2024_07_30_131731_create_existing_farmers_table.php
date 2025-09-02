<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExistingFarmersTable extends Migration
{
    public function up()
    {
        Schema::create('existing_farmers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middle_initial')->nullable();
            $table->integer('age');
            $table->date('birthday');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('existing_farmers');
    }
}
