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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('plan_id')->index();
            $table->unsignedBigInteger('vacancy_id')->nullable()->index();
            // String is used for cross-DB compatibility; allowed values are enforced in enum/request.
            $table->string('provider', 20)->index();
            $table->unsignedInteger('amount');
            // String is used for cross-DB compatibility; allowed values are enforced in enum/request.
            $table->string('status', 20)->default('pending')->index();
            $table->string('provider_invoice_id', 120)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_invoice_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreign('vacancy_id')
                ->references('id')
                ->on('vacancies')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
