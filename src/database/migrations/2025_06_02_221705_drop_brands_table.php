<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBrandsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('brands');
    }

    public function down()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
}
