<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("slug");
            $table->string("title");
            $table->string("description");
            $table->string("canonical");
            $table->integer("brand_id");
            $table->integer("category_id");
            $table->string("thumbnail")->nullable();
            $table->text("body")->nullable();
            $table->string("price_in_pkr");
            $table->string("price_in_dollar");
            $table->integer("views")->default(0);
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
        Schema::dropIfExists('products');
    }
}
