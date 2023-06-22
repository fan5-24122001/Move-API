<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('view_videos', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->change();
            $table->tinyInteger('age')->nullable()->change();
            $table->tinyInteger('gender')->nullable()->change();
            $table->dropColumn('address');
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->bigInteger('time')->default('30');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('view_videos', function (Blueprint $table) {
            $table->bigInteger('user_id')->change();
            $table->tinyInteger('age')->change();
            $table->string('gender')->change();
            $table->string('address');
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
            $table->dropColumn('time');
        });
    }
};
