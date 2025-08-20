<?php

// create_exams_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('class_year_id')->constrained('class_years');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('classroom_id')->constrained('classrooms');
            $table->enum('type', ['quiz', 'midterm', 'final', 'assignment']);
            $table->integer('total_marks');
            $table->integer('passing_marks');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
};