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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')
                ->constrained('vacancies')
                ->cascadeOnDelete();
            $table->foreignId('seeker_id')
                ->constrained('seekers')
                ->cascadeOnDelete();
            $table->enum('status', ['sent', 'viewed', 'accepted', 'rejected'])->default('sent');
            $table->timestamps();

            $table->index('vacancy_id');
            $table->index('seeker_id');
            $table->unique(['vacancy_id', 'seeker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
