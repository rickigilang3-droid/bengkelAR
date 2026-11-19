<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        return view('dashboard.pelanggan_blade', compact('user'));
    }
}
