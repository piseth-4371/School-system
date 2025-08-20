<?php

// create_class_years_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('year_id')->constrained('academic_years');
            $table->enum('semester', ['first', 'second', 'summer'])->default('first');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('shift_id')->nullable()->constrained('shifts');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['class_id', 'year_id', 'semester'], 'class_year_semester_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_years');
    }
};
