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
        if (! Schema::hasColumn('vacancies', 'seeker_type_id')) {
            return;
        }

        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropForeign(['seeker_type_id']);
            $table->dropColumn('seeker_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vacancies', 'seeker_type_id')) {
            return;
        }

        Schema::table('vacancies', function (Blueprint $table) {
            $table->unsignedBigInteger('seeker_type_id')->nullable()->after('employer_id');
            $table->foreign('seeker_type_id')->references('id')->on('seekers_types')->cascadeOnUpdate()->restrictOnDelete();
        });
    }
};
