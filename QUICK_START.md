# 🚀 Quick Start Guide - HRIS API

## ✅ Setup Sudah Selesai!

Anda sudah berhasil setup:
- ✅ Database dengan 15 tabel
- ✅ Role-Based Access Control (super_admin, hr, manager, employee)
- ✅ API Endpoints untuk Employee Management
- ✅ Middleware untuk Authorization

---

## 📝 Step-by-Step Testing

### 1️⃣ **Register User Pertama**

```bash
POST http://127.0.0.1:8000/api/register
Content-Type: application/json

{
  "name": "Admin User",
  "email": "admin@hris.com",
  "password": "password",
  "password_confirmation": "password"
}
```

**Response:**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@hris.com",
    "roles": [
      {
        "name": "employee"
      }
    ]
  },
  "token": "1|xxxxxxxxxxxxx"
}
```

⚠️ **Default role adalah 'employee'**, kita perlu upgrade ke 'super_admin'

---

### 2️⃣ **Upgrade User ke Super Admin** (Via Tinker)

```bash
php artisan tinker
```

Lalu jalankan:
```php
$user = App\Models\User::where('email', 'admin@hris.com')->first();
$user->roles()->detach(); // Hapus role employee
$user->assignRole('super_admin');
exit
```

---

### 3️⃣ **Login sebagai Super Admin**

```bash
POST http://127.0.0.1:8000/api/login
Content-Type: application/json

{
  "email": "admin@hris.com",
  "password": "password"
}
```

**Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@hris.com",
    "roles": [
      {
        "id": 1,
        "name": "super_admin",
        "display_name": "Super Admin"
      }
    ]
  },
  "token": "2|yyyyyyyyyyyy"
}
```

**💾 SIMPAN TOKEN INI!** Gunakan untuk semua request selanjutnya.

---

### 4️⃣ **Create Department**

```bash
POST http://127.0.0.1:8000/api/departments
Authorization: Bearer 2|yyyyyyyyyyyy
Content-Type: application/json

{
  "name": "Information Technology",
  "code": "IT",
  "description": "IT Department"
}
```

**Response:**
```json
{
  "message": "Department created successfully",
  "department": {
    "id": 1,
    "name": "Information Technology",
    "code": "IT",
    "description": "IT Department",
    "is_active": true
  }
}
```

---

### 5️⃣ **Create Position**

```bash
POST http://127.0.0.1:8000/api/positions
Authorization: Bearer 2|yyyyyyyyyyyy
Content-Type: application/json

{
  "name": "Software Engineer",
  "code": "SE",
  "department_id": 1,
  "level": 2,
  "description": "Mid-level Software Engineer",
  "min_salary": 5000000,
  "max_salary": 10000000
}
```

**Response:**
```json
{
  "message": "Position created successfully",
  "position": {
    "id": 1,
    "name": "Software Engineer",
    "code": "SE",
    "department_id": 1,
    "level": 2,
    "min_salary": "5000000.00",
    "max_salary": "10000000.00",
    "department": {
      "id": 1,
      "name": "Information Technology"
    }
  }
}
```

---

### 6️⃣ **Create Employee**

```bash
POST http://127.0.0.1:8000/api/employees
Authorization: Bearer 2|yyyyyyyyyyyy
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@hris.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "employee",
  
  "employee_id": "EMP001",
  "phone": "08123456789",
  "department_id": 1,
  "position_id": 1,
  "employment_status": "probation",
  "join_date": "2025-11-01",
  "basic_salary": 6000000,
  "annual_leave_quota": 12
}
```

**Response:**
```json
{
  "message": "Employee created successfully",
  "employee": {
    "id": 2,
    "name": "John Doe",
    "email": "john@hris.com",
    "employee_profile": {
      "id": 1,
      "employee_id": "EMP001",
      "phone": "08123456789",
      "employment_status": "probation",
      "join_date": "2025-11-01",
      "basic_salary": "6000000.00",
      "annual_leave_quota": 12,
      "remaining_leave": 12,
      "department": {
        "id": 1,
        "name": "Information Technology",
        "code": "IT"
      },
      "position": {
        "id": 1,
        "name": "Software Engineer",
        "code": "SE"
      }
    },
    "roles": [
      {
        "id": 4,
        "name": "employee",
        "display_name": "Employee"
      }
    ]
  }
}
```

---

### 7️⃣ **Get All Employees**

```bash
GET http://127.0.0.1:8000/api/employees
Authorization: Bearer 2|yyyyyyyyyyyy
```

**Response:**
```json
{
  "data": [
    {
      "id": 2,
      "name": "John Doe",
      "email": "john@hris.com",
      "employee_profile": {...},
      "roles": [...]
    }
  ],
  "current_page": 1,
  "per_page": 15,
  "total": 1
}
```

---

### 8️⃣ **Get Employee Detail**

```bash
GET http://127.0.0.1:8000/api/employees/2
Authorization: Bearer 2|yyyyyyyyyyyy
```

---

### 9️⃣ **Update Employee**

```bash
PUT http://127.0.0.1:8000/api/employees/2
Authorization: Bearer 2|yyyyyyyyyyyy
Content-Type: application/json

{
  "employment_status": "permanent",
  "permanent_date": "2025-12-01",
  "basic_salary": 7000000
}
```

---

### 🔟 **Get Current User Profile**

```bash
GET http://127.0.0.1:8000/api/user
Authorization: Bearer {token}
```

---

## 📌 Important Notes

### Field yang Required untuk Create Employee:
- ✅ `name`, `email`, `password`, `password_confirmation`
- ✅ `employee_id` (unique)
- ✅ `department_id`, `position_id`
- ✅ `employment_status` (permanent/contract/internship/probation)
- ✅ `join_date`
- ✅ `role` (employee/manager/hr)

### Field Optional:
- `phone`, `birth_date`, `gender`, `address`, `city`, `province`
- `work_shift_id`, `manager_id`, `permanent_date`
- `basic_salary`, `annual_leave_quota`
- `bank_name`, `bank_account_number`, `bank_account_name`
- `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`

### Default Values:
- `basic_salary`: 0
- `annual_leave_quota`: 12
- `remaining_leave`: sama dengan annual_leave_quota
- `is_active`: true

---

## 🔒 Authorization Rules

| Endpoint | Super Admin | HR | Manager | Employee |
|----------|-------------|----|---------| ---------|
| GET /api/employees | ✅ | ✅ | ❌ | ❌ |
| POST /api/employees | ✅ | ✅ | ❌ | ❌ |
| PUT /api/employees | ✅ | ✅ | ❌ | ❌ |
| DELETE /api/employees | ✅ | ✅ | ❌ | ❌ |
| GET /api/departments | ✅ | ✅ | ❌ | ❌ |
| POST /api/departments | ✅ | ✅ | ❌ | ❌ |
| GET /api/positions | ✅ | ✅ | ❌ | ❌ |
| POST /api/positions | ✅ | ✅ | ❌ | ❌ |
| GET /api/user | ✅ | ✅ | ✅ | ✅ |

---

## 🧪 Testing dengan Postman/Insomnia

### Import Collection:
1. Buat folder "HRIS API"
2. Set Environment Variable:
   - `base_url`: http://127.0.0.1:8000/api
   - `token`: (dari response login)
3. Semua request pakai header: `Authorization: Bearer {{token}}`

---

## 🎯 Next Steps untuk Frontend

Sekarang Anda bisa buat dashboard dengan fitur:

### 1. **Login Page**
- Form login
- Save token ke localStorage/sessionStorage

### 2. **Dashboard Admin**
- List Employees (dengan pagination, search, filter)
- Add New Employee Form
- Edit Employee Modal
- Deactivate Employee

### 3. **Master Data Management**
- Departments CRUD
- Positions CRUD  
- Work Shifts CRUD (belum ada API, bisa dibuat mirip Department)

### 4. **Employee Detail Page**
- Profile Information
- Employment History
- Leave Balance
- Attendance Summary

---

## 🐛 Troubleshooting

### Error: "Unauthenticated"
❌ Token tidak valid atau tidak disertakan
✅ Pastikan header `Authorization: Bearer {token}` ada

### Error: "Forbidden"
❌ User tidak punya role yang sesuai
✅ Cek role user dengan GET /api/user

### Error: "SQLSTATE[23000]: Integrity constraint violation"
❌ Foreign key tidak ditemukan (department_id/position_id salah)
✅ Cek dulu ID department dan position yang valid

### Error: "The email has already been taken"
❌ Email sudah digunakan
✅ Gunakan email berbeda atau hapus user lama

---

Selamat! API Anda sudah siap digunakan untuk dashboard FE! 🎉
