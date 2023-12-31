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
        Schema::dropIfExists('notifications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->bigInteger('comment_id')->nullable();
            $table->bigInteger('follower_id')->nullable();
            $table->bigInteger('user_id');
            $table->boolean('readed')->default(0);
            $table->tinyInteger('type_notification_id');
            $table->timestamps();
        });
    }
};
