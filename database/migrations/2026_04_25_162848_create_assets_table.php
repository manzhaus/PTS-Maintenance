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
        Schema::create('assets', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // e.g., "Genset Perkins 50kVA"
    $table->string('category'); // e.g., "Genset", "Weighbridge", "Bangunan"
    $table->string('pts_lokasi');
    $table->json('metadata')->nullable(); // Store specific fields like 'tarikh_kalibrasi_seterusnya' here
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
