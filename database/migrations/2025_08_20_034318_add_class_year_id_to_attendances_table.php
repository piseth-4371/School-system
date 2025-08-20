<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if the column exists before attempting to add it
        if (!Schema::hasColumn('attendances', 'class_year_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreignId('class_year_id')->nullable()->constrained('class_years')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['class_year_id']);
            $table->dropColumn('class_year_id');
        });
    }
};
