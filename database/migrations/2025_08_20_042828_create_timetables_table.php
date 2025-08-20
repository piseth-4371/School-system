<?php

// create_timetables_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_year_id')->constrained('class_years');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->foreignId('classroom_id')->constrained('classrooms');
            $table->foreignId('day_id')->constrained('days');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique([
                'class_year_id',
                'day_id',
                'start_time',
                'end_time',
                'classroom_id'
            ], 'timetable_slot_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('timetables');
    }
};