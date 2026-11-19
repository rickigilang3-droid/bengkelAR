<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $vehicles = $user->vehicles;
        $bookingRequests = \App\Models\BookingRequest::where('user_id', $user->id)
            ->with('vehicle')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.booking_blade', compact('user', 'vehicles', 'bookingRequests'));
    }
}
