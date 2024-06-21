<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barber;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::all();
        return response()->json($barbers);
    }

    public function store(Request $request)
    {
        $barber = Barber::create($request->all());
        return response()->json($barber, 201);
    }

    public function update(Request $request, $id)
    {
        $barber = Barber::findOrFail($id);
        $barber->update($request->all());
        return response()->json($barber, 200);
    }

    public function destroy($id)
    {
        Barber::destroy($id);
        return response()->json(null, 204);
    }
}
