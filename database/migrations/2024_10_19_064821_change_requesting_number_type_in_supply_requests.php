<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRequestingNumberTypeInSupplyRequests extends Migration
{
    public function up()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->string('requesting_number')->change(); // Change to string type
        });
    }

    public function down()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->integer('requesting_number')->change(); // Change back to integer if needed
        });
    }
}
