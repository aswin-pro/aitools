<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id')->uniqid();
            $table->boolean('is_private')->default(false);
            $table->string('plan_id')->nullable();
            $table->string('name');
            $table->longText('description'); 
            $table->double('price', 15, 2);
            $table->integer('validity');
            $table->bigInteger('template_counts');
            $table->longText('templates');
            $table->bigInteger('max_words');
            $table->bigInteger('max_images');
            $table->boolean('additional_tools');
            $table->boolean('ai_speech_to_text');
            $table->boolean('ai_text_to_speech');
            $table->boolean('ai_code');
            $table->boolean('ai_chatgenius');
            $table->boolean('ai_docsassist');
            $table->boolean('ai_webchat');
            $table->boolean('recommended')->default(false);
            $table->boolean('support');
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
        Schema::dropIfExists('plans');
    }
}


    // public function up()
    // {
    //     Schema::create('plans', function (Blueprint $table) {
    //         $table->increments('id')->uniqid();
    //         $table->boolean('is_private')->default(false);
    //         $table->string('plan_id')->nullable();
    //         $table->string('name');
    //         $table->longText('description'); 
    //         $table->double('price', 15, 2);
    //         $table->integer('validity');
    //         $table->bigInteger('template_counts');
    //         $table->longText('templates');
    //         $table->bigInteger('max_words');
    //         $table->bigInteger('max_images');
    //         $table->boolean('additional_tools');
    //         $table->boolean('ai_speech_to_text');
    //         $table->boolean('ai_text_to_speech');
    //         $table->boolean('ai_code');
    //         $table->boolean('ai_chatgenius');
    //         $table->boolean('ai_docsassist');
    //         $table->boolean('ai_webchat');
    //         $table->boolean('recommended')->default(false);
    //         $table->boolean('support');
    //         $table->boolean('status')->default(true);
    //         $table->timestamp('created_at')->useCurrent();
    //         $table->timestamp('updated_at')->useCurrent();
    //     });
    // }