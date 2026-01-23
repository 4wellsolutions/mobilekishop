<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("referral")->nullable();
            $table->string("placement")->nullable();
            $table->string("device")->nullable();
            $table->string("ip_address")->nullable();
            $table->string("platform")->nullable();
            $table->string("browser")->nullable();
            $table->string("is_mobile")->nullable();
            $table->string("product_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clicks');
    }
}
