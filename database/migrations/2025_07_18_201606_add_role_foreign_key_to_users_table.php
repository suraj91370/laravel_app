<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if foreign key constraint already exists
            $constraintName = 'users_role_id_foreign';
            $constraintExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.table_constraints 
            WHERE constraint_schema = DATABASE() 
            AND table_name = 'users' 
            AND constraint_name = '$constraintName'
        ")[0]->count > 0;

            if (!$constraintExists) {
                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
    }
};
