<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id(); // Campo auto-incrementante per l'ID dell'appuntamento

            // Chiave esterna per l'utente associato all'appuntamento
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Chiave esterna per il parrucchiere associato all'appuntamento
            $table->foreignId('barber_id')->constrained('barbers')->onDelete('cascade');

            $table->date('date'); // Campo per la data dell'appuntamento
            $table->time('time'); // Campo per l'ora dell'appuntamento
            $table->timestamps(); // Campi per la gestione automatica delle date di creazione e aggiornamento

            // Non è necessario aggiungere un campo 'deleted_at' per Soft Deletes, 
            // a meno che non sia richiesta un eliminazione logica dei record.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments'); // Elimina la tabella 'appointments' se esiste
    }
};
