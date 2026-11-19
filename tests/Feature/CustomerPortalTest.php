<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\Notification;
use App\Models\ServiceApprovalItem;
use App\Models\ServiceHistory;
use App\Models\ServiceQueue;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_render_portal_pages_and_the_sidebar_links_to_them(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

        $this->actingAs($customer)
            ->get(route('customer.bookings'))
            ->assertOk()
            ->assertSee('Booking servis')
            ->assertSee('Pembayaran')
            ->assertSee(route('customer.payments'))
            ->assertSee('Chat dengan bengkel')
            ->assertSee(route('customer.chats'))
            ->assertSee('Sparepart')
            ->assertSee(route('customer.vehicles'));

        $this->get(route('customer.vehicles'))->assertOk()->assertSee('Kendaraan saya');
        $this->get(route('customer.inventory'))->assertOk()->assertSee('Sparepart tersedia');
        $this->get(route('customer.history'))->assertOk()->assertSee('Riwayat servis');
        $this->get(route('customer.notifications'))->assertOk()->assertSee('Notifikasi');
        $this->get(route('customer.chats'))->assertOk()->assertSee('Chat dengan bengkel');
        $this->get(route('customer.approvals'))->assertOk()->assertSee('Persetujuan sparepart');
        $this->get(route('customer.payments'))->assertOk()->assertSee('Pembayaran servis');
    }

    public function test_customer_can_submit_a_transfer_proof_for_their_own_service_history(): void
    {
        Storage::fake('local');
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $vehicle = $this->createVehicle($customer);
        $history = ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis berkala',
            'description' => 'Ganti oli dan cek rem',
            'entered_at' => now()->subHours(3),
            'completed_at' => now(),
            'total_cost' => 250000,
            'payment_status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get(route('customer.payments'))
            ->assertOk()
            ->assertSee('QRIS')
            ->assertSee('DANA')
            ->assertSee('GoPay')
            ->assertSee('Transfer bank')
            ->assertSee('081388796229')
            ->assertSee('Ricki Gilang Saputra')
            ->assertSee('Referensi')
            ->assertSee('91010000000001')
            ->assertSee('bukan nomor tujuan transfer')
            ->assertDontSee('7845201936')
            ->assertDontSee('Virtual Account pelanggan')
            ->assertSee('Bukti pembayaran');

        $this->actingAs($customer)
            ->post(route('customer.payments.submit', $history), [
                'payment_channel' => 'qris',
                'payment_proof' => UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('customer.payments'))
            ->assertSessionHas('status');

        $history->refresh();
        $this->assertSame('transfer', $history->payment_method);
        $this->assertSame('qris', $history->payment_channel);
        $this->assertSame('pending', $history->payment_status);
        $this->assertNotNull($history->payment_submitted_at);
        $this->assertNotNull($history->payment_proof_path);
        Storage::disk('local')->assertExists($history->payment_proof_path);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'payment_update',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.payments.proof', $history))
            ->assertDownload();
    }

    public function test_paid_service_invoices_are_removed_from_payment_page_but_unpaid_invoices_remain(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);

        ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis sudah lunas',
            'description' => 'Nota pembayaran yang sudah lunas.',
            'entered_at' => now()->subDay(),
            'completed_at' => now()->subDay(),
            'total_cost' => 150000,
            'payment_status' => 'paid',
            'payment_method' => 'transfer',
            'payment_channel' => 'ovo',
        ]);
        ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis belum lunas',
            'description' => 'Tagihan yang masih perlu dibayar.',
            'entered_at' => now()->subHours(3),
            'completed_at' => now(),
            'total_cost' => 250000,
            'payment_status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get(route('customer.payments'))
            ->assertOk()
            ->assertSee('Servis belum lunas')
            ->assertSee('Rp250.000')
            ->assertDontSee('Servis sudah lunas')
            ->assertDontSee('Rp150.000');

        $this->get(route('customer.history'))
            ->assertOk()
            ->assertSee('Servis sudah lunas');
    }

    public function test_customer_cannot_submit_payment_for_another_customers_history(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($otherCustomer);
        $history = ServiceHistory::create([
            'user_id' => $otherCustomer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis berkala',
            'description' => 'Ganti oli',
            'entered_at' => now()->subHour(),
            'completed_at' => now(),
            'total_cost' => 100000,
            'payment_status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->post(route('customer.payments.submit', $history), ['payment_channel' => 'cash'])
            ->assertNotFound();

        $this->assertDatabaseHas('service_histories', [
            'id' => $history->id,
            'payment_submitted_at' => null,
        ]);
    }

    public function test_customer_payment_page_shows_automatically_summed_approved_repairs_for_active_service(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Pemeriksaan rem',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now()->subHour(),
        ]);
        ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Kampas rem depan',
            'estimated_cost' => 65000,
            'notes' => 'Sudah disetujui.',
            'status' => 'approved',
            'responded_at' => now(),
        ]);
        ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Barang yang masih menunggu',
            'estimated_cost' => 90000,
            'status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get(route('customer.payments'))
            ->assertOk()
            ->assertSee('Kampas rem depan')
            ->assertSee('Rp65.000')
            ->assertSee('Perkiraan biaya servis')
            ->assertDontSee('Rp155.000')
            ->assertDontSee('Barang yang masih menunggu');
    }

    public function test_approving_repair_after_service_completion_updates_customer_invoice_total(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis berkala',
            'status' => 'completed',
            'priority' => 'medium',
            'entered_at' => now()->subHours(3),
            'completed_at' => now(),
        ]);
        $history = ServiceHistory::create([
            'service_queue_id' => $queue->id,
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis berkala',
            'description' => 'Belum ada rincian',
            'entered_at' => $queue->entered_at,
            'completed_at' => $queue->completed_at,
            'total_cost' => 0,
            'payment_status' => 'pending',
        ]);
        $approval = ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Aki motor',
            'estimated_cost' => 450000,
            'notes' => 'Aki lama sudah lemah.',
            'status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->post(route('customer.approvals.respond', $approval), ['status' => 'approved'])
            ->assertRedirect(route('customer.service.show', $queue));

        $history->refresh();
        $this->assertSame(450000.0, (float) $history->total_cost);
        $this->assertStringContainsString('Aki motor', $history->description);
        $this->assertStringContainsString('Aki lama sudah lemah.', $history->description);
        $this->assertSame(1, ServiceHistory::query()->where('service_queue_id', $queue->id)->count());
    }

    public function test_customer_approval_inbox_lists_only_approvals_for_their_services(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);
        $otherVehicle = $this->createVehicle($otherCustomer);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis rutin',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        $otherQueue = ServiceQueue::create([
            'user_id' => $otherCustomer->id,
            'vehicle_id' => $otherVehicle->id,
            'complaint' => 'Servis milik pelanggan lain',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Kampas rem pelanggan',
            'estimated_cost' => 125000,
            'status' => 'pending',
        ]);
        ServiceApprovalItem::create([
            'service_queue_id' => $otherQueue->id,
            'item_name' => 'Item rahasia pelanggan lain',
            'estimated_cost' => 150000,
            'status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get(route('customer.approvals'))
            ->assertOk()
            ->assertSee('Kampas rem pelanggan')
            ->assertSee(route('customer.service.show', $queue) . '#approvals')
            ->assertDontSee('Item rahasia pelanggan lain');
    }

    public function test_customer_chat_inbox_lists_only_their_own_service_conversations(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);
        $otherVehicle = $this->createVehicle($otherCustomer);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis rutin',
            'status' => 'waiting',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        ServiceQueue::create([
            'user_id' => $otherCustomer->id,
            'vehicle_id' => $otherVehicle->id,
            'complaint' => 'Servis milik pelanggan lain',
            'status' => 'waiting',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);

        $this->actingAs($customer)
            ->get(route('customer.chats'))
            ->assertOk()
            ->assertSee($vehicle->plate_number)
            ->assertSee(route('customer.service.show', $queue) . '#conversation')
            ->assertDontSee($otherVehicle->plate_number);
    }

    public function test_customer_can_submit_a_booking_for_their_own_vehicle(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $vehicle = $this->createVehicle($customer);

        $this->actingAs($customer)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $vehicle->id,
                'preferred_date' => now()->addDay()->toDateString(),
                'preferred_time' => '09:00',
                'service_type' => 'Servis berkala',
                'complaint' => 'Servis rutin dan pemeriksaan rem',
            ])
            ->assertRedirect(route('customer.bookings'));

        $this->assertDatabaseHas('booking_requests', [
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'booking_update',
        ]);
    }

    public function test_customer_cannot_book_or_edit_another_customers_vehicle(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($otherCustomer);

        $this->actingAs($customer)
            ->post(route('customer.bookings.store'), [
                'vehicle_id' => $vehicle->id,
                'preferred_date' => now()->addDay()->toDateString(),
                'preferred_time' => '09:00',
                'service_type' => 'Servis berkala',
                'complaint' => 'Servis rutin',
            ])
            ->assertNotFound();

        $this->put(route('customer.vehicles.update', $vehicle), [
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => now()->year,
            'color' => 'Hitam',
            'plate_number' => 'B 1234 TEST',
        ])->assertNotFound();

        $this->assertDatabaseMissing('booking_requests', ['user_id' => $customer->id]);
    }

    public function test_customer_can_track_odometer_and_part_replacement_due_kilometers(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);

        $this->actingAs($customer)
            ->get(route('customer.vehicles'))
            ->assertOk()
            ->assertSee('Kilometer saat ini')
            ->assertSee('Catat penggantian komponen')
            ->assertSee('Ingatkan lagi setelah (km)')
            ->assertSee('Patokan umum 2.000-3.000 km');

        $this->put(route('customer.vehicles.odometer', $vehicle), [
            'current_odometer_km' => 5000,
        ])->assertRedirect(route('customer.vehicles'));

        $this->post(route('customer.vehicles.maintenance.store', $vehicle), [
            'part_name' => 'Oli mesin',
            'replaced_at' => now()->toDateString(),
            'odometer_km' => 5000,
        ])->assertSessionHasErrors('interval_km');

        $this->post(route('customer.vehicles.maintenance.store', $vehicle), [
            'part_name' => 'Oli mesin',
            'replaced_at' => now()->toDateString(),
            'odometer_km' => 5000,
            'interval_km' => 2000,
            'notes' => 'Oli sesuai rekomendasi bengkel',
        ])->assertRedirect(route('customer.vehicles'));

        $this->assertDatabaseHas('maintenance_records', [
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'part_name' => 'Oli mesin',
            'odometer_km' => 5000,
            'interval_km' => 2000,
        ]);
        $this->assertSame(5000, $vehicle->fresh()->current_odometer_km);

        $this->get(route('customer.vehicles'))
            ->assertOk()
            ->assertSee('Oli mesin')
            ->assertSee('Ganti oli mesin pada 7.000 km')
            ->assertSee('sisa 2.000 km');

        $this->put(route('customer.vehicles.odometer', $vehicle), [
            'current_odometer_km' => 7000,
        ])->assertRedirect(route('customer.vehicles'));

        $this->get(route('customer.vehicles'))
            ->assertOk()
            ->assertSee('Sudah mencapai jadwal ganti oli mesin');
    }

    public function test_customer_cannot_lower_odometer_or_add_maintenance_for_another_customers_vehicle(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);
        $vehicle->update(['current_odometer_km' => 12000]);

        $this->actingAs($customer)
            ->put(route('customer.vehicles.odometer', $vehicle), [
                'current_odometer_km' => 11000,
            ])
            ->assertSessionHasErrors('current_odometer_km');

        $this->actingAs($otherCustomer)
            ->post(route('customer.vehicles.maintenance.store', $vehicle), [
                'part_name' => 'Oli mesin',
                'replaced_at' => now()->toDateString(),
                'odometer_km' => 12000,
                'interval_km' => 2000,
            ])
            ->assertNotFound();

        $this->assertDatabaseCount('maintenance_records', 0);
    }

    public function test_clicking_customer_approval_notification_opens_service_and_can_approve_item(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $vehicle = $this->createVehicle($customer);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis berkala',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        $approval = ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Kampas rem depan',
            'estimated_cost' => 125000,
            'notes' => 'Kampas sudah tipis dan perlu diganti.',
            'status' => 'pending',
        ]);
        $notification = Notification::create([
            'user_id' => $customer->id,
            'title' => 'Persetujuan penggantian barang',
            'message' => 'Mohon tinjau permintaan.',
            'type' => 'approval_request',
            'link' => "/pelanggan/servis/{$queue->id}",
            'is_read' => false,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.notifications.open', $notification))
            ->assertRedirect(route('customer.service.show', $queue));
        $this->get(route('customer.service.show', $queue))
            ->assertOk()
            ->assertSee('Kampas rem depan')
            ->assertSee('Setujui')
            ->assertSee('Tolak')
            ->assertSee('Kirim pesan ke Bengkel AR')
            ->assertSee("api/chats/{$queue->id}");
        $this->post(route('customer.approvals.respond', $approval), ['status' => 'approved'])
            ->assertRedirect(route('customer.service.show', $queue))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('service_approval_items', ['id' => $approval->id, 'status' => 'approved']);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'id' => $notification->id,
            'is_read' => true,
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'approval_response',
            'link' => "/admin/antrian/{$queue->id}",
        ]);
    }

    public function test_old_approval_notification_links_still_open_the_matching_service(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis berkala',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Oli Shell Advance',
            'estimated_cost' => 62000,
            'status' => 'pending',
        ]);
        $notification = Notification::create([
            'user_id' => $customer->id,
            'title' => 'Persetujuan penggantian barang',
            'message' => 'Mekanik mengajukan penggantian/perbaikan: Oli Shell Advance (Rp 62.000). Silakan konfirmasi.',
            'type' => 'approval_request',
            'link' => '/dashboard',
            'is_read' => false,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.notifications.open', $notification))
            ->assertRedirect(route('customer.service.show', $queue));
    }

    public function test_customer_cannot_view_or_respond_to_another_customers_approval(): void
    {
        $owner = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($owner);
        $queue = ServiceQueue::create([
            'user_id' => $owner->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis berkala',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        $approval = ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Aki',
            'estimated_cost' => 250000,
            'status' => 'pending',
        ]);

        $this->actingAs($otherCustomer)
            ->get(route('customer.service.show', $queue))
            ->assertNotFound();
        $this->post(route('customer.approvals.respond', $approval), ['status' => 'approved'])
            ->assertForbidden();
        $this->assertDatabaseHas('service_approval_items', ['id' => $approval->id, 'status' => 'pending']);
    }

    public function test_customer_clicking_another_users_notification_cannot_open_it(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $otherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $notification = Notification::create([
            'user_id' => $otherCustomer->id,
            'title' => 'Private notification',
            'message' => 'Private message',
            'type' => 'approval_request',
            'link' => '/pelanggan/dashboard',
            'is_read' => false,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.notifications.open', $notification))
            ->assertNotFound();
    }

    public function test_customer_booking_routes_are_not_available_to_other_roles(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $mechanic = User::factory()->create(['role' => User::ROLE_MECHANIC]);

        $this->actingAs($admin)->get(route('customer.bookings'))->assertForbidden();
        $this->actingAs($mechanic)->get(route('customer.vehicles'))->assertForbidden();
        $this->actingAs($mechanic)->get(route('admin.queue.index'))->assertForbidden();
    }

    public function test_accepting_a_booking_more_than_once_creates_only_one_queue_item(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = $this->createVehicle($customer);
        $booking = BookingRequest::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '09:00',
            'service_type' => 'Servis berkala',
            'complaint' => 'Servis rutin',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.booking.accept', $booking))
            ->assertSessionHas('success');
        $this->post(route('admin.booking.accept', $booking))
            ->assertSessionHas('error');

        $this->assertSame(1, ServiceQueue::query()->where('vehicle_id', $vehicle->id)->count());
        $this->assertDatabaseHas('booking_requests', ['id' => $booking->id, 'status' => 'accepted']);
    }

    private function createVehicle(User $owner): Vehicle
    {
        return Vehicle::create([
            'user_id' => $owner->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => now()->year,
            'color' => 'Hitam',
            'plate_number' => 'B ' . $owner->id . ' TEST',
        ]);
    }
}
