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
        Schema::table('schedules', function (Blueprint $table) {
            // 1. Drop the foreign key constraint first
            $table->dropForeign(['user_id']);

            // 2. Rename the column
            $table->renameColumn('user_id', 'teacher_id');

            // 3. Re-add the foreign key constraint with the new column name
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Revert the changes if we roll back
            $table->dropForeign(['teacher_id']);
            $table->renameColumn('teacher_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
