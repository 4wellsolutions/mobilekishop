<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('site_settings')->insert([
            ['key' => 'head_code', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'body_start_code', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'body_end_code', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
