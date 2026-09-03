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
    $table->uuid('plan_id')->unique();
    $table->boolean('is_private')->default(false);
    $table->string('name');
    $table->longText('description');
    $table->double('price', 15, 2);
    $table->integer('validity');

    $table->json('content_templates');

    $table->bigInteger('ai_credits');
    $table->bigInteger('ai_image_credits');

    $table->boolean('speech_to_text')->default(true);
    $table->boolean('text_to_speech');
    $table->boolean('code_generator')->default(true);
    $table->boolean('personalized_chat')->default(false);
    $table->boolean('document_analyzer')->default(false);
    $table->boolean('site_analyzer')->default(false);

    $table->boolean('is_recommended')->default(false);
    $table->boolean('customer_support');

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