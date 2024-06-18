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
        Schema::create('barbers', function (Blueprint $table) {
            $table->id(); // Campo auto-incrementante per l'ID del parrucchiere

            $table->string('name'); // Campo per il nome del parrucchiere (obbligatorio)
            $table->string('photo')->nullable(); // Campo per l'URL della foto del parrucchiere (opzionale, nullable)
            $table->text('description')->nullable(); // Campo per la descrizione del parrucchiere (opzionale, nullable)
            $table->timestamps(); // Campi per la gestione automatica delle date di creazione e aggiornamento

            // Non è necessario aggiungere un campo 'deleted_at' per Soft Deletes, 
            // a meno che non sia richiesto un eliminazione logica dei record.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbers'); // Elimina la tabella 'barbers' se esiste
    }
};
