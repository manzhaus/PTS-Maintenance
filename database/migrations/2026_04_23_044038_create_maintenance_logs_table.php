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
    Schema::create('maintenance_logs', function (Blueprint $table) {
        $table->id();
        $table->morphs('maintainable'); // This allows logs for both Lorries AND Assets (Flexible!)
        $table->date('tarikh');
        $table->string('jenis_maintenance'); // Servis, Tayar, etc.
        $table->decimal('kos_rm', 12, 2);
        $table->string('vendor');
        $table->string('resit_upload')->nullable();
        $table->integer('odometer_masa_servis')->nullable(); // For lorries
        $table->string('status')->default('Siap'); // Siap, Dalam Proses
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
