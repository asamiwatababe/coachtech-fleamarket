<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePostalCodeFromAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('postal_code');
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('postal_code')->nullable(); // 復元用
        });
    }
}
