<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_assistants', function (Blueprint $table) {
            $table->increments('id')->uniqid();
            $table->string('chat_assistant_id');
            $table->string('chat_assistant_image')->nullable();
            $table->string('chat_assistant_name');
            $table->string('chat_assistant_expert');
            $table->text('chat_assistant_description');
            $table->text('chat_assistant_message');
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_assistants');
    }
};
