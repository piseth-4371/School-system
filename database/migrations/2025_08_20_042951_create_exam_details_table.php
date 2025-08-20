<?php

// create_exam_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students');
            $table->decimal('score', 5, 2)->nullable();
            $table->string('grade', 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['exam_id', 'student_id'], 'exam_student_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_details');
    }
};