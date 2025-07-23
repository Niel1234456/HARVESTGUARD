<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnFieldsToBorrowRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->string('is_returned')->default('No'); // Default value set to "No"
            $table->timestamp('returned_at')->nullable(); // To store the date and time of return
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropColumn('is_returned');
            $table->dropColumn('returned_at');
        });
    }
}
