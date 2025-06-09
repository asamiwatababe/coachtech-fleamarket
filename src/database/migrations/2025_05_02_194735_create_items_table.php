<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('price');
            $table->text('description');
            $table->string('image_url');
            $table->string('condition', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
