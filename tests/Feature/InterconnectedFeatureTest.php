<?php

namespace Tests\Feature;

use App\Models\ServiceApprovalItem;
use App\Models\ServiceQueue;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterconnectedFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_fetch_notifications()
    {
        $user = User::factory()->create(['role' => 'pelanggan']);
        $this->actingAs($user);

        $response = $this->getJson('/api/notifications');

        $response->assertStatus(200)
                 ->assertJsonStructure(['unread_count', 'notifications']);
    }

    public function test_user_can_respond_to_sparepart_approval()
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);
        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario',
            'year' => 2021,
            'color' => 'Hitam',
            'plate_number' => 'B 1111 AAA',
        ]);

        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Servis CVT',
            'status' => 'in_progress',
            'priority' => 'medium',
            'entered_at' => now(),
        ]);

        $item = ServiceApprovalItem::create([
            'service_queue_id' => $queue->id,
            'item_name' => 'V-Belt Gates',
            'estimated_cost' => 120000,
            'notes' => 'Retak-retak',
            'status' => 'pending',
        ]);

        $this->actingAs($customer);

        $response = $this->postJson("/api/approvals/{$item->id}/respond", [
            'status' => 'approved',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('service_approval_items', [
            'id' => $item->id,
            'status' => 'approved',
        ]);
    }

    public function test_user_can_send_chat_message()
    {
        $customer = User::factory()->create(['role' => 'pelanggan']);
        $admin = User::factory()->create(['role' => 'admin']);

        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2022,
            'color' => 'Biru',
            'plate_number' => 'B 2222 BBB',
        ]);

        $queue = ServiceQueue::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Oli bocor',
            'status' => 'in_progress',
            'priority' => 'high',
            'entered_at' => now(),
        ]);

        $this->actingAs($customer);

        $response = $this->postJson("/api/chats/{$queue->id}", [
            'message' => 'Halo mas mekanik, pengerjaan jam berapa selesainya?',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('service_chats', [
            'service_queue_id' => $queue->id,
            'sender_id' => $customer->id,
            'message' => 'Halo mas mekanik, pengerjaan jam berapa selesainya?',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'chat',
            'link' => "/admin/antrian/{$queue->id}",
        ]);

        $notification = \App\Models\Notification::query()
            ->where('user_id', $admin->id)
            ->where('type', 'chat')
            ->firstOrFail();
        $this->actingAs($admin)
            ->get(route('admin.notifications.open', $notification))
            ->assertRedirect("/admin/antrian/{$queue->id}");
    }

    public function test_customer_cannot_read_or_send_chat_for_another_customers_queue()
    {
        $owner = User::factory()->create(['role' => 'pelanggan']);
        $otherCustomer = User::factory()->create(['role' => 'pelanggan']);
        $vehicle = Vehicle::create([
            'user_id' => $owner->id,
            'vehicle_type' => 'Motor',
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'year' => 2022,
            'color' => 'Biru',
            'plate_number' => 'B 3333 CCC',
        ]);
        $queue = ServiceQueue::create([
            'user_id' => $owner->id,
            'vehicle_id' => $vehicle->id,
            'complaint' => 'Oli bocor',
            'status' => 'in_progress',
            'priority' => 'high',
            'entered_at' => now(),
        ]);

        $this->actingAs($otherCustomer)
            ->getJson("/api/chats/{$queue->id}")
            ->assertForbidden();
        $this->postJson("/api/chats/{$queue->id}", ['message' => 'Pesan tidak berhak'])
            ->assertForbidden();
    }
}
