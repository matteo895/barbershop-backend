<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Restituisce tutti gli appuntamenti con relazioni utente e parrucchiere.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Carica tutti gli appuntamenti con le relazioni utente e parrucchiere
            $appointments = Appointment::with(['user', 'barber'])->get();
            return response()->json($appointments);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching appointments', ['error' => $e->getMessage()]);
            // Return a JSON error response
            return response()->json(['error' => 'Errore durante il recupero degli appuntamenti'], 500);
        }
    }

    /**
     * Salva un nuovo appuntamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Log request data
            Log::info('Attempting to store appointment', $request->all());

            // Validazione dei dati della richiesta
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'barber_id' => 'required|exists:barbers,id',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'name' => 'required|string', // Aggiunto il campo 'name' qui
            ]);

            $date = Carbon::parse($validatedData['date']);
            $time = Carbon::parse($validatedData['time']);

            // Controlli aggiuntivi come data passata, giorni chiusura, orario di lavoro, ecc...

            // Creazione del nuovo appuntamento con il campo 'name'
            $appointment = Appointment::create($validatedData);

            // Log created appointment
            Log::info('Created appointment', $appointment->toArray());

            // Ritorna una risposta JSON con il nuovo appuntamento e codice 201 (created)
            return response()->json($appointment, 201);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error storing appointment', ['error' => $e->getMessage()]);

            // Return a JSON error response
            return response()->json(['error' => 'Errore durante la prenotazione'], 500);
        }
    }

    /**
     * Mostra i dettagli di un singolo appuntamento.
     *
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
    {
        try {
            // Ritorna una risposta JSON con i dettagli dell'appuntamento
            return response()->json($appointment);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching appointment details', ['error' => $e->getMessage()]);
            // Return a JSON error response
            return response()->json(['error' => 'Errore durante il recupero dei dettagli dell\'appuntamento'], 500);
        }
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
        try {
            // Log request data
            Log::info('Attempting to update appointment', $request->all());

            // Validazione dei dati della richiesta
            $validatedData = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'barber_id' => 'sometimes|required|exists:barbers,id',
                'date' => 'sometimes|required|date',
                'time' => 'sometimes|required|date_format:H:i',
            ]);

            // Verifica se esiste già un appuntamento per lo stesso parrucchiere, data e ora
            if (isset($validatedData['barber_id']) && isset($validatedData['date']) && isset($validatedData['time'])) {
                $existingAppointment = Appointment::where('barber_id', $validatedData['barber_id'])
                    ->where('date', $validatedData['date'])
                    ->where('time', $validatedData['time'])
                    ->where('id', '!=', $appointment->id)
                    ->first();

                if ($existingAppointment) {
                    return response()->json(['error' => 'Questo orario è già prenotato per questo parrucchiere.'], 409);
                }
            }

            // Aggiornamento dell'appuntamento con i dati validati
            $appointment->update($validatedData);

            // Log updated appointment
            Log::info('Updated appointment', $appointment->toArray());

            // Ritorna una risposta JSON con l'appuntamento aggiornato
            return response()->json($appointment);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error updating appointment', ['error' => $e->getMessage()]);

            // Return a JSON error response
            return response()->json(['error' => 'Errore durante l\'aggiornamento dell\'appuntamento'], 500);
        }
    }

    /**
     * Cancella un appuntamento esistente.
     *
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        try {
            // Log deletion attempt
            Log::info('Attempting to delete appointment', $appointment->toArray());

            // Elimina l'appuntamento dal database
            $appointment->delete();

            // Log successful deletion
            Log::info('Deleted appointment', ['id' => $appointment->id]);

            // Ritorna una risposta JSON vuota con codice 204 (no content)
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error deleting appointment', ['error' => $e->getMessage()]);

            // Return a JSON error response
            return response()->json(['error' => 'Errore durante l\'eliminazione dell\'appuntamento'], 500);
        }
    }

    /**
     * Restituisce la lista dei parrucchieri disponibili.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBarbers()
    {
        try {
            // Carica tutti i parrucchieri
            $barbers = Barber::all();
            return response()->json($barbers);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching barbers', ['error' => $e->getMessage()]);
            // Return a JSON error response
            return response()->json(['error' => 'Errore durante il recupero dei parrucchieri disponibili'], 500);
        }
    }
}
