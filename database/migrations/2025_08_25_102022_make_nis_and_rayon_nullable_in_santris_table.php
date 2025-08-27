<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->string('nis')->nullable()->change();
            $table->string('rayon')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->string('nis')->nullable(false)->change();
            $table->string('rayon')->nullable(false)->change();
        });
    }
};