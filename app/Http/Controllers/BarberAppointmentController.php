<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BarberAppointmentController extends Controller
{
    public function index($barberId)
    {
        try {
            $appointments = Appointment::where('barber_id', $barberId)
                ->orderBy('date')
                ->orderBy('time')
                ->with('barber')
                ->get();
            return response()->json($appointments);
        } catch (\Exception $e) {
            return
                response()->json(['error' => 'errore nel recupero della prenotazione'], 500);
        }
    }
}
