<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->string("price");
            $table->string("slug");
            $table->string("condition");
            $table->string("external_id");
            $table->string("olx_id")->nullable();
            $table->string("province_id");
            $table->string("city_id");
            $table->string("area_id")->nullable();
            $table->string("latitude");
            $table->string("longitude");
            $table->string("address")->nullable();
            $table->string("name");
            $table->string("phone_number");
            $table->integer("brand_id");
            $table->integer("mobile_id")->nullable();
            $table->integer("views")->default(1);
            $table->integer("user_id");
            $table->boolean("category_id");
            $table->integer("validity");
            $table->integer("admin_id")->nullable();
            $table->integer("checked_by")->nullable();
            $table->boolean("is_whatsapp")->default(0);
            $table->boolean("is_featured")->default(0);
            $table->boolean("is_active")->default(0);
            $table->boolean("status_id")->default(1);
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
        Schema::dropIfExists('ads');
    }
}
