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
        Schema::create('seekers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('region_id')->index();
            $table->string('experience')->nullable();
            $table->unsignedBigInteger('salary_min')->nullable();
            $table->enum('work_format', ['online', 'offline', 'gibrid'])->nullable();
            $table->text('about_me')->nullable();
            $table->string('cv_file_path')->nullable();
            $table->timestamps();

            $table->index(['region_id', 'work_format']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seekers');
    }
};
