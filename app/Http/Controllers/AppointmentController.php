<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Restituisce tutti gli appuntamenti con relazioni utente e parrucchiere.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Carica tutti gli appuntamenti con le relazioni utente e parrucchiere
        $appointments = Appointment::with(['user', 'barber'])->get();
        return response()->json($appointments);
    }

    /**
     * Salva un nuovo appuntamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validazione dei dati della richiesta
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:barbers,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        // Creazione del nuovo appuntamento
        $appointment = Appointment::create($validatedData);

        // Ritorna una risposta JSON con il nuovo appuntamento e codice 201 (created)
        return response()->json($appointment, 201);
    }

    /**
     * Mostra i dettagli di un singolo appuntamento.
     *
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
    {
        // Ritorna una risposta JSON con i dettagli dell'appuntamento
        return response()->json($appointment);
    }

    /**
     * Aggiorna i dettagli di un appuntamento esistente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validazione dei dati della richiesta
        $validatedData = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'barber_id' => 'sometimes|required|exists:barbers,id',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required|date_format:H:i',
        ]);

        // Aggiornamento dell'appuntamento con i dati validati
        $appointment->update($validatedData);

        // Ritorna una risposta JSON con l'appuntamento aggiornato
        return response()->json($appointment);
    }

    /**
     * Cancella un appuntamento esistente.
     *
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        // Elimina l'appuntamento dal database
        $appointment->delete();

        // Ritorna una risposta JSON vuota con codice 204 (no content)
        return response()->json(null, 204);
    }
}
