<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $histories = \App\Models\ServiceHistory::where('user_id', $user->id)
            ->with('vehicle')
            ->orderBy('completed_at', 'desc')
            ->get();

        $totalSpent = $histories->sum('total_cost');
        $completedCount = $histories->count();

        return view('dashboard.riwayat_blade', compact('user', 'histories', 'totalSpent', 'completedCount'));
    }
}
