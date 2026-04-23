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
    Schema::create('staff', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('jawatan');
        $table->decimal('gaji_asas', 10, 2); 
        $table->string('pts_lokasi'); // 1 staff = 1 PTS
        $table->date('tarikh_mula_kerja');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
