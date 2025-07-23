<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionReturnDateBorrowNumberToBorrowRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->string('borrow_number')->nullable(); // Add nullable for now, no unique constraint yet
            $table->string('description')->nullable();
            $table->date('return_date')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->dropColumn(['borrow_number', 'description', 'return_date']);
        });
    }
}    