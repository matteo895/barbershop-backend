<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    /**
     * Restituisce tutti i parrucchieri.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Ottiene tutti i parrucchieri dal database
        $barbers = Barber::all();
        return response()->json($barbers);
    }

    /**
     * Salva un nuovo parrucchiere.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validazione dei dati della richiesta
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'required|string',
            'description' => 'required|string',
        ]);

        // Creazione del nuovo parrucchiere
        $barber = Barber::create($validatedData);

        // Ritorna una risposta JSON con il nuovo parrucchiere e codice 201 (creato)
        return response()->json($barber, 201);
    }

    /**
     * Mostra i dettagli di un singolo parrucchiere.
     *
     * @param  Barber  $barber
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Barber $barber)
    {
        // Ritorna una risposta JSON con i dettagli del parrucchiere
        return response()->json($barber);
    }

    /**
     * Aggiorna i dettagli di un parrucchiere esistente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Barber  $barber
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Barber $barber)
    {
        // Validazione dei dati della richiesta
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'photo' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
        ]);

        // Aggiornamento del parrucchiere con i dati validati
        $barber->update($validatedData);

        // Ritorna una risposta JSON con il parrucchiere aggiornato
        return response()->json($barber);
    }

    /**
     * Cancella un parrucchiere esistente.
     *
     * @param  Barber  $barber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barber $barber)
    {
        // Elimina il parrucchiere dal database
        $barber->delete();

        // Ritorna una risposta JSON vuota con codice 204 (no content)
        return response()->json(null, 204);
    }
}
