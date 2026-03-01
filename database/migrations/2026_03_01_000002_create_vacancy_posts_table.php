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
        Schema::create('vacancy_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')
                ->constrained('vacancies')
                ->cascadeOnDelete();
            $table->bigInteger('tg_chat_id');
            $table->unsignedBigInteger('tg_message_id');
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();

            $table->unique('vacancy_id');
            $table->index('tg_chat_id');
            $table->unique(['tg_chat_id', 'tg_message_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_posts');
    }
};
