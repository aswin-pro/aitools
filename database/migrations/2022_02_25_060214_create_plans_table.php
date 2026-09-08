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
            $table->string('plan_id')->unique();
            $table->string('name');
            $table->longText('description'); 
            $table->double('price', 15, 2)->default(0);
            $table->integer('validity');
            $table->json('content_templates');
            $table->bigInteger('ai_credits');
            $table->bigInteger('ai_image_credits');
            $table->boolean('speech_to_text');
            $table->boolean('text_to_speech');
            $table->boolean('code_generator');
            $table->boolean('personalized_chat');
            $table->boolean('document_analyzer');
            $table->boolean('site_analyzer');
            $table->boolean('is_recommended')->default(false);
            $table->boolean('customer_support')->default(false);
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
