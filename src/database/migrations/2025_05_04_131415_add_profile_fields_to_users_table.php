<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('building')->nullable();
            $table->string('profile_image')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'postal_code', 'address', 'building', 'profile_image']);
        });
    }
}
