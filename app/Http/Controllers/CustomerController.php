<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.pelanggan_blade');
    }

    public function booking()
    {
        return view('dashboard.booking_blade');
    }

    public function riwayat()
    {
        return view('dashboard.riwayat_blade');
    }

    public function profil()
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $vehicles = $user->vehicles;

        return view('dashboard.profil_blade', compact('user', 'vehicles'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $user->phone,
            'email' => $validated['email'],
            'address' => $validated['address'] ?? $user->address,
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

        return redirect()->back()->with('success', 'Profil pengguna berhasil diperbarui!');
    }

    public function addVehicle(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'year' => 'nullable|integer',
            'color' => 'nullable|string|max:50',
        ]);

        $user = auth()->user();

        \App\Models\Vehicle::create([
            'user_id' => $user->id,
            'vehicle_type' => 'Motor',
            'brand' => $request->brand,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'year' => $request->year ?? date('Y'),
            'color' => $request->color ?? 'Hitam',
        ]);

        return redirect()->back()->with('success', 'Kendaraan baru berhasil ditambahkan!');
    }

    public function updateVehicle(Request $request, $id)
    {
        $user = auth()->user();
        $vehicle = \App\Models\Vehicle::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,' . $id,
            'year' => 'nullable|integer',
            'color' => 'nullable|string|max:50',
        ]);

        $vehicle->update([
            'brand' => $request->brand,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'year' => $request->year ?? $vehicle->year,
            'color' => $request->color ?? $vehicle->color,
        ]);

        return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    public function deleteVehicle($id)
    {
        $user = auth()->user();
        $vehicle = \App\Models\Vehicle::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $vehicle->delete();

        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus dari garasi Anda!');
    }
}