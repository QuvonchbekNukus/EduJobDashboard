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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id')->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('employer_id')->index();
            $table->unsignedBigInteger('seeker_type_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('title');
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->unsignedBigInteger('salary_from')->nullable();
            $table->unsignedBigInteger('salary_to')->nullable();
            $table->string('schedule')->nullable();
            $table->enum('work_format', ['online', 'offline', 'gibrid']);
            $table->text('requirements')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_username')->nullable();
            $table->enum('status', ['pending', 'published', 'rejected', 'archived'])->nullable()->index();
            $table->date('published_at')->nullable()->index();
            $table->text('benefits')->nullable();
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('regions')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('employer_id')->references('id')->on('employers')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('seeker_type_id')->references('id')->on('seekers_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
