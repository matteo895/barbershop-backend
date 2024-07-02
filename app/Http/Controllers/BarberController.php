<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barber;
use Illuminate\Support\Facades\Storage;

class BarberController extends Controller
{
    // Metodo per ottenere tutti i parrucchieri
    public function index()
    {
        $barbers = Barber::all(); // Ottiene tutti i parrucchieri dal database
        return response()->json($barbers); // Restituisce i parrucchieri in formato JSON
    }

    // Metodo per salvare un nuovo parrucchiere
    public function store(Request $request)
    {
        // Validazione della richiesta
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
        ]);

        // Gestione del file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo'); // Ottiene il file caricato
            $path = $file->store('images', 'public'); // Salva il file nella cartella public/images
        } else {
            return response()->json(['error' => 'File non trovato'], 400); // Se il file non è presente, restituisce un errore
        }

        // Creazione del nuovo parrucchiere
        $barber = new Barber();
        $barber->name = $request->input('name'); // Imposta il nome del parrucchiere
        $barber->photo = '/storage/' . $path; // Imposta il percorso dell'immagine salvata
        $barber->description = $request->input('description'); // Imposta la descrizione del parrucchiere
        $barber->save(); // Salva il nuovo parrucchiere nel database

        return response()->json($barber, 201); // Restituisce il parrucchiere creato con codice di stato HTTP 201 (Created)
    }

    // Metodo per aggiornare un parrucchiere esistente
    public function update(Request $request, $id)
    {
        // Validazione della richiesta
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
        ]);

        // Trova il parrucchiere da aggiornare
        $barber = Barber::findOrFail($id);

        // Aggiorna i dati del parrucchiere con i valori dalla richiesta
        if ($request->input('name')) {
            $barber->name = $request->input('name');
        }
        if ($request->input('description')) {
            $barber->description = $request->input('description');
        }

        // Salva le modifiche nel database
        $barber->save();

        return response()->json($barber, 200);
    }

    // Metodo per eliminare un parrucchiere
    public function destroy($id)
    {
        // Trova il parrucchiere da eliminare
        $barber = Barber::findOrFail($id);

        // Cancella l'immagine associata al parrucchiere se presente
        if ($barber->photo) {
            Storage::disk('public')->delete($barber->photo);
        }

        $barber->delete(); // Elimina il parrucchiere dal database

        return response()->json(null, 204); // Restituisce una risposta vuota con codice di stato HTTP 204 (No Content)
    }
}
