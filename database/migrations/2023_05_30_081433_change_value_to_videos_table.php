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
        Schema::table('videos', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->string('thumbnail')->nullable()->change();
            $table->bigInteger('user_id')->nullable()->change();
            $table->tinyInteger('category_id')->nullable()->change();
            $table->tinyInteger('level')->nullable()->change();
            $table->tinyInteger('duration')->nullable()->change();
            $table->string('file_status')->default('uploading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->string('thumbnail')->nullable(false)->change();
            $table->bigInteger('user_id')->nullable(false)->change();
            $table->tinyInteger('category_id')->nullable(false)->change();
            $table->tinyInteger('level')->nullable(false)->change();
            $table->tinyInteger('duration')->nullable(false)->change();
            $table->dropColumn('file_status')->nullable(false);
        });
    }
};
