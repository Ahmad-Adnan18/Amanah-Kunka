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
        Schema::create('hour_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('subject_category')->comment('Merujuk ke kolom category di mata_pelajarans');
            $table->tinyInteger('day_of_week');
            $table->tinyInteger('time_slot');
            $table->boolean('is_allowed')->default(true);
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
        Schema::dropIfExists('hour_priorities');
    }
};
