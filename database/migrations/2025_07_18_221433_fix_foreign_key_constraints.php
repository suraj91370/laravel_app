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
        $constraintName = 'users_role_id_foreign';
        $tableName = 'users';

        $constraintExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.table_constraints 
            WHERE constraint_schema = DATABASE() 
            AND table_name = '$tableName' 
            AND constraint_name = '$constraintName'
        ")[0]->count > 0;

        if ($constraintExists) {
            // Drop the existing constraint
            Schema::table('users', function (Blueprint $table) use ($constraintName) {
                $table->dropForeign($constraintName);
            });
        }

        // Add the foreign key constraint with the correct name
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
