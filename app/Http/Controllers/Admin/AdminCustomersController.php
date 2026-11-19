<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AdminCustomersController extends Controller
{
    /**
     * Display the customers list
     */
    public function index(Request $request)
    {
        $query = User::query()->where('role', User::ROLE_CUSTOMER)->with(['vehicles']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('is_active', $status === 'active');
        }

        $customers = $query->paginate(15);

        return view('dashboard.admin.customers.index', [
            'title' => 'Customers',
            'description' => 'Manajemen pelanggan dan kendaraan aktif dalam sistem bengkel.',
            'active' => 'customers',
            'totalCustomers' => User::query()->where('role', User::ROLE_CUSTOMER)->count(),
            'totalVehicles' => Vehicle::query()->whereHas('user', fn ($query) => $query->where('role', User::ROLE_CUSTOMER))->count(),
            'activeCustomers' => User::query()->where('role', User::ROLE_CUSTOMER)->where('is_active', true)->count(),
            'customers' => $customers,
        ]);
    }

    /**
     * Show customer details with vehicles
     */
    public function show($id)
    {
        $customer = User::query()
            ->where('role', User::ROLE_CUSTOMER)
            ->with(['vehicles', 'serviceQueues', 'serviceHistories'])
            ->findOrFail($id);

        return view('dashboard.admin.customers.show', [
            'title' => 'Detail Pelanggan',
            'active' => 'customers',
            'customer' => $customer,
        ]);
    }

    /**
     * Update customer information
     */
    public function update(Request $request, $id)
    {
        $customer = User::query()->where('role', User::ROLE_CUSTOMER)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $id,
            'address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer->id)
                       ->with('success', 'Data pelanggan berhasil diupdate');
    }

    /**
     * Delete customer
     */
    public function destroy($id)
    {
        $customer = User::query()->where('role', User::ROLE_CUSTOMER)->findOrFail($id);

        // Delete related vehicles
        $customer->vehicles()->delete();

        $customer->delete();

        return redirect()->route('admin.customers.index')
                       ->with('success', 'Pelanggan berhasil dihapus');
    }

    /**
     * Admin add vehicle for customer
     */
    public function addVehicle(Request $request, $userId)
    {
        $user = User::query()->where('role', User::ROLE_CUSTOMER)->findOrFail($userId);

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'year' => 'nullable|integer',
            'color' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = $user->id;
        $validated['vehicle_type'] = 'Motor';
        $validated['year'] = $request->year ?? date('Y');
        $validated['color'] = $request->color ?? 'Hitam';

        Vehicle::create($validated);

        return redirect()->route('admin.customers.show', $user->id)
                       ->with('success', 'Kendaraan pelanggan berhasil ditambahkan!');
    }

    /**
     * Admin update customer vehicle
     */
    public function updateVehicle(Request $request, $id)
    {
        $vehicle = Vehicle::query()
            ->whereHas('user', fn ($query) => $query->where('role', User::ROLE_CUSTOMER))
            ->findOrFail($id);

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,' . $id,
            'year' => 'nullable|integer',
            'color' => 'nullable|string|max:50',
        ]);

        $vehicle->update($validated);

        return redirect()->route('admin.customers.show', $vehicle->user_id)
                       ->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    /**
     * Admin delete customer vehicle
     */
    public function deleteVehicle($id)
    {
        $vehicle = Vehicle::query()
            ->whereHas('user', fn ($query) => $query->where('role', User::ROLE_CUSTOMER))
            ->findOrFail($id);
        $userId = $vehicle->user_id;

        $vehicle->delete();

        return redirect()->route('admin.customers.show', $userId)
                       ->with('success', 'Kendaraan berhasil dihapus!');
    }
}
