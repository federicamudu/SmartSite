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
        Schema::create('action_logs', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Usiamo sempre gli UUID per sicurezza
            $table->foreignUuid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete(); // Chi ha fatto l'azione
            $table->string('action'); // Un codice breve, es: 'document_approved'
            $table->string('description'); // La frase leggibile, es: 'Mario ha approvato il documento PID...'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_logs');
    }
};
