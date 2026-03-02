<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Add foreign key constraints to the products table
     * for brand_id and category_id referential integrity.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // Fix column type first (integer -> unsignedBigInteger for FK compatibility)
                $table->unsignedBigInteger('brand_id')->nullable()->change();
                $table->unsignedBigInteger('category_id')->nullable()->change();

                // Add foreign key constraints
                $table->foreign('brand_id')
                    ->references('id')
                    ->on('brands')
                    ->nullOnDelete();

                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['brand_id']);
                $table->dropForeign(['category_id']);
            });
        }
    }
};
