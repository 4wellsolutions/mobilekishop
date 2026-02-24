<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('error_logs', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('message');
            $table->string('user_agent')->nullable()->after('ip_address');
            $table->string('referer')->nullable()->after('user_agent');
            $table->unsignedInteger('hit_count')->default(1)->after('referer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('error_logs', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent', 'referer', 'hit_count']);
        });
    }
}
