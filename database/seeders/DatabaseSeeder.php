<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'ricki@gmail.com'],
            [
                'name' => 'Ricki (Admin)',
                'password' => bcrypt('rigskind'),
                'role' => 'admin',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'amel@gmail.com'],
            [
                'name' => 'Amel (Pelanggan)',
                'password' => bcrypt('rigskind'),
                'role' => 'pelanggan',
            ]
        );

        $vehicle = \App\Models\Vehicle::firstOrCreate(
            ['user_id' => $customer->id, 'plate_number' => 'B 3829 WXR'],
            [
                'vehicle_type' => 'Motor',
                'brand' => 'Honda',
                'model' => 'Vario 150 Esp',
                'year' => 2022,
                'color' => 'Hitam Matte',
                'description' => 'Servis rutin berkala',
            ]
        );

        $beat = \App\Models\Vehicle::firstOrCreate(
            ['user_id' => $customer->id, 'plate_number' => 'B 4567 BET'],
            [
                'vehicle_type' => 'Motor',
                'brand' => 'Honda',
                'model' => 'Beat Deluxe eSP 110cc',
                'year' => 2023,
                'color' => 'Silver Matte',
                'description' => 'Motor Beat Kesayangan Harian',
            ]
        );

        $queue = \App\Models\ServiceQueue::firstOrCreate(
            ['user_id' => $customer->id, 'vehicle_id' => $vehicle->id, 'status' => 'in_progress'],
            [
                'complaint' => 'Suara mesin agak kasar di CVT & rem depan kurang pakem',
                'priority' => 'high',
                'entered_at' => now()->subMinutes(45),
                'estimated_completion_at' => now()->addHours(1),
                'notes' => 'Mekanik sedang membongkar CVT untuk pembersihan dan penggantian v-belt.',
            ]
        );

        $vehicle2 = \App\Models\Vehicle::firstOrCreate(
            ['user_id' => $customer->id, 'plate_number' => 'B 1234 BKI'],
            [
                'vehicle_type' => 'Motor',
                'brand' => 'Yamaha',
                'model' => 'NMAX 155 ABS',
                'year' => 2023,
                'color' => 'Biru Matte',
                'description' => 'Motor harian kantor',
            ]
        );

        // Seed Sample Booking Requests (Hari Ini & Besok)
        \App\Models\BookingRequest::firstOrCreate(
            ['user_id' => $customer->id, 'preferred_date' => date('Y-m-d'), 'preferred_time' => '09:00 WIB'],
            [
                'vehicle_id' => $vehicle2->id,
                'service_type' => 'Servis Berkala & Ganti Oli',
                'complaint' => 'Tarikan awal CVT agak gredek dan rem belakang kurang pakem.',
                'status' => 'pending',
            ]
        );

        \App\Models\BookingRequest::firstOrCreate(
            ['user_id' => $customer->id, 'preferred_date' => date('Y-m-d', strtotime('+1 day')), 'preferred_time' => '10:00 WIB'],
            [
                'vehicle_id' => $vehicle->id,
                'service_type' => 'Ganti Oli & Filter',
                'complaint' => 'Ganti oli rutin mesin & gardan + pembersihan filter udara.',
                'status' => 'pending',
            ]
        );

        \App\Models\BookingRequest::firstOrCreate(
            ['user_id' => $customer->id, 'preferred_date' => date('Y-m-d', strtotime('+2 days')), 'preferred_time' => '13:00 WIB'],
            [
                'vehicle_id' => $vehicle2->id,
                'service_type' => 'Perbaikan Kelistrikan',
                'complaint' => 'Aki agak tekor kalau starter pagi hari.',
                'status' => 'pending',
            ]
        );

        \App\Models\ServiceApprovalItem::firstOrCreate(
            ['service_queue_id' => $queue->id, 'item_name' => 'Kampas Rem Depan Original Honda'],
            [
                'estimated_cost' => 65000,
                'notes' => 'Kampas rem aus parah (sisa 10%), berisiko mengikis piringan cakram.',
                'status' => 'pending',
            ]
        );

        \App\Models\ServiceApprovalItem::firstOrCreate(
            ['service_queue_id' => $queue->id, 'item_name' => 'Roller Set & Slider CVT'],
            [
                'estimated_cost' => 85000,
                'notes' => 'Roller sudah tergerus pipih menyebabkan getaran pada tarikan awal.',
                'status' => 'pending',
            ]
        );

        \App\Models\Notification::firstOrCreate(
            ['user_id' => $customer->id, 'title' => 'Permintaan Persetujuan Sparepart'],
            [
                'message' => 'Mekanik memerlukan konfirmasi Anda untuk pergantian Kampas Rem Depan & Roller Set CVT.',
                'type' => 'approval_request',
                'link' => '/dashboard',
                'is_read' => false,
            ]
        );

        \App\Models\ServiceChat::firstOrCreate(
            ['service_queue_id' => $queue->id, 'message' => 'Halo Kak Amel, motor Vario sudah kami bongkar. Kampas rem depan terpantau sudah tipis banget ya.'],
            [
                'sender_id' => $admin->id,
                'sender_type' => 'admin',
                'is_read' => false,
            ]
        );

        $this->call(SparePartSeeder::class);
    }
}
