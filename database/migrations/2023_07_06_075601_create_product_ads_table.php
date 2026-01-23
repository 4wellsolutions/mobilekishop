<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ads', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->double("price");
            $table->string("external_id");
            $table->string("url");
            $table->dateTime("starting_time");
            $table->dateTime("expiry_time");
            $table->integer("user_id");
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
        Schema::dropIfExists('product_ads');
    }
}
