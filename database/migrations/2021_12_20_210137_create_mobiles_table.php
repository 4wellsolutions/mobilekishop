<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url')->nullable();
            $table->unsignedInteger('brand_id')->nullable();
            $table->string('name')->nullable();
            $table->string('model')->nullable();
            $table->integer('price_in_pkr')->nullable();
            $table->string('price_in_dollar')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('description');
            $table->text('keywords');
            $table->string('os')->nullable();
            $table->string('ui')->nullable();
            $table->text('dimensions')->nullable();
            $table->text('standby')->nullable();
            $table->text('talktime')->nullable();
            $table->text('musicplay')->nullable();
            $table->text('weight')->nullable();
            $table->text('sim')->nullable();
            $table->text('colors')->nullable();
            $table->text('2g_band')->nullable();
            $table->text('3g_band')->nullable();
            $table->text('4g_band')->nullable();
            $table->text('5g_band')->nullable();
            $table->text('cpu')->nullable();
            $table->text('chipset')->nullable();
            $table->text('gpu')->nullable();
            $table->text('technology')->nullable();
            $table->text('size')->nullable();
            $table->text('resolution')->nullable();
            $table->text('protection')->nullable();
            $table->text('extra_features')->nullable();
            $table->text('built_in')->nullable();
            $table->text('card')->nullable();
            $table->text('main')->nullable();
            $table->text('features')->nullable();
            $table->text('front')->nullable();
            $table->text('wlan')->nullable();
            $table->text('bluetooth')->nullable();
            $table->text('gps')->nullable();
            $table->text('radio')->nullable();
            $table->text('usb')->nullable();
            $table->text('nfc')->nullable();
            $table->text('infrared')->nullable();
            $table->text('data')->nullable();
            $table->text('sensors')->nullable();
            $table->text('audio')->nullable();
            $table->text('browser')->nullable();
            $table->text('messaging')->nullable();
            $table->text('whats_new')->nullable();
            $table->text('contacts')->nullable();
            $table->text('games')->nullable();
            $table->text('torch')->nullable();
            $table->text('extra')->nullable();
            $table->text('capacity')->nullable();
            $table->text('body')->nullable();
            $table->text('battery')->nullable();
            $table->text('screen_resolution')->nullable();
            $table->integer('views')->default(1);
            $table->integer('quantity')->default(1);
            $table->integer('images_count');
            $table->integer('pixels');
            $table->integer('no_of_cameras');
            $table->float('screen_size');
            $table->integer('ram_in_gb');
            $table->integer('rom_in_gb');
            $table->integer('no_of_sims');
            $table->string('operating_system');
            $table->timestamp('release_date')->nullable();
            $table->boolean('is_featured')->nullable();
            $table->boolean('is_hot')->nullable();
            $table->boolean('is_new')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobiles');
    }
}
