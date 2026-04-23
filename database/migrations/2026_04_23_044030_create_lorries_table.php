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
    Schema::create('lorries', function (Blueprint $table) {
        $table->id();
        $table->string('no_plat')->unique();
        $table->string('model');
        $table->integer('tahun');
        $table->string('pts_lokasi');
        $table->integer('odometer_semasa');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lorries');
    }
};
