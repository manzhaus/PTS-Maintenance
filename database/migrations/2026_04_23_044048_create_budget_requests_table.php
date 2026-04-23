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
    Schema::create('budget_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pts_id'); // We'll link this to a PTS table or string
        $table->decimal('jumlah_dipohon', 12, 2);
        $table->text('sebab');
        $table->string('lampiran_quotation')->nullable();
        $table->enum('status', ['Draft', 'Submitted', 'Approved', 'Rejected'])->default('Draft');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_requests');
    }
};
