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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('org_name')->nullable();
            $table->enum('org_type', ['learning_center', 'school', 'kindergarden'])->nullable();
            $table->unsignedBigInteger('region_id')->index();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('adress')->nullable();
            $table->string('org_contact')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['region_id', 'org_type']);
            $table->index(['is_verified', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
