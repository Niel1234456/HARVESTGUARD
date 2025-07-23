<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfficeIdPictureToAdminsTable extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            // Add a new column for the office ID picture
            $table->string('office_picture')->nullable(); // Use string type for file path or URL
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            // Drop the column if rolling back the migration
            $table->dropColumn('office_picture');
        });
    }
}
