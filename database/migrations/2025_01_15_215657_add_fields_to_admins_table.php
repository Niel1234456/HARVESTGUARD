<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('first_name')->after('name');
            $table->string('last_name')->after('first_name');
            $table->string('middle_initial', 1)->nullable()->after('last_name');
            $table->string('position')->nullable()->after('middle_initial');
            $table->string('id_type')->nullable()->after('position');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('id_type');
            $table->string('postal_code')->nullable()->after('gender');
            $table->string('city')->nullable()->after('postal_code');
            $table->string('province')->nullable()->after('city');
            $table->string('country')->nullable()->after('province');
        });
    }


    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'middle_initial',
                'position',
                'id_type',
                'gender',
                'postal_code',
                'city',
                'province',
                'country',
            ]);
        });
    }
};
