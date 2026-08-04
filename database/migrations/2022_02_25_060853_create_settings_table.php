<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('analytics_id')->nullable();
		    $table->longText('google_tag')->nullable();
            $table->longText('adsense_code')->nullable();
            $table->longText('site_name')->nullable();
            $table->longText('site_logo')->nullable();
            $table->longText('site_logo_light')->nullable();
            $table->longText('favicon')->nullable();
            $table->longText('tawk_chat_key')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('settings');
    }
}
