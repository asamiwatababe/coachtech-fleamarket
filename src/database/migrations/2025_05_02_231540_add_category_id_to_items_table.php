<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('category_id')
            ->nullable()
            ->after('user_id')
            ->constrained()
            ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
}
