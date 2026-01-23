<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string("filter_type");
            $table->string("filter_network");
            $table->string("filter_data");
            $table->string("name")->unique();
            $table->string("slug")->unique();
            $table->string("meta_title")->unique();
            $table->string("meta_description")->unique();
            $table->string("price")->nullable();
            $table->string("onnet")->nullable();
            $table->string("offnet")->nullable();
            $table->string("sms")->nullable();
            $table->string("data")->nullable();
            $table->string("validity")->nullable();
            $table->text("description")->nullable();
            $table->text("method")->nullable();
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
        Schema::dropIfExists('packages');
    }
}
