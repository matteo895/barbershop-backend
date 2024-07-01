<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BarberAppointmentController extends Controller
{
    public function index($barber_id)
    {
        $today = Carbon::today()->toDateString();

        $appointments = Appointment::where('barber_id', $barber_id)
            ->whereDate('date', $today)
            ->orderBy('time')
            ->with('barber')
            ->get();
        return response()->json($appointments);
    }
}
