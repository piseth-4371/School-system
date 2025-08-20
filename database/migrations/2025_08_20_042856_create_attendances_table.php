<?php

// create_attendances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('class_year_id')->constrained('class_years');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->timestamps();
            
            $table->unique(['date', 'class_year_id'], 'attendance_date_class_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};