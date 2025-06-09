<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemIdAndNameToAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->after('user_id');
            $table->string('name')->after('item_id');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn(['item_id', 'name']);
        });
    }
}
