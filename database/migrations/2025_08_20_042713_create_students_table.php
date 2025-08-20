<?php

// create_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 2025_08_19_072503_create_students_table.php (Updated)
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('student_code')->unique();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth'); // Changed from 'dob' to match controller
            $table->text('address');
            $table->string('phone')->nullable(); // Changed from 'phone_number'
            $table->string('parent_name'); // Added missing field
            $table->string('parent_phone'); // Added missing field
            $table->date('enrolled_date'); // Changed from 'enrollment_date'
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('class_year_id')->constrained('class_years')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};