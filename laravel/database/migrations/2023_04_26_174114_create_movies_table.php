<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('description',255);
            $table->string('gender',255);
            $table->unsignedBigInteger('cover_id');
            $table->foreign('cover_id')->references('id')->on('files');

            $table->unsignedBigInteger('intro_id');
            $table->foreign('intro_id')->references('id')->on('files');

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
        Schema::dropIfExists('movies');
    }
};
