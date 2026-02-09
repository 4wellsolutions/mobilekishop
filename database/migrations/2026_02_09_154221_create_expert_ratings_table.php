<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expert_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade')->unique();
            $table->decimal('design', 3, 1)->default(0);
            $table->decimal('display', 3, 1)->default(0);
            $table->decimal('performance', 3, 1)->default(0);
            $table->decimal('camera', 3, 1)->default(0);
            $table->decimal('battery', 3, 1)->default(0);
            $table->decimal('value_for_money', 3, 1)->default(0);
            $table->decimal('overall', 3, 1)->default(0);
            $table->text('verdict')->nullable();
            $table->string('rated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_ratings');
    }
};
