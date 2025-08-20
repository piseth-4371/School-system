<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Fix academic_years table (if needed)
        if (Schema::hasTable('academic_years')) {
            Schema::table('academic_years', function (Blueprint $table) {
                if (!Schema::hasColumn('academic_years', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // 2. Fix class_years table relationships
        Schema::table('class_years', function (Blueprint $table) {
            // Add missing foreign key for academic_year_id
            if (Schema::hasColumn('class_years', 'academic_year_id') && 
                !$this->hasForeignKey('class_years', 'class_years_academic_year_id_foreign')) {
                $table->foreign('academic_year_id')
                      ->references('id')
                      ->on('academic_years')
                      ->onDelete('restrict');
            }

            // Fix year_id to reference academic_years
            if (Schema::hasColumn('class_years', 'year_id') && 
                !$this->hasForeignKey('class_years', 'class_years_year_id_foreign')) {
                $table->foreign('year_id')
                      ->references('id')
                      ->on('academic_years')
                      ->onDelete('restrict');
            }

            // Ensure other foreign keys exist
            $foreignKeys = [
                'class_id' => 'classes',
                'department_id' => 'departments',
                'shift_id' => 'shifts',
                'classroom_id' => 'classrooms'
            ];

            foreach ($foreignKeys as $column => $tableName) {
                if (Schema::hasColumn('class_years', $column) && 
                    !$this->hasForeignKey('class_years', "class_years_{$column}_foreign")) {
                    $table->foreign($column)
                          ->references('id')
                          ->on($tableName)
                          ->onDelete('restrict');
                }
            }
        });

        // 3. Fix classes table
        Schema::table('classes', function (Blueprint $table) {
            if (Schema::hasColumn('classes', 'department_id') && 
                !$this->hasForeignKey('classes', 'classes_department_id_foreign')) {
                $table->foreign('department_id')
                      ->references('id')
                      ->on('departments')
                      ->onDelete('restrict');
            }

            if (Schema::hasColumn('classes', 'teacher_id') && 
                !$this->hasForeignKey('classes', 'classes_teacher_id_foreign')) {
                $table->foreign('teacher_id')
                      ->references('id')
                      ->on('teachers')
                      ->onDelete('set null');
            }
        });

        // 4. Fix department_subject table
        Schema::table('department_subject', function (Blueprint $table) {
            $foreignKeys = [
                'department_id' => 'departments',
                'subject_id' => 'subjects',
                'year_id' => 'academic_years'
            ];

            foreach ($foreignKeys as $column => $tableName) {
                if (Schema::hasColumn('department_subject', $column) && 
                    !$this->hasForeignKey('department_subject', "department_subject_{$column}_foreign")) {
                    $table->foreign($column)
                          ->references('id')
                          ->on($tableName)
                          ->onDelete('restrict');
                }
            }
        });

        // 5. Fix exams table
        Schema::table('exams', function (Blueprint $table) {
            $foreignKeys = [
                'class_year_id' => 'class_years',
                'subject_id' => 'subjects',
                'classroom_id' => 'classrooms',
                'teacher_id' => 'teachers'
            ];

            foreach ($foreignKeys as $column => $tableName) {
                if (Schema::hasColumn('exams', $column) && 
                    !$this->hasForeignKey('exams', "exams_{$column}_foreign")) {
                    $table->foreign($column)
                          ->references('id')
                          ->on($tableName)
                          ->onDelete('restrict');
                }
            }
        });

        // 6. Fix attendances table (remove duplicate student_id)
        if (Schema::hasColumn('attendances', 'student_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            });
        }

        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'class_year_id') && 
                !$this->hasForeignKey('attendances', 'attendances_class_year_id_foreign')) {
                $table->foreign('class_year_id')
                      ->references('id')
                      ->on('class_years')
                      ->onDelete('cascade');
            }

            if (Schema::hasColumn('attendances', 'teacher_id') && 
                !$this->hasForeignKey('attendances', 'attendances_teacher_id_foreign')) {
                $table->foreign('teacher_id')
                      ->references('id')
                      ->on('teachers')
                      ->onDelete('restrict');
            }
        });

        // 7. Fix attendance_details table
        Schema::table('attendance_details', function (Blueprint $table) {
            if (Schema::hasColumn('attendance_details', 'attendance_id') && 
                !$this->hasForeignKey('attendance_details', 'attendance_details_attendance_id_foreign')) {
                $table->foreign('attendance_id')
                      ->references('id')
                      ->on('attendances')
                      ->onDelete('cascade');
            }

            if (Schema::hasColumn('attendance_details', 'student_id') && 
                !$this->hasForeignKey('attendance_details', 'attendance_details_student_id_foreign')) {
                $table->foreign('student_id')
                      ->references('id')
                      ->on('students')
                      ->onDelete('cascade');
            }
        });

        // 8. Fix students table
        Schema::table('students', function (Blueprint $table) {
            $foreignKeys = [
                'user_id' => 'users',
                'department_id' => 'departments',
                'class_year_id' => 'class_years'
            ];

            foreach ($foreignKeys as $column => $tableName) {
                if (Schema::hasColumn('students', $column) && 
                    !$this->hasForeignKey('students', "students_{$column}_foreign")) {
                    $table->foreign($column)
                          ->references('id')
                          ->on($tableName)
                          ->onDelete('restrict');
                }
            }
        });

        // 9. Fix teachers table
        Schema::table('teachers', function (Blueprint $table) {
            $foreignKeys = [
                'user_id' => 'users',
                'department_id' => 'departments'
            ];

            foreach ($foreignKeys as $column => $tableName) {
                if (Schema::hasColumn('teachers', $column) && 
                    !$this->hasForeignKey('teachers', "teachers_{$column}_foreign")) {
                    $table->foreign($column)
                          ->references('id')
                          ->on($tableName)
                          ->onDelete('restrict');
                }
            }
        });

        // 10. Fix timetables table
        Schema::table('timetables', function (Blueprint $table) {
            $foreignKeys = [
                'class_year_id' => 'class_years',
                'subject_id' => 'subjects',
                'teacher_id' => 'teachers',
                'classroom_id' => 'classrooms',
                'day_id' => 'days'
            ];

            foreach ($foreignKeys as $column => $tableName) {
                if (Schema::hasColumn('timetables', $column) && 
                    !$this->hasForeignKey('timetables', "timetables_{$column}_foreign")) {
                    $table->foreign($column)
                          ->references('id')
                          ->on($tableName)
                          ->onDelete('restrict');
                }
            }
        });

        // 11. Fix payments table
        Schema::table('payments', function (Blueprint $table) {
            $foreignKeys = [
                'student_id' => 'students',
                'recorded_by' => 'users'
            ];

            foreach ($foreignKeys as $column => $tableName) {
                if (Schema::hasColumn('payments', $column) && 
                    !$this->hasForeignKey('payments', "payments_{$column}_foreign")) {
                    $table->foreign($column)
                          ->references('id')
                          ->on($tableName)
                          ->onDelete('restrict');
                }
            }
        });

        // 12. Drop school_classes table if it exists (duplicate of classes)
        if (Schema::hasTable('school_classes')) {
            Schema::dropIfExists('school_classes');
        }
    }

    public function down()
    {
        // Drop all foreign keys added in this migration
        $tables = [
            'class_years' => ['academic_year_id', 'year_id', 'class_id', 'department_id', 'shift_id', 'classroom_id'],
            'classes' => ['department_id', 'teacher_id'],
            'department_subject' => ['department_id', 'subject_id', 'year_id'],
            'exams' => ['class_year_id', 'subject_id', 'classroom_id', 'teacher_id'],
            'attendances' => ['class_year_id', 'teacher_id'],
            'attendance_details' => ['attendance_id', 'student_id'],
            'students' => ['user_id', 'department_id', 'class_year_id'],
            'teachers' => ['user_id', 'department_id'],
            'timetables' => ['class_year_id', 'subject_id', 'teacher_id', 'classroom_id', 'day_id'],
            'payments' => ['student_id', 'recorded_by']
        ];

        foreach ($tables as $table => $columns) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($columns) {
                    foreach ($columns as $column) {
                        $foreignKeyName = "{$table}_{$column}_foreign";
                        if ($this->hasForeignKey($table, $foreignKeyName)) {
                            $table->dropForeign([$column]);
                        }
                    }
                });
            }
        }

        // Re-add student_id to attendances table if it was removed
        if (!Schema::hasColumn('attendances', 'student_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Check if a foreign key exists
     */
    private function hasForeignKey($table, $foreignKeyName): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        return $connection->table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $foreignKeyName)
            ->exists();
    }
};