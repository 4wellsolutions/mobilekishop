<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Add missing composite indexes for maximum query performance.
     * These cover the most frequent queries: price filtering, attribute filtering,
     * slug lookups, and country resolution.
     */
    public function up(): void
    {
        // product_variants: Used by every price query and filter
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->index(['product_id', 'country_id'], 'idx_pv_product_country');
                $table->index(['country_id', 'price'], 'idx_pv_country_price');
            });
        }

        // product_attributes: Used by every EAV filter query (RAM, ROM, camera, etc.)
        if (Schema::hasTable('product_attributes')) {
            Schema::table('product_attributes', function (Blueprint $table) {
                $table->index(['attribute_id', 'value'], 'idx_pa_attr_value');
                $table->index(['product_id', 'attribute_id'], 'idx_pa_product_attr');
            });
        }

        // brands: slug lookup for route model binding
        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->index('slug', 'idx_brands_slug');
            });
        }

        // categories: slug lookup for route model binding
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->index('slug', 'idx_categories_slug');
            });
        }

        // countries: code lookup and active menu filtering
        if (Schema::hasTable('countries')) {
            Schema::table('countries', function (Blueprint $table) {
                $table->index('country_code', 'idx_countries_code');
                $table->index(['is_active', 'is_menu'], 'idx_countries_active_menu');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropIndex('idx_pv_product_country');
                $table->dropIndex('idx_pv_country_price');
            });
        }

        if (Schema::hasTable('product_attributes')) {
            Schema::table('product_attributes', function (Blueprint $table) {
                $table->dropIndex('idx_pa_attr_value');
                $table->dropIndex('idx_pa_product_attr');
            });
        }

        if (Schema::hasTable('brands')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropIndex('idx_brands_slug');
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex('idx_categories_slug');
            });
        }

        if (Schema::hasTable('countries')) {
            Schema::table('countries', function (Blueprint $table) {
                $table->dropIndex('idx_countries_code');
                $table->dropIndex('idx_countries_active_menu');
            });
        }
    }
};
