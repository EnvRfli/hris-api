# Struktur Database HRIS API

## Overview
Dokumentasi ini menjelaskan struktur database untuk aplikasi HRIS (Human Resource Information System) yang mencakup:
- ✅ **Presensi/Attendance** - Check in/out dengan GPS dan foto
- ✅ **Cuti/Leave** - Pengajuan dan approval cuti
- ✅ **Reimburse** - Pengajuan penggantian biaya
- ✅ **Employee Management** - Data karyawan lengkap
- ✅ **Department & Position** - Struktur organisasi
- ✅ **Holiday & Work Shift** - Hari libur dan jam kerja

---

## Tabel Utama

### 1. **users** (Tabel default Laravel)
Tabel autentikasi dasar. **JANGAN** tambahkan field HRIS di sini.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| name | string | Nama user |
| email | string | Email (unique) |
| password | string | Password (hashed) |
| email_verified_at | timestamp | Waktu verifikasi email |
| remember_token | string | Token remember me |
| timestamps | - | created_at, updated_at |

**Relasi:**
- `hasOne(EmployeeProfile)`
- `hasMany(Attendance)`
- `hasMany(Leave)`
- `hasMany(Reimbursement)`

---

### 2. **employee_profiles**
Data lengkap karyawan. Relasi 1:1 dengan `users`.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| user_id | bigint | FK → users |
| employee_id | string | NIP/Employee Number (unique) |
| phone | string | Nomor telepon |
| birth_date | date | Tanggal lahir |
| gender | enum | male, female |
| address | text | Alamat lengkap |
| city | string | Kota |
| province | string | Provinsi |
| postal_code | string | Kode pos |
| photo | string | Path foto profil |
| **Employment Info** | | |
| department_id | bigint | FK → departments |
| position_id | bigint | FK → positions |
| work_shift_id | bigint | FK → work_shifts |
| manager_id | bigint | FK → users (atasan) |
| employment_status | enum | permanent, contract, internship, probation |
| join_date | date | Tanggal masuk |
| permanent_date | date | Tanggal diangkat permanent |
| resign_date | date | Tanggal resign |
| **Salary & Benefits** | | |
| basic_salary | decimal(15,2) | Gaji pokok |
| annual_leave_quota | integer | Jatah cuti tahunan (default: 12) |
| remaining_leave | integer | Sisa cuti |
| **Bank Info** | | |
| bank_name | string | Nama bank |
| bank_account_number | string | Nomor rekening |
| bank_account_name | string | Nama pemilik rekening |
| **Emergency Contact** | | |
| emergency_contact_name | string | Nama kontak darurat |
| emergency_contact_phone | string | Nomor kontak darurat |
| emergency_contact_relation | string | Hubungan (istri, suami, ortu) |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

---

### 3. **departments**
Departemen/divisi perusahaan.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| name | string | Nama departemen (IT, Finance, HR, dll) |
| code | string | Kode (IT, FIN, HR) - unique |
| description | text | Deskripsi |
| head_id | bigint | FK → users (kepala departemen) |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

---

### 4. **positions**
Jabatan/posisi karyawan.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| name | string | Nama posisi |
| code | string | Kode - unique |
| department_id | bigint | FK → departments |
| level | integer | Level (1=Junior, 5=Manager) |
| description | text | Deskripsi |
| min_salary | decimal(15,2) | Gaji minimum |
| max_salary | decimal(15,2) | Gaji maksimum |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

---

### 5. **work_shifts**
Jam kerja/shift.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| name | string | Nama shift |
| start_time | time | Jam masuk (08:00) |
| end_time | time | Jam pulang (17:00) |
| grace_period | integer | Toleransi telat (menit) |
| is_default | boolean | Shift default |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

---

## Modul Presensi

### 6. **attendances**
Data presensi harian karyawan.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| user_id | bigint | FK → users |
| date | date | Tanggal presensi |
| check_in | time | Waktu check in |
| check_out | time | Waktu check out |
| check_in_location | string | GPS coordinates check in |
| check_out_location | string | GPS coordinates check out |
| check_in_photo | string | Path foto check in |
| check_out_photo | string | Path foto check out |
| status | enum | present, late, absent, half_day, sick, leave, holiday, remote |
| late_duration | integer | Durasi telat (menit) |
| work_duration | integer | Durasi kerja (menit) |
| notes | text | Catatan |
| is_overtime | boolean | Apakah lembur |
| overtime_duration | integer | Durasi lembur (menit) |
| timestamps | - | created_at, updated_at |

**Unique:** `user_id + date` (1 user = 1 attendance/hari)

---

## Modul Cuti

### 7. **leave_types**
Jenis-jenis cuti.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| name | string | Nama (Cuti Tahunan, Sakit, dll) |
| code | string | Kode (ANNUAL, SICK) - unique |
| description | text | Deskripsi |
| max_days | integer | Maksimal hari |
| is_paid | boolean | Apakah dibayar |
| requires_document | boolean | Perlu dokumen (surat dokter) |
| is_deducted_from_quota | boolean | Mengurangi jatah cuti |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

**Contoh data:**
- Cuti Tahunan (ANNUAL) - paid, deduct quota, max 12 hari
- Cuti Sakit (SICK) - paid, deduct quota, requires document
- Cuti Melahirkan (MATERNITY) - paid, tidak deduct quota, max 90 hari

---

### 8. **leaves**
Pengajuan cuti karyawan.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| user_id | bigint | FK → users |
| leave_type_id | bigint | FK → leave_types |
| start_date | date | Tanggal mulai |
| end_date | date | Tanggal selesai |
| total_days | integer | Total hari cuti |
| reason | text | Alasan cuti |
| document | string | Path upload dokumen |
| status | enum | pending, approved, rejected, cancelled |
| approved_by | bigint | FK → users (yang approve) |
| approved_at | timestamp | Waktu approve |
| approval_notes | text | Catatan approval |
| rejection_reason | text | Alasan reject |
| timestamps | - | created_at, updated_at |

**Flow:**
1. Karyawan submit → status `pending`
2. Manager approve/reject
3. Jika approved → kurangi `remaining_leave` di `employee_profiles`

---

## Modul Reimburse

### 9. **reimbursement_categories**
Kategori reimburse.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| name | string | Nama (Transport, Makan, dll) |
| code | string | Kode - unique |
| description | text | Deskripsi |
| max_amount | decimal(15,2) | Maksimal nominal |
| requires_receipt | boolean | Perlu bukti/kwitansi |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

---

### 10. **reimbursements**
Pengajuan reimburse.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| user_id | bigint | FK → users |
| reimbursement_category_id | bigint | FK → reimbursement_categories |
| title | string | Judul pengajuan |
| description | text | Deskripsi detail |
| expense_date | date | Tanggal pengeluaran |
| amount | decimal(15,2) | Nominal yang diajukan |
| receipt | string | Path upload bukti |
| status | enum | pending, approved, rejected, paid |
| approved_by | bigint | FK → users |
| approved_at | timestamp | Waktu approve |
| approval_notes | text | Catatan approval |
| rejection_reason | text | Alasan reject |
| approved_amount | decimal(15,2) | Nominal yang disetujui |
| payment_date | date | Tanggal dibayar |
| payment_method | string | Metode bayar |
| payment_reference | string | Nomor referensi |
| timestamps | - | created_at, updated_at |

**Flow:**
1. Submit → status `pending`
2. Approve → status `approved` (bisa ubah nominal)
3. Bayar → status `paid`

---

## Modul Lainnya

### 11. **holidays**
Hari libur.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| name | string | Nama hari libur |
| date | date | Tanggal |
| type | enum | national, company, religious |
| description | text | Deskripsi |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

---

### 12. **announcements**
Pengumuman perusahaan.

| Field | Type | Keterangan |
|-------|------|------------|
| id | bigint | Primary Key |
| title | string | Judul |
| content | text | Isi pengumuman |
| type | enum | general, urgent, event, policy |
| created_by | bigint | FK → users |
| published_date | date | Tanggal publish |
| expired_date | date | Tanggal expired |
| is_pinned | boolean | Pin di atas |
| is_active | boolean | Status aktif |
| timestamps | - | created_at, updated_at |

---

## Cara Menjalankan

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. (Opsional) Buat Seeder untuk Data Dummy
```bash
php artisan make:seeder DepartmentSeeder
php artisan make:seeder PositionSeeder
php artisan make:seeder WorkShiftSeeder
php artisan make:seeder LeaveTypeSeeder
php artisan make:seeder ReimbursementCategorySeeder
```

### 3. Contoh Data Seeder

**WorkShiftSeeder:**
```php
WorkShift::create([
    'name' => 'Regular',
    'start_time' => '08:00:00',
    'end_time' => '17:00:00',
    'grace_period' => 15,
    'is_default' => true,
]);
```

**LeaveTypeSeeder:**
```php
LeaveType::create([
    'name' => 'Cuti Tahunan',
    'code' => 'ANNUAL',
    'max_days' => 12,
    'is_paid' => true,
    'is_deducted_from_quota' => true,
]);
```

---

## Fitur yang Bisa Dikembangkan

### 1. **Payroll/Penggajian**
- Tabel: `payrolls`, `payroll_components`, `allowances`, `deductions`
- Hitung gaji + tunjangan - potongan

### 2. **Performance Review**
- Tabel: `performance_reviews`, `kpis`, `goals`
- Evaluasi kinerja karyawan

### 3. **Training & Development**
- Tabel: `trainings`, `training_participants`, `certifications`
- Pelatihan dan sertifikasi

### 4. **Recruitment**
- Tabel: `job_postings`, `applications`, `interviews`
- Proses rekrutmen

### 5. **Document Management**
- Tabel: `employee_documents`
- Upload KTP, CV, kontrak, dll

### 6. **Payslip**
- Generate slip gaji PDF
- Email otomatis setiap bulan

### 7. **Notification System**
- Push notification untuk approval
- Email reminder cuti, ulang tahun, dll

---

## Tips & Best Practices

### 1. **Jangan Overload Tabel Users**
❌ **Jangan:**
```php
// Tabel users dengan 50+ kolom
users: id, name, email, nip, phone, address, salary, dll...
```

✅ **Lakukan:**
```php
// Pisahkan ke tabel employee_profiles
users: id, name, email, password
employee_profiles: id, user_id, nip, phone, address, salary, dll...
```

### 2. **Gunakan Enum untuk Status**
```php
status: enum('pending', 'approved', 'rejected')
// Lebih baik dari string biasa
```

### 3. **Soft Deletes untuk Data Penting**
```php
use SoftDeletes;
// Jangan hapus permanent, biar ada history
```

### 4. **Index untuk Performance**
```php
$table->index(['user_id', 'date']); // attendances
$table->index('status'); // leaves, reimbursements
```

### 5. **Validation di Controller**
```php
// Validasi total_days tidak melebihi remaining_leave
// Validasi tanggal tidak overlap dengan cuti lain
```

---

## API Endpoints yang Direkomendasikan

### Authentication
- POST `/api/register`
- POST `/api/login`
- POST `/api/logout`

### Profile
- GET `/api/profile`
- PUT `/api/profile`
- POST `/api/profile/photo`

### Attendance
- POST `/api/attendance/check-in`
- POST `/api/attendance/check-out`
- GET `/api/attendance/history`
- GET `/api/attendance/monthly/{year}/{month}`

### Leave
- GET `/api/leaves`
- POST `/api/leaves`
- GET `/api/leaves/{id}`
- PUT `/api/leaves/{id}`
- DELETE `/api/leaves/{id}`
- POST `/api/leaves/{id}/approve`
- POST `/api/leaves/{id}/reject`

### Reimbursement
- GET `/api/reimbursements`
- POST `/api/reimbursements`
- GET `/api/reimbursements/{id}`
- POST `/api/reimbursements/{id}/approve`
- POST `/api/reimbursements/{id}/pay`

### Master Data
- GET `/api/departments`
- GET `/api/positions`
- GET `/api/holidays`
- GET `/api/announcements`

---

**Dibuat pada:** 30 Oktober 2025
**Framework:** Laravel 11
**Database:** MySQL/PostgreSQL
