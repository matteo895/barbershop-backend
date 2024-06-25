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
            // Log dell'eccezione
            Log::error('Errore durante il recupero degli appuntamenti', ['error' => $e->getMessage()]);
            // Ritorna una risposta JSON di errore
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
            // Log dei dati della richiesta
            Log::info('Tentativo di salvataggio di un nuovo appuntamento', $request->all());

            // Validazione dei dati della richiesta
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'barber_id' => 'required|exists:barbers,id',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'name' => 'required|string', // Campo 'name' aggiunto
            ]);

            // Verifica se l'orario è già prenotato per lo stesso parrucchiere
            $conflictingAppointment = Appointment::where('barber_id', $validatedData['barber_id'])
                ->where('date', $validatedData['date'])
                ->where('time', $validatedData['time'])
                ->first();

            if ($conflictingAppointment) {
                Log::info('Conflitto di orario: Questo orario è già prenotato per questo parrucchiere.', [
                    'barber_id' => $validatedData['barber_id'],
                    'date' => $validatedData['date'],
                    'time' => $validatedData['time'],
                ]);

                return response()->json(['error' => 'Questo orario è già prenotato per questo parrucchiere.'], 409);
            }

            // Creazione del nuovo appuntamento con il campo 'name'
            $appointment = new Appointment();
            $appointment->user_id = $validatedData['user_id'];
            $appointment->barber_id = $validatedData['barber_id'];
            $appointment->date = $validatedData['date'];
            $appointment->time = $validatedData['time'];
            $appointment->name = $validatedData['name'];
            $appointment->save();

            // Log del nuovo appuntamento creato
            Log::info('Appuntamento creato', $appointment->toArray());

            // Ritorna una risposta JSON con il nuovo appuntamento e codice 201 (created)
            return response()->json($appointment, 201);
        } catch (\Exception $e) {
            // Log dell'eccezione
            Log::error('Errore durante il salvataggio dell\'appuntamento', ['error' => $e->getMessage()]);
            // Ritorna una risposta JSON di errore
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
            // Log dell'eccezione
            Log::error('Errore durante il recupero dei dettagli dell\'appuntamento', ['error' => $e->getMessage()]);
            // Ritorna una risposta JSON di errore
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
            // Log dei dati della richiesta
            Log::info('Tentativo di aggiornamento dell\'appuntamento', $request->all());

            // Validazione dei dati della richiesta
            $validatedData = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'barber_id' => 'sometimes|required|exists:barbers,id',
                'date' => 'sometimes|required|date',
                'time' => 'sometimes|required|date_format:H:i',
                'name' => 'sometimes|required|string',
            ]);

            // Verifica se esiste già un appuntamento per lo stesso parrucchiere, data e ora
            if (isset($validatedData['barber_id']) && isset($validatedData['date']) && isset($validatedData['time'])) {
                $existingAppointment = Appointment::where('barber_id', $validatedData['barber_id'])
                    ->where('date', $validatedData['date'])
                    ->where('time', $validatedData['time'])
                    ->where('id', '!=', $appointment->id)
                    ->first();

                if ($existingAppointment) {
                    Log::info('Conflitto di orario: Questo orario è già prenotato per questo parrucchiere.', [
                        'barber_id' => $validatedData['barber_id'],
                        'date' => $validatedData['date'],
                        'time' => $validatedData['time'],
                    ]);

                    return response()->json(['error' => 'Questo orario è già prenotato per questo parrucchiere.'], 409);
                }
            }

            // Aggiornamento dell'appuntamento con i dati validati
            $appointment->update($validatedData);

            // Carica di nuovo l'appuntamento con le relazioni aggiornate
            $updatedAppointment = Appointment::with('barber')->find($appointment->id);

            // Log dell'aggiornamento dell'appuntamento
            Log::info('Appuntamento aggiornato', $appointment->toArray());

            // Ritorna una risposta JSON con l'appuntamento aggiornato, compreso di parrucchiere
            return response()->json($updatedAppointment);
        } catch (\Exception $e) {
            // Log dell'eccezione
            Log::error('Errore durante l\'aggiornamento dell\'appuntamento', ['error' => $e->getMessage()]);
            // Ritorna una risposta JSON di errore
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
            // Log dell'eliminazione dell'appuntamento
            Log::info('Tentativo di cancellazione dell\'appuntamento', $appointment->toArray());

            // Elimina l'appuntamento dal database
            $appointment->delete();

            // Log dell'eliminazione dell'appuntamento
            Log::info('Appuntamento eliminato', ['id' => $appointment->id]);

            // Ritorna una risposta JSON vuota con codice 204 (no content)
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Log dell'eccezione
            Log::error('Errore durante l\'eliminazione dell\'appuntamento', ['error' => $e->getMessage()]);
            // Ritorna una risposta JSON di errore
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
            // Log dell'eccezione
            Log::error('Errore durante il recupero dei parrucchieri disponibili', ['error' => $e->getMessage()]);
            // Ritorna una risposta JSON di errore
            return response()->json(['error' => 'Errore durante il recupero dei parrucchieri disponibili'], 500);
        }
    }
}
