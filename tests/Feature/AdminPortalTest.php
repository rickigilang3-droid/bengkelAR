<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Notification;
use App\Models\ServiceQueue;
use App\Models\ServiceApprovalItem;
use App\Models\ServiceHistory;
use Illuminate\Support\Facades\Hash;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sidebar_destinations_are_available_to_admins(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);

        foreach ([
            'dashboard.admin',
            'admin.queue.index',
            'admin.chats',
            'admin.approvals',
            'admin.payments',
            'admin.customers.index',
            'admin.mechanics.index',
            'admin.history.index',
            'admin.reminders.index',
            'admin.inventory.index',
            'admin.notifications.index',
        ] as $routeName) {
            $this->get(route($routeName))->assertOk();
        }
    }

    public function test_admin_can_create_a_mechanic_account(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)
            ->post(route('admin.mechanics.store'), [
                'name' => 'Mekanik Baru',
                'email' => 'mekanik.baru@example.com',
                'phone' => '081234567890',
                'password' => 'KataSandiAman123',
                'password_confirmation' => 'KataSandiAman123',
            ])
            ->assertRedirect(route('admin.mechanics.index'))
            ->assertSessionHas('success');

        $mechanic = User::query()->where('email', 'mekanik.baru@example.com')->firstOrFail();
        $this->assertSame(User::ROLE_MECHANIC, $mechanic->role);
        $this->assertTrue($mechanic->hasVerifiedEmail());
        $this->assertTrue(Hash::check('KataSandiAman123', $mechanic->password));
    }

    public function test_admin_dashboard_shows_only_paid_revenue_for_the_current_month(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2024,
            'color' => 'Hitam',
            'plate_number' => 'B 1234 REV',
        ]);

        ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis berkala',
            'description' => 'Tagihan lunas',
            'entered_at' => now()->subDay(),
            'completed_at' => now()->subDay(),
            'total_cost' => 250000,
            'payment_status' => 'paid',
        ]);
        ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis berikutnya',
            'description' => 'Belum lunas',
            'entered_at' => now(),
            'completed_at' => now(),
            'total_cost' => 500000,
            'payment_status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard.admin'))
            ->assertOk()
            ->assertSee('Pendapatan bulan ini')
            ->assertSee('Rp250.000')
            ->assertSee(route('admin.history.index'));
    }

    public function test_admin_can_verify_customer_payment_and_customer_is_notified(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => now()->year,
            'color' => 'Hitam',
            'plate_number' => 'B 4455 PAY',
        ]);
        $history = ServiceHistory::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type' => 'Servis berkala',
            'description' => 'Ganti oli',
            'entered_at' => now()->subHours(2),
            'completed_at' => now(),
            'total_cost' => 200000,
            'payment_method' => 'transfer',
            'payment_status' => 'pending',
            'payment_submitted_at' => now(),
        ]);

        $this->actingAs($admin)
            ->put(route('admin.payments.update', $history), [
                'total_cost' => 200000,
                'payment_channel' => 'bank_transfer',
                'payment_status' => 'paid',
            ])
            ->assertRedirect(route('admin.payments'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_histories', [
            'id' => $history->id,
            'payment_status' => 'paid',
            'payment_channel' => 'bank_transfer',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'title' => 'Pembayaran terverifikasi',
            'link' => '/pelanggan/pembayaran',
        ]);
    }

    public function test_admin_approval_inbox_links_to_the_related_service(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER, 'name' => 'Pelanggan Uji']);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => now()->year,
            'color' => 'Hitam',
            'plate_number' => 'B 4455 APPROVAL',
        ]);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Pemeriksaan servis',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'Kampas rem',
            'estimated_cost' => 125000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.approvals'))
            ->assertOk()
            ->assertSee('Kampas rem')
            ->assertSee('Pelanggan Uji')
            ->assertSee(route('admin.queue.show', $queue->id) . '#approvals');
    }

    public function test_customer_management_lists_customers_but_not_mechanics(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        User::factory()->create(['name' => 'Pelanggan Terdaftar', 'role' => User::ROLE_CUSTOMER]);
        User::factory()->create(['name' => 'Mekanik Internal', 'role' => User::ROLE_MECHANIC]);

        $this->actingAs($admin)
            ->get(route('admin.customers.index'))
            ->assertOk()
            ->assertSee('Pelanggan Terdaftar')
            ->assertDontSee('Mekanik Internal');
    }

    public function test_admin_chat_inbox_links_to_the_service_conversation(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER, 'name' => 'Pemilik Servis']);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => now()->year,
            'color' => 'Hitam',
            'plate_number' => 'B 4455 CHAT',
        ]);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Pemeriksaan servis',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.chats'))
            ->assertOk()
            ->assertSee('Pemilik Servis')
            ->assertSee(route('admin.queue.show', $queue->id) . '#chat');
    }

    public function test_admin_can_open_notification_directly_to_related_service_detail(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => now()->year,
            'color' => 'Hitam',
            'plate_number' => 'B 4455 ADMIN',
        ]);
        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Pemeriksaan servis',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);
        $notification = Notification::create([
            'user_id' => $admin->id,
            'title' => 'Pelanggan menyetujui perbaikan',
            'message' => 'Kampas rem disetujui.',
            'type' => 'approval_response',
            'link' => "/admin/antrian/{$queue->id}",
            'is_read' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.notifications.open', $notification))
            ->assertRedirect("/admin/antrian/{$queue->id}");

        $this->get("/admin/antrian/{$queue->id}")
            ->assertOk()
            ->assertSee('Detail Antrean');
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'user_id' => $admin->id,
            'is_read' => true,
        ]);
    }
}
