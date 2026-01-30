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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->bigInteger('tg_chat_id')->unique();
            $table->unsignedBigInteger('region_id')->unique();
            $table->enum('type', ['CHANNEL', 'GROUP'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('region_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};