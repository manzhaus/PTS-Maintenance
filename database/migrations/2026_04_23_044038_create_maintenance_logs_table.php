<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            
            // Relationship to Lorry
            $table->foreignId('lorry_id')->constrained()->onDelete('cascade');
            
            // Audit Trail: Who created/edited this?
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            // Maintenance Details
            $table->date('tarikh');
            $table->enum('jenis_maintenance', ['Servis', 'Tayar', 'Bateri', 'Repair', 'Lain-lain']);
            $table->decimal('kos_rm', 12, 2);
            $table->string('vendor');
            $table->string('resit_upload')->nullable(); // Path to the file
            $table->integer('odometer_masa_servis');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
