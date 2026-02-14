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
        Schema::table('seekers', function (Blueprint $table) {
            $table->unsignedBigInteger('seekertype_id')->nullable()->after('region_id');
            $table->unsignedBigInteger('subject_id')->nullable()->after('seekertype_id');

            $table->index('seekertype_id');
            $table->index('subject_id');
            $table->index(['seekertype_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seekers', function (Blueprint $table) {
            $table->dropIndex(['seekertype_id', 'subject_id']);
            $table->dropIndex(['seekertype_id']);
            $table->dropIndex(['subject_id']);
            $table->dropColumn(['seekertype_id', 'subject_id']);
        });
    }
};
