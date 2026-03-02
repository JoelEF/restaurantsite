<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        return view('reservation');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'guests' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        Reservation::create($validated);

        return redirect()->route('reservation.index')
            ->with('success', 'Uw reservering is ontvangen! We nemen zo snel mogelijk contact met u op ter bevestiging.');
    }
}
