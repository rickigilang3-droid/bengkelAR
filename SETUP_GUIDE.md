# Setup & Installation Guide - Admin Dashboard

## 1. Database Setup

### Run Migrations

```bash
php artisan migrate
```

Ini akan membuat tabel berikut:
- `vehicles` - Data kendaraan pelanggan
- `service_queues` - Antrean servis kendaraan
- `service_histories` - Riwayat servis yang telah selesai
- `reminders` - Pengingat servis untuk pelanggan

### Run Seeders (Optional - untuk test data)

```bash
php artisan db:seed --class=AdminDatabaseSeeder
```

Ini akan membuat data test:
- **1 Admin user**: email: `ricki@gmail.com`, password: `rigskind`, role: `admin`
- **1 Customer user**: email: `amel@gmail.com`, password: `rigskind`, role: `customer`
- **3 Kendaraan motor** milik pelanggan
- **2 Service Queue** (antrean servis)
- **2 Service History** (riwayat servis)
- **2 Reminders** (pengingat servis)

---

## 2. User Model Update

User model sudah di-update dengan relationships berikut:

```php
$user->vehicles();           // Semua kendaraan user
$user->serviceQueues();      // Semua antrean servis user
$user->serviceHistories();   // Semua riwayat servis user
$user->reminders();          // Semua pengingat user
```

---

## 3. Testing Routes

### Login sebagai Admin

1. Kunjungi `/login`
2. Masukkan email: `ricki@gmail.com`
3. Password: `rigskind`
4. Klik Login

### Akses Admin Dashboard

Setelah login, pilih salah satu menu admin:

- **Service Queue** - `/admin/antrian`
- **Customers** - `/admin/customers`
- **Service History** - `/admin/history`
- **Reminders** - `/admin/reminders`

---

## 4. Models & Relationships

### Vehicle Model
```php
public function user()              // Pemilik kendaraan
public function serviceQueues()     // Antrean servis
public function serviceHistories()  // Riwayat servis
public function reminders()         // Pengingat
```

### ServiceQueue Model
```php
public function user()              // Pemilik/Pelanggan
public function vehicle()           // Kendaraan yang diservis
```

### ServiceHistory Model
```php
public function user()              // Pelanggan
public function vehicle()           // Kendaraan
```

### Reminder Model
```php
public function user()              // Pelanggan yang diingatkan
public function vehicle()           // Kendaraan (optional)
```

---

## 5. Controllers & Actions

### AdminQueueController
- `index()` - Lihat daftar antrean
- `show($id)` - Lihat detail antrean
- `updateStatus($id)` - Update status & prioritas antrean

**Status**: waiting, in_progress, completed, cancelled
**Priority**: low, medium, high

### AdminCustomersController
- `index()` - Lihat daftar pelanggan dengan search & filter
- `show($id)` - Lihat detail pelanggan + kendaraannya
- `update($id)` - Update data pelanggan
- `destroy($id)` - Hapus pelanggan

### AdminHistoryController
- `index()` - Lihat riwayat servis dengan filter
- `show($id)` - Lihat detail riwayat servis
- `export()` - Export ke CSV/PDF
- `statistics()` - API untuk statistik

### AdminRemindersController
- `index()` - Lihat daftar pengingat
- `create()` - Form buat pengingat
- `store()` - Simpan pengingat baru
- `edit($id)` - Form edit pengingat
- `update($id)` - Update pengingat
- `destroy($id)` - Hapus pengingat
- `sendNotification($id)` - Kirim notifikasi ke pelanggan

---

## 6. API Endpoints

### Statistics
```
GET /admin/history-stats
```

Response:
```json
{
  "total_services": 10,
  "total_revenue": 5000000,
  "average_duration": 4.5,
  "completion_rate": 100,
  "currency": "IDR"
}
```

### Export History
```
GET /admin/history/export?format=csv
GET /admin/history/export?format=pdf&start_date=2026-05-01&end_date=2026-05-31
```

---

## 7. Middleware & Security

### AdminMiddleware
Melindungi semua route `/admin/*` dengan:
1. User harus authenticated (`auth` middleware)
2. User harus memiliki `role = 'admin'` (custom `admin` middleware)

```php
// Automatik di-check untuk semua admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // admin routes
});
```

---

## 8. Database Schema

### vehicles
```sql
CREATE TABLE vehicles (
  id BIGINT PRIMARY KEY,
  user_id BIGINT FOREIGN KEY,
  vehicle_type VARCHAR (Mobil, Motor, Truk),
  brand VARCHAR,
  model VARCHAR,
  year INTEGER,
  color VARCHAR,
  plate_number VARCHAR UNIQUE,
  engine_number VARCHAR,
  chassis_number VARCHAR,
  description TEXT,
  timestamps
)
```

### service_queues
```sql
CREATE TABLE service_queues (
  id BIGINT PRIMARY KEY,
  user_id BIGINT FOREIGN KEY,
  vehicle_id BIGINT FOREIGN KEY,
  complaint TEXT,
  status ENUM (waiting, in_progress, completed, cancelled),
  priority ENUM (low, medium, high),
  entered_at DATETIME,
  estimated_completion_at DATETIME,
  completed_at DATETIME,
  notes TEXT,
  timestamps
)
```

### service_histories
```sql
CREATE TABLE service_histories (
  id BIGINT PRIMARY KEY,
  user_id BIGINT FOREIGN KEY,
  vehicle_id BIGINT FOREIGN KEY,
  service_type VARCHAR,
  description TEXT,
  entered_at DATETIME,
  completed_at DATETIME,
  total_cost DECIMAL(12,2),
  payment_method ENUM (cash, transfer, card),
  payment_status ENUM (pending, paid, failed),
  rating INTEGER (1-5),
  notes TEXT,
  timestamps
)
```

### reminders
```sql
CREATE TABLE reminders (
  id BIGINT PRIMARY KEY,
  user_id BIGINT FOREIGN KEY,
  vehicle_id BIGINT FOREIGN KEY (nullable),
  type ENUM (service, maintenance, inspection),
  title VARCHAR,
  description TEXT,
  reminder_date DATE,
  notify_days_before INTEGER,
  is_sent BOOLEAN,
  status ENUM (active, completed, cancelled),
  timestamps
)
```

---

## 9. Next Steps / TODO

### Belum Diimplementasi:
- [ ] Notifikasi email/SMS untuk reminders
- [ ] Export PDF untuk history
- [ ] Add photo/attachment untuk service queue
- [ ] Generate invoice/receipt
- [ ] Customer portal (untuk customer lihat riwayat sendiri)
- [ ] Dashboard analytics & charts
- [ ] Print service queue
- [ ] Backup/restore database

### Fitur yang Bisa Ditambah:
- [ ] Multi-user admin dengan role berbeda
- [ ] Approval workflow untuk queue
- [ ] Payment integration
- [ ] Customer rating & review
- [ ] Performance report untuk teknisi
- [ ] Inventory management untuk spare parts
- [ ] Asset tracking
- [ ] Mobile app untuk pelanggan

---

## 10. Common Issues & Troubleshooting

### "Class not found" error
**Solution**: Pastikan migration sudah dijalankan
```bash
php artisan migrate
```

### Admin middleware tidak berjalan
**Solution**: Pastikan user memiliki `role = 'admin'` di database
```bash
php artisan tinker
$user = User::find(1);
$user->update(['role' => 'admin']);
```

### Data tidak muncul di table
**Solution**: Jalankan seeder untuk membuat test data
```bash
php artisan db:seed --class=AdminDatabaseSeeder
```

### Pagination error
**Solution**: Pastikan menggunakan `paginate()` di controller dan menampilkan `{{ $items->links() }}` di view

---

## 11. Quick Commands

```bash
# Setup everything
php artisan migrate
php artisan db:seed --class=AdminDatabaseSeeder

# Fresh setup (HATI-HATI: delete all data)
php artisan migrate:fresh --seed --seeder=AdminDatabaseSeeder

# Clear cache
php artisan cache:clear

# View routes
php artisan route:list

# Check migrations
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback
```

---

**Last Updated**: May 17, 2026  
**Status**: Ready for Testing
