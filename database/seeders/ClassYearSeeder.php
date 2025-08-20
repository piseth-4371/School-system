<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassYearSeeder extends Seeder
{
     public function run()
    {
        // First, make sure we have classes and academic years
        $classes = \App\Models\SchoolClass::pluck('id')->toArray();
        $academicYears = \App\Models\AcademicYear::pluck('id')->toArray();

        if (empty($classes) || empty($academicYears)) {
            echo "No classes or academic years found. Please run ClassSeeder and AcademicYearSeeder first.\n";
            return;
        }

        $classYears = [
            [
                'class_id' => $classes[0], 
                'academic_year_id' => $academicYears[0], 
                'semester' => 'first', 
                'is_active' => true
            ],
            [
                'class_id' => $classes[0], 
                'academic_year_id' => $academicYears[0], 
                'semester' => 'second', 
                'is_active' => true
            ],
            [
                'class_id' => $classes[1] ?? $classes[0], 
                'academic_year_id' => $academicYears[0], 
                'semester' => 'first', 
                'is_active' => true
            ],
            // Add more as needed
        ];

        foreach ($classYears as $classYear) {
            \App\Models\ClassYear::create($classYear);
        }
    }
}
