<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNameFromAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('name');
        });
    }
}
