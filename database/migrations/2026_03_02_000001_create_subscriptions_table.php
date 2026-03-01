<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('plan_id')
                ->constrained('plans')
                ->restrictOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('is_active')->default(true);
            $table->dateTime('canceled_at')->nullable();
            $table->foreignId('created_from_payment_id')
                ->nullable()
                ->constrained('payments')
                ->nullOnDelete();
            $table->timestamps();

            $table->index('user_id');
            $table->index('plan_id');
            $table->index('is_active');
            $table->index('end_at');
        });

        // Cross-DB strategy:
        // - PostgreSQL: enforce "one active subscription per user" via partial unique index.
        // - Other drivers (e.g. MySQL): enforced at application/service level inside transaction.
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement(
                'CREATE UNIQUE INDEX subscriptions_one_active_per_user
                ON subscriptions (user_id)
                WHERE is_active = true'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
