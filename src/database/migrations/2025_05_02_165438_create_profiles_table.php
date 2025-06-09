<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FOREIGN KEY (users.id)
            $table->string('profile_image')->nullable(); // 画像パス
            $table->string('zip_code', 8)->nullable(); // 郵便番号
            $table->string('address')->nullable(); // 住所
            $table->string('building')->nullable(); // 建物名
            $table->string('phone_number', 20)->nullable(); //電話番号
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
