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
        Schema::create('authentication_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('authenticatable_id');
            $table->string('authenticatable_type');
            $table->boolean('login_successful')->default(false);
            $table->boolean('cleared_by_user')->default(false);
            $table->boolean('is_trusted')->default(false);
            $table->boolean('is_suspicious')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_id')->nullable();
            $table->string('device_name')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->string('location')->nullable();
            $table->string('suspicious_reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->index(['authenticatable_type', 'authenticatable_id'], 'auth_log_authenticatable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication_log');
    }
};