<?php

// create_department_subject_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('department_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('year_id')->constrained('academic_years');
            $table->string('semester');
            $table->timestamps();
            
            $table->unique(['department_id', 'subject_id', 'year_id', 'semester'], 'department_subject_year_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('department_subject');
    }
};