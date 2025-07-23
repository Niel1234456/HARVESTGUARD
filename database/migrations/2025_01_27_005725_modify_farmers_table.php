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
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('email'); // Remove the email field
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_initial', 1)->nullable(); // optional middle initial
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->string('email')->unique(); // Add email back in case of rollback
            $table->dropColumn(['first_name', 'last_name', 'middle_initial']);
        });
    }
};
