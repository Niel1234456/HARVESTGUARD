<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionAndRequestingNumberToSupplyRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->string('description')->nullable();  // Add description field
            $table->integer('requesting_number');       // Add requesting_number field
        });
    }

    public function down()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('requesting_number');
        });
    }
}
