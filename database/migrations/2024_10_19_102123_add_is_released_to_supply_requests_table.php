<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->string('is_released')->default('No'); // Default to "No" (not released)
        });
    }
    
    public function down()
    {
        Schema::table('supply_requests', function (Blueprint $table) {
            $table->dropColumn('is_released');
        });
    }
};
