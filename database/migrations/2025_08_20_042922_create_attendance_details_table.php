<?php

// create_attendance_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students');
            $table->enum('session1', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->enum('session2', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->enum('session3', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->enum('session4', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['attendance_id', 'student_id'], 'attendance_student_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_details');
    }
};
