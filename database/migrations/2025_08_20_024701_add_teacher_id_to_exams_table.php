<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            // Only add the foreign key if it doesn't exist
            if (!Schema::hasColumn('exams', 'teacher_id')) {
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            // Drop the foreign key if exists
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};

