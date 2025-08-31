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
        Schema::create('blocked_times', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day_of_week');
            $table->tinyInteger('time_slot');
            $table->string('reason')->nullable()->comment('Contoh: Upacara Bendera');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocked_times');
    }
};
