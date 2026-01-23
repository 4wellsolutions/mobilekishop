<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_numbers', function (Blueprint $table) {
            $table->id();
            $table->integer("ad_id");
            $table->integer("user_id");
            $table->string("browser")->nullable();
            $table->string("browser_version")->nullable();
            $table->string("platform")->nullable();
            $table->string("device")->nullable();
            $table->string("device_type")->nullable();
            $table->string("ip")->nullable();
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
        Schema::dropIfExists('show_numbers');
    }
}
