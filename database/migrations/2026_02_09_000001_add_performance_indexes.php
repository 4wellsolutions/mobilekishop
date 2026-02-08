<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Add performance indexes to key tables.
     */
    public function up(): void
    {
        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            $table->index('slug', 'idx_products_slug');
            $table->index('brand_id', 'idx_products_brand_id');
            $table->index('category_id', 'idx_products_category_id');
            $table->index('release_date', 'idx_products_release_date');
        });

        // Redirections table index for faster lookup
        if (Schema::hasTable('redirections')) {
            Schema::table('redirections', function (Blueprint $table) {
                $table->index('from_url', 'idx_redirections_from_url');
            });
        }

        // Reviews table index
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'is_active'], 'idx_reviews_product_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_slug');
            $table->dropIndex('idx_products_brand_id');
            $table->dropIndex('idx_products_category_id');
            $table->dropIndex('idx_products_release_date');
        });

        if (Schema::hasTable('redirections')) {
            Schema::table('redirections', function (Blueprint $table) {
                $table->dropIndex('idx_redirections_from_url');
            });
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_reviews_product_active');
        });
    }
};
