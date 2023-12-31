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
        Schema::dropIfExists('type_notifications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('type_notifications', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }
};
