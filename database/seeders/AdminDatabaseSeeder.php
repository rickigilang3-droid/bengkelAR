<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\ServiceQueue;
use App\Models\ServiceHistory;
use App\Models\Reminder;
use Illuminate\Database\Seeder;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'ricki@gmail.com'],
            [
                'name' => 'Ricki',
                'password' => bcrypt('rigskind'),
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1',
                'role' => 'admin',
            ]
        );

        // Create customer user
        $customers = [
            [
                'name' => 'Amel',
                'email' => 'amel@gmail.com',
                'phone' => '081987654321',
                'address' => 'Jl. Jalan Motor No. 10, Jakarta',
                'role' => 'customer',
            ],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $customerData['password'] = bcrypt('rigskind');
            $customer = User::firstOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );
            $createdCustomers[] = $customer;
        }

        // Create vehicles for Amel
        $vehicleData = [
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_type' => 'Motor',
                'brand' => 'Honda',
                'model' => 'Beat',
                'year' => 2022,
                'color' => 'Merah',
                'plate_number' => 'B 1234 AM',
                'engine_number' => 'E1234AM',
                'chassis_number' => 'C1234AM',
            ],
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_type' => 'Motor',
                'brand' => 'Yamaha',
                'model' => 'NMAX',
                'year' => 2023,
                'color' => 'Hitam',
                'plate_number' => 'B 5678 AM',
                'engine_number' => 'E5678AM',
                'chassis_number' => 'C5678AM',
            ],
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_type' => 'Motor',
                'brand' => 'Suzuki',
                'model' => 'Satria',
                'year' => 2021,
                'color' => 'Biru',
                'plate_number' => 'B 9012 AM',
                'engine_number' => 'E9012AM',
                'chassis_number' => 'C9012AM',
            ],
        ];

        $createdVehicles = [];
        foreach ($vehicleData as $data) {
            $vehicle = Vehicle::firstOrCreate(
                ['plate_number' => $data['plate_number']],
                $data
            );
            $createdVehicles[] = $vehicle;
        }

        // Create service queues
        $queueData = [
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_id' => $createdVehicles[0]->id,
                'complaint' => 'Servis rutin ganti oli dan tune up motor',
                'status' => 'waiting',
                'priority' => 'high',
                'entered_at' => now()->subHours(2),
                'estimated_completion_at' => now()->addHours(2),
            ],
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_id' => $createdVehicles[1]->id,
                'complaint' => 'Perbaikan rem belakang serta pengecekan suspensi',
                'status' => 'in_progress',
                'priority' => 'medium',
                'entered_at' => now()->subHours(1),
                'estimated_completion_at' => now()->addHours(3),
            ],
        ];

        foreach ($queueData as $data) {
            ServiceQueue::create($data);
        }

        // Create service history
        $historyData = [
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_id' => $createdVehicles[0]->id,
                'service_type' => 'Ganti Oli Motor',
                'description' => 'Penggantian oli mesin, pembersihan karburator, dan pengecekan kelistrikan',
                'entered_at' => now()->subDays(5)->setHour(10),
                'completed_at' => now()->subDays(5)->setHour(12),
                'total_cost' => 175000,
                'payment_method' => 'transfer',
                'payment_status' => 'paid',
                'rating' => 5,
            ],
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_id' => $createdVehicles[2]->id,
                'service_type' => 'Servis Rutin Satria',
                'description' => 'Ganti oli, setel karburator, dan pengecekan rem',
                'entered_at' => now()->subDays(3)->setHour(9),
                'completed_at' => now()->subDays(3)->setHour(11),
                'total_cost' => 220000,
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'rating' => 5,
            ],
        ];

        foreach ($historyData as $data) {
            ServiceHistory::create($data);
        }

        // Create reminders
        $reminderData = [
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_id' => $createdVehicles[0]->id,
                'type' => 'service',
                'title' => 'Pengingat Servis Honda Beat',
                'description' => 'Pengingat servis berkala untuk motor Honda Beat.',
                'reminder_date' => now()->addDays(7),
                'notify_days_before' => 3,
                'is_sent' => false,
                'status' => 'active',
            ],
            [
                'user_id' => $createdCustomers[0]->id,
                'vehicle_id' => $createdVehicles[2]->id,
                'type' => 'maintenance',
                'title' => 'Pengingat Maintenance Suzuki Satria',
                'description' => 'Pengingat untuk cek rem dan rantai.',
                'reminder_date' => now()->addDays(14),
                'notify_days_before' => 5,
                'is_sent' => false,
                'status' => 'active',
            ],
        ];

        foreach ($reminderData as $data) {
            Reminder::create($data);
        }

        $this->command->info('Admin database seeded successfully!');
    }
}
