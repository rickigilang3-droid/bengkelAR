# Admin Dashboard Routes Documentation

## Overview
Sistem routing untuk fitur admin dashboard telah diatur dengan struktur yang terorganisir menggunakan controllers dan middleware.

## Route Structure

### Admin Routes Prefix
Semua route admin menggunakan prefix `/admin` dan memerlukan middleware `auth` dan `admin`.

Contoh URL: `http://localhost/admin/antrian`, `http://localhost/admin/customers`, dll.

---

## Fitur & Routes

### 1. SERVICE QUEUE (Antrean Servis)
**Path:** `/admin/antrian`

| Method | Route | Controller Method | Nama Route | Keterangan |
|--------|-------|------------------|-----------|-----------|
| GET | `/admin/antrian` | `AdminQueueController@index` | `admin.queue.index` | Lihat daftar antrean |
| GET | `/admin/antrian/{id}` | `AdminQueueController@show` | `admin.queue.show` | Lihat detail antrean |
| PUT | `/admin/antrian/{id}/status` | `AdminQueueController@updateStatus` | `admin.queue.updateStatus` | Update status antrean |

**Fitur:**
- Daftar kendaraan yang sedang/akan diservis
- Update status (Menunggu, Sedang Dikerjakan, Selesai, Dibatalkan)
- Atur prioritas (Rendah, Sedang, Tinggi)
- Statistik antrean (total menunggu, sedang dikerjakan, selesai hari ini, dibatalkan)

**View Files:**
- `resources/views/dashboard/admin/queue/index.blade.php` - Daftar antrean
- `resources/views/dashboard/admin/queue/show.blade.php` - Detail antrean

---

### 2. CUSTOMERS (Manajemen Pelanggan)
**Path:** `/admin/customers`

| Method | Route | Controller Method | Nama Route | Keterangan |
|--------|-------|------------------|-----------|-----------|
| GET | `/admin/customers` | `AdminCustomersController@index` | `admin.customers.index` | Lihat daftar pelanggan |
| GET | `/admin/customers/{id}` | `AdminCustomersController@show` | `admin.customers.show` | Lihat detail pelanggan |
| PUT | `/admin/customers/{id}` | `AdminCustomersController@update` | `admin.customers.update` | Update data pelanggan |
| DELETE | `/admin/customers/{id}` | `AdminCustomersController@destroy` | `admin.customers.destroy` | Hapus pelanggan |

**Fitur:**
- Daftar semua pelanggan
- Edit data pelanggan (nama, telepon, email, alamat)
- Lihat riwayat kendaraan pelanggan
- Hapus data pelanggan
- Filter & pencarian pelanggan

**View Files:**
- `resources/views/dashboard/admin/customers/index.blade.php` - Daftar pelanggan
- `resources/views/dashboard/admin/customers/show.blade.php` - Detail & edit pelanggan

---

### 3. SERVICE HISTORY (Riwayat Servis)
**Path:** `/admin/history`

| Method | Route | Controller Method | Nama Route | Keterangan |
|--------|-------|------------------|-----------|-----------|
| GET | `/admin/history` | `AdminHistoryController@index` | `admin.history.index` | Lihat riwayat servis |
| GET | `/admin/history/{id}` | `AdminHistoryController@show` | `admin.history.show` | Lihat detail servis |
| GET | `/admin/history/export` | `AdminHistoryController@export` | `admin.history.export` | Export laporan (PDF/Excel) |
| GET | `/admin/history-stats` | `AdminHistoryController@statistics` | `admin.history.stats` | Dapatkan statistik API |

**Fitur:**
- Daftar servis yang sudah selesai
- Detail riwayat servis (tanggal, pelanggan, kendaraan, jenis, total)
- Catatan teknisi
- Export laporan ke PDF/Excel
- Statistik: Total servis, pendapatan, rata-rata durasi, tingkat penyelesaian

**View Files:**
- `resources/views/dashboard/admin/history/index.blade.php` - Daftar riwayat
- `resources/views/dashboard/admin/history/show.blade.php` - Detail riwayat

---

### 4. REMINDERS (Pengingat Servis)
**Path:** `/admin/reminders`

| Method | Route | Controller Method | Nama Route | Keterangan |
|--------|-------|------------------|-----------|-----------|
| GET | `/admin/reminders` | `AdminRemindersController@index` | `admin.reminders.index` | Lihat daftar pengingat |
| GET | `/admin/reminders/create` | `AdminRemindersController@create` | `admin.reminders.create` | Form buat pengingat baru |
| POST | `/admin/reminders` | `AdminRemindersController@store` | `admin.reminders.store` | Simpan pengingat baru |
| GET | `/admin/reminders/{id}/edit` | `AdminRemindersController@edit` | `admin.reminders.edit` | Form edit pengingat |
| PUT | `/admin/reminders/{id}` | `AdminRemindersController@update` | `admin.reminders.update` | Update pengingat |
| DELETE | `/admin/reminders/{id}` | `AdminRemindersController@destroy` | `admin.reminders.destroy` | Hapus pengingat |
| POST | `/admin/reminders/{id}/send` | `AdminRemindersController@sendNotification` | `admin.reminders.send` | Kirim notifikasi ke pelanggan |

**Fitur:**
- Buat pengingat servis untuk pelanggan
- Tipe pengingat: Service, Maintenance, Inspection
- Atur tanggal pengingat dan notifikasi H-X hari sebelumnya
- Edit & hapus pengingat
- Kirim notifikasi ke pelanggan
- Filter & pencarian pengingat

**View Files:**
- `resources/views/dashboard/admin/reminders/index.blade.php` - Daftar pengingat
- `resources/views/dashboard/admin/reminders/create.blade.php` - Form buat pengingat
- `resources/views/dashboard/admin/reminders/edit.blade.php` - Form edit pengingat

---

## File Structure

```
app/Http/Controllers/Admin/
├── AdminQueueController.php
├── AdminCustomersController.php
├── AdminHistoryController.php
└── AdminRemindersController.php

app/Http/Middleware/
└── AdminMiddleware.php

resources/views/dashboard/admin/
├── layouts/
│   └── app.blade.php (Main layout)
├── queue/
│   ├── index.blade.php
│   └── show.blade.php
├── customers/
│   ├── index.blade.php
│   └── show.blade.php
├── history/
│   ├── index.blade.php
│   └── show.blade.php
└── reminders/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

---

## Configuration Files Updated

### 1. `routes/web.php`
- Import controller Admin baru
- Middleware route group untuk `/admin`
- Backward compatibility dengan route lama (redirect)

### 2. `bootstrap/app.php`
- Registrasi middleware alias `admin`

---

## Middleware

### AdminMiddleware (`app/Http/Middleware/AdminMiddleware.php`)
- Memastikan user sudah authenticated
- Memastikan user memiliki role `admin`
- Return 403 Forbidden jika user bukan admin

---

## Menggunakan Route dalam Blade Template

```blade
<!-- Navigasi ke halaman admin -->
<a href="{{ route('admin.queue.index') }}">Antrean</a>
<a href="{{ route('admin.customers.index') }}">Pelanggan</a>
<a href="{{ route('admin.history.index') }}">Riwayat</a>
<a href="{{ route('admin.reminders.index') }}">Pengingat</a>

<!-- Navigasi ke detail -->
<a href="{{ route('admin.queue.show', $id) }}">Lihat Detail</a>

<!-- Form action -->
<form method="POST" action="{{ route('admin.queue.updateStatus', $id) }}">
    @csrf
    @method('PUT')
    <!-- Form fields -->
</form>
```

---

## Catatan Implementasi

### TODO Items (Untuk Development Selanjutnya)

1. **AdminQueueController**
   - Implementasi logika database untuk mengambil data antrean
   - Implementasi update status di database
   - Tambah validasi data

2. **AdminCustomersController**
   - Implementasi CRUD ke database
   - Relasi dengan vehicles
   - Export customer data

3. **AdminHistoryController**
   - Implementasi query untuk mengambil history servis
   - Implementasi export PDF/Excel
   - Statistik dari database

4. **AdminRemindersController**
   - Implementasi model Reminder
   - Implementasi notifikasi email/SMS
   - Job scheduler untuk reminder otomatis

5. **Models & Database**
   - Buat model: Queue, Reminder, Vehicle (jika belum ada)
   - Buat migration untuk tabel yang diperlukan
   - Setup relasi antar model

---

## Security Notes

✅ Semua route admin dilindungi oleh middleware:
- `auth` - Pengguna harus login
- `admin` - Pengguna harus memiliki role 'admin'

Pastikan di dalam:
1. User model memiliki field `role`
2. Role `admin` sudah di-assign ke user admin
3. Verifikasi role di migration atau seeder

---

## Testing Routes

Anda bisa test routes menggunakan:

```bash
# Test menggunakan Artisan Tinker
php artisan tinker

# Test menggunakan curl atau Postman
curl http://localhost/admin/antrian
```

---

## Next Steps

1. **Database Setup**
   ```bash
   php artisan make:model Queue -m
   php artisan make:model Reminder -m
   php artisan migrate
   ```

2. **Implement Controllers**
   - Isi logic di setiap controller method
   - Setup repository pattern jika perlu

3. **API Endpoints** (Optional)
   - Tambahkan `api.php` routes untuk JSON responses
   - Implementasi API controller terpisah

4. **Testing**
   ```bash
   php artisan make:test AdminQueueTest
   php artisan make:test AdminCustomersTest
   ```

---

**Created:** May 17, 2026  
**Last Updated:** May 17, 2026
