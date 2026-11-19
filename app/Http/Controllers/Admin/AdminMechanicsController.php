<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminMechanicsController extends Controller
{
    public function index(): View
    {
        return view('dashboard.admin.mechanics.index', [
            'mechanics' => User::query()
                ->whereIn('role', [User::ROLE_MECHANIC, User::ROLE_TECHNICIAN])
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->forceCreate([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_MECHANIC,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.mechanics.index')
            ->with('success', 'Akun mekanik berhasil dibuat. Sampaikan email dan kata sandi awal secara pribadi.');
    }
}
