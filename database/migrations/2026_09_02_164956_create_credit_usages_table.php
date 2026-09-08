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
        Schema::create('credit_usages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->bigInteger('plan_ai_credits')->default(0);
            $table->bigInteger('plan_ai_image_credits')->default(0);
            $table->bigInteger('purchased_ai_credits')->default(0);
            $table->bigInteger('purchased_ai_image_credits')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_usages');
    }
};
