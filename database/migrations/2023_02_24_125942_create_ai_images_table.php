<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_images', function (Blueprint $table) {
            $table->increments('id')->uniqid();
            $table->string('generate_id');
            $table->string('generate_by');
            $table->longText('name');
            $table->string('type');
            $table->longText('prompt');
            $table->bigInteger('n');
            $table->longText('size');
            $table->longText('format');
            $table->longText('result');
            $table->boolean('bookmark')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_images');
    }
}
