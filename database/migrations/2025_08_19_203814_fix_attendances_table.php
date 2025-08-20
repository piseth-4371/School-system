<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Remove the incorrect status column if it exists
            if (Schema::hasColumn('attendances', 'status')) {
                $table->dropColumn('status');
            }
            
            // Only add the class_year_id column if it does not exist
            if (!Schema::hasColumn('attendances', 'class_year_id')) {
                $table->foreignId('class_year_id')->after('id')->constrained()->onDelete('cascade');
            }

            // Only add teacher_id column if it doesn't already exist
            if (!Schema::hasColumn('attendances', 'teacher_id')) {
                $table->foreignId('teacher_id')->after('class_year_id')->constrained()->onDelete('cascade');
            }

            // Only add the date column if it doesn't already exist
            if (!Schema::hasColumn('attendances', 'date')) {
                $table->date('date')->after('teacher_id');
            }
        });
    }


    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['class_year_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['class_year_id', 'teacher_id', 'date']);
        });
    }
};