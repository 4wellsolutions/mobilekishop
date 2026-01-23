<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW product_attributes_view AS
            SELECT 
                products.id AS product_id, 
                products.name AS product_name, 
                attributes.label AS attribute_label, 
                product_attributes.value AS attribute_value
            FROM products
            JOIN product_attributes ON products.id = product_attributes.product_id
            JOIN attributes ON attributes.id = product_attributes.attribute_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes_view');
    }
}
