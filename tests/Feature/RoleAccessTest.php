<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceQueue;
use App\Models\ServiceHistory;
use App\Models\ServiceApprovalItem;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_routes_users_to_their_role_home(): void
    {
        $destinations = [
            User::ROLE_ADMIN => 'dashboard.admin',
            User::ROLE_CUSTOMER => 'dashboard.customer',
            User::ROLE_MECHANIC => 'dashboard.mechanic',
            User::ROLE_TECHNICIAN => 'dashboard.mechanic',
        ];

        foreach ($destinations as $role => $routeName) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertRedirect(route($routeName, absolute: false));
        }
    }

    public function test_role_dashboards_reject_users_with_other_roles(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $mechanic = User::factory()->create(['role' => User::ROLE_MECHANIC]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($customer)->get(route('dashboard.admin'))->assertForbidden();
        $this->actingAs($mechanic)->get(route('dashboard.customer'))->assertForbidden();
        $this->actingAs($admin)->get(route('dashboard.mechanic'))->assertForbidden();
    }

    public function test_history_detail_uses_the_actual_service_record_instead_of_sample_values(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER, 'name' => 'Nama Pelanggan Nyata']);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2023,
            'color' => 'Biru',
            'plate_number' => 'D 1234 NYATA',
        ]);
        $history = ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis injeksi',
            'description' => 'Pembersihan throttle body dan ganti busi',
            'entered_at' => now()->subHours(3),
            'completed_at' => now(),
            'total_cost' => 375000,
            'payment_status' => 'pending',
            'notes' => 'Uji jalan selesai.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.history.show', $history->id))
            ->assertOk()
            ->assertSee('Nama Pelanggan Nyata')
            ->assertSee('Yamaha')
            ->assertSee('NMAX')
            ->assertSee('D 1234 NYATA')
            ->assertSee('Pembersihan throttle body dan ganti busi')
            ->assertSee('Rp375.000')
            ->assertSee('Uji jalan selesai.')
            ->assertDontSee('Budi Santoso')
            ->assertDontSee('Toyota Avanza');
    }

    public function test_each_role_can_render_its_own_dashboard(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $mechanic = User::factory()->create(['role' => User::ROLE_MECHANIC]);

        $this->actingAs($admin)->get(route('dashboard.admin'))
            ->assertOk()
            ->assertSee('Bengkel AR')
            ->assertSee('Selesai hari ini')
            ->assertSee('Antrean servis terbaru');
        $this->actingAs($customer)->get(route('dashboard.customer'))
            ->assertOk()
            ->assertSee('Portal pelanggan')
            ->assertSee('Servis aktif')
            ->assertSee('Riwayat servis');
        $this->actingAs($mechanic)->get(route('dashboard.mechanic'))
            ->assertOk()
            ->assertSee('Ringkasan mekanik')
            ->assertSee('Antrean menunggu')
            ->assertSee('Sedang dikerjakan')
            ->assertSee('Pengajuan menunggu')
            ->assertSee('Riwayat selesai');
    }

    public function test_public_registration_cannot_choose_a_privileged_role(): void
    {
        $this->post(route('register'), [
            'name' => 'New Customer',
            'email' => 'customer@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => User::ROLE_ADMIN,
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'role' => User::ROLE_CUSTOMER,
        ]);
    }

    public function test_mechanics_can_advance_a_service_queue_one_status_at_a_time(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2024,
            'color' => 'Hitam',
            'plate_number' => 'B 1234 TEST',
        ]);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis berkala',
            'status' => 'waiting',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        $mechanic = User::factory()->create(['role' => User::ROLE_MECHANIC]);

        $this->actingAs($mechanic)
            ->patch(route('dashboard.mechanic.queue-status', $queue), ['status' => 'completed'])
            ->assertUnprocessable();

        $this->assertDatabaseHas('service_queues', [
            'id' => $queue->id,
            'status' => 'waiting',
        ]);

        $this->patch(route('dashboard.mechanic.queue-status', $queue), ['status' => 'in_progress'])
            ->assertRedirect();

        ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Kampas rem',
            'estimated_cost' => 175000,
            'notes' => 'Kampas aus dan diganti baru.',
            'status' => 'approved',
            'responded_at' => now(),
        ]);
        ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Barang yang ditolak',
            'estimated_cost' => 50000,
            'status' => 'rejected',
            'responded_at' => now(),
        ]);
        $legacyHistory = ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis Umum',
            'description' => $queue->complaint,
            'entered_at' => $queue->entered_at,
            'completed_at' => now(),
            'total_cost' => 0,
            'payment_status' => 'pending',
        ]);

        $this->assertDatabaseHas('service_queues', [
            'id' => $queue->id,
            'status' => 'in_progress',
            'completed_at' => null,
        ]);

        $this->patch(route('dashboard.mechanic.queue-status', $queue), ['status' => 'completed'])
            ->assertRedirect();

        $this->assertDatabaseHas('service_queues', [
            'id' => $queue->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('service_histories', [
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_queue_id' => $queue->id,
            'total_cost' => 175000,
            'payment_status' => 'pending',
        ]);
        $history = ServiceHistory::query()->where('service_queue_id', $queue->id)->firstOrFail();
        $this->assertSame($legacyHistory->id, $history->id);
        $this->assertStringContainsString('Kampas aus dan diganti baru.', $history->description);
        $this->assertStringNotContainsString('Barang yang ditolak', $history->description);
        $this->actingAs($customer)
            ->get(route('customer.history'))
            ->assertOk()
            ->assertSee('Kampas rem')
            ->assertSee('Kampas aus dan diganti baru.');
        $this->get(route('customer.history.show', $history))
            ->assertOk()
            ->assertSee('Cetak nota')
            ->assertSee('Kampas rem')
            ->assertSee('Belum dipilih');
        $this->actingAs($mechanic)
            ->patch(route('dashboard.mechanic.queue-status', $queue), ['status' => 'completed'])
            ->assertUnprocessable();
        $this->assertSame(1, ServiceHistory::query()->where('service_queue_id', $queue->id)->count());
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'title' => 'Servis Selesai',
        ]);
    }

    public function test_mechanics_can_manage_work_notes_odometer_and_customer_approved_parts(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2024,
            'color' => 'Hitam',
            'plate_number' => 'B 8888 MEK',
        ]);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis berkala',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        $mechanic = User::factory()->create(['role' => User::ROLE_MECHANIC]);
        $inventory = Inventory::query()->create([
            'part_code' => 'OLI-MTR-01',
            'name' => 'Oli mesin',
            'category' => 'Pelumas',
            'stock' => 3,
            'min_stock' => 1,
            'purchase_price' => 40000,
            'selling_price' => 55000,
            'unit' => 'Botol',
        ]);

        $this->actingAs($mechanic)
            ->get(route('dashboard.mechanic'))
            ->assertOk()
            ->assertSee('Catatan pemeriksaan dan pekerjaan')
            ->assertSee('Ajukan sparepart ke pelanggan')
            ->assertSee('Oli mesin');

        $this->patch(route('dashboard.mechanic.queue-notes', $queue), [
            'notes' => 'Pemeriksaan dan servis selesai.',
        ])->assertRedirect();

        $this->post(route('dashboard.mechanic.queue-parts', $queue), [
            'inventory_id' => $inventory->id,
            'quantity' => 1,
            'odometer_km' => 8500,
            'interval_km' => 2000,
            'notes' => 'Ganti oli sesuai spesifikasi.',
        ])->assertRedirect();

        $this->assertDatabaseHas('service_queues', [
            'id' => $queue->id,
            'notes' => 'Pemeriksaan dan servis selesai.',
        ]);
        $this->assertDatabaseHas('service_approval_items', [
            'service_queue_id' => $queue->id,
            'inventory_id' => $inventory->id,
            'requested_by' => $mechanic->id,
            'quantity' => 1,
            'status' => 'pending',
        ]);
        $this->assertSame(3, $inventory->fresh()->stock);
        $this->assertNull($vehicle->fresh()->current_odometer_km);
        $this->assertDatabaseMissing('part_replacements', [
            'service_queue_id' => $queue->id,
            'inventory_id' => $inventory->id,
        ]);
        $approval = ServiceApprovalItem::query()->where('service_queue_id', $queue->id)->firstOrFail();
        $this->actingAs($mechanic)
            ->get(route('dashboard.mechanic', ['status' => 'pending_approval', 'search' => 'B 8888 MEK']))
            ->assertOk()
            ->assertSee('Oli mesin');

        $this->actingAs($customer)
            ->post(route('customer.approvals.respond', $approval), ['status' => 'approved'])
            ->assertRedirect();

        $this->assertDatabaseHas('part_replacements', [
            'service_queue_id' => $queue->id,
            'vehicle_id' => $vehicle->id,
            'inventory_id' => $inventory->id,
            'quantity' => 1,
        ]);
        $this->assertSame(2, $inventory->fresh()->stock);
        $this->assertSame(8500, $vehicle->fresh()->current_odometer_km);
        $this->assertDatabaseHas('service_queues', [
            'id' => $queue->id,
            'odometer_km' => 8500,
        ]);
        $this->assertDatabaseHas('maintenance_records', [
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'part_name' => 'Oli mesin',
            'odometer_km' => 8500,
            'interval_km' => 2000,
        ]);
        $this->post(route('customer.approvals.respond', $approval), ['status' => 'approved'])
            ->assertUnprocessable();
        $this->assertSame(2, $inventory->fresh()->stock);

        $this->actingAs($mechanic)
            ->post(route('dashboard.mechanic.queue-parts', $queue), [
                'inventory_id' => $inventory->id,
                'quantity' => 1,
                'odometer_km' => 8500,
                'interval_km' => 2000,
                'notes' => 'Pengajuan kedua untuk uji penolakan.',
            ])
            ->assertRedirect();
        $rejectedApproval = ServiceApprovalItem::query()->where('service_queue_id', $queue->id)->where('status', 'pending')->firstOrFail();
        $this->actingAs($customer)
            ->post(route('customer.approvals.respond', $rejectedApproval), ['status' => 'rejected'])
            ->assertRedirect();
        $this->assertSame(2, $inventory->fresh()->stock);
        $this->assertSame(1, \App\Models\PartReplacement::query()->where('service_queue_id', $queue->id)->count());

        $this->actingAs($customer)
            ->post(route('dashboard.mechanic.queue-parts', $queue), [
                'inventory_id' => $inventory->id,
                'quantity' => 1,
                'odometer_km' => 9000,
                'interval_km' => 2000,
            ])
            ->assertForbidden();
    }
}
