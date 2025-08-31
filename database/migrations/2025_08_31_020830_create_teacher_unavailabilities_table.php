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
    public function up()
    {
        Schema::create('teacher_unavailabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->comment('1 for Monday, 7 for Sunday');
            $table->tinyInteger('time_slot')->comment('Jam ke- (1, 2, 3, etc)');
            $table->timestamps();

            // Mencegah entri duplikat untuk guru, hari, dan jam yang sama
            $table->unique(['user_id', 'day_of_week', 'time_slot']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_unavailabilities');
    }
};
