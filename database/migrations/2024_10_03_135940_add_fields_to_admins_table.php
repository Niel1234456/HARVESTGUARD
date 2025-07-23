<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('name')->unique()->change(); // Make 'name' unique
            $table->string('contact_number')->nullable(); // Add contact number
            $table->string('address')->nullable(); // Add address
            $table->integer('age')->nullable(); // Add age
            $table->date('birthday')->nullable(); // Add birthday
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
        });
    }
};
