<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandAndCategoryIdToItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // 商品名のあとにブランドを追加
            $table->string('brand')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('brand');
        });
    }
}
