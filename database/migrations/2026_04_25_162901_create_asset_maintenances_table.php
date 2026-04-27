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
        Schema::create('asset_maintenances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('asset_id')->constrained()->onDelete('cascade');
    $table->string('jenis_kerja'); // e.g., "Repair Bumbung", "Tukar Minyak Hitam"
    $table->decimal('kos_rm', 10, 2);
    $table->date('tarikh');
    $table->string('resit_path')->nullable();
    $table->enum('status', ['Siap', 'Dalam Proses'])->default('Dalam Proses');
    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
