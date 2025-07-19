<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'mobile')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('mobile')->nullable(false)->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('mobile')->after('email');
            });
        }

        // Check if isactive column exists and fix if needed
        if (Schema::hasColumn('users', 'isactive')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('isactive')->default(true)->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('isactive')->default(true)->after('password');
            });
        }

        // Check if role_id column exists and fix if needed
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id')->nullable(false)->change();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id')->after('isactive');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
