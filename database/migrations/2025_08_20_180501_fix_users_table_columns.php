<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Make sure the columns are properly configured
            if (Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->change();
            } else {
                $table->string('profile_photo')->nullable()->after('role');
            }
            
            if (Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->change();
            } else {
                $table->timestamp('last_login_at')->nullable()->after('profile_photo');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // No need to drop columns in down method for safety
        });
    }
};