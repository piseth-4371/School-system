<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');  // The name of the class (e.g., "Math 101")
            $table->string('class_code')->unique();  // Unique identifier or code for the class
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');  // Foreign key to the teachers table
            $table->integer('capacity')->nullable();  // Maximum number of students in the class
            $table->boolean('is_active')->default(true);  // Active status of the class
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
