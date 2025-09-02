<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('farmer_id')->nullable()->after('id');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
            $table->dropColumn('farmer_id');
        });
    }
};
