<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('school_class_id');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_class_id')->nullable()->constrained('classes')->onDelete('set null');
        });
    }

};
