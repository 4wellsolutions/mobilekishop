<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string("brand_id")->nullable();
            $table->string("price")->nullable();
            $table->string("ram")->nullable();
            $table->string("storage")->nullable();
            $table->string("screen_size")->nullable();
            $table->string("camera")->nullable();
            $table->integer("user_id")->nullable();
            $table->string("parameter")->nullable();
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
        Schema::dropIfExists('filters');
    }
}
