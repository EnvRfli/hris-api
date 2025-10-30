# HRIS API - Employee Management Endpoints

## 🔐 Authentication & Authorization

Semua endpoint memerlukan authentication dengan Bearer Token yang didapat dari login/register.

### Roles:
- **super_admin** - Full access
- **hr** - Manage employees, approve leaves & reimbursements
- **manager** - Approve leaves & reimbursements untuk bawahannya
- **employee** - Basic access

---

## 📋 API Endpoints

### 1. **Create Employee** (Super Admin / HR Only)

**Endpoint:** `POST /api/employees`

**Headers:**
```
Authorization: Bearer {token}
```

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "employee",
  
  "employee_id": "EMP001",
  "phone": "08123456789",
  "birth_date": "1990-01-15",
  "gender": "male",
  "address": "Jl. Contoh No. 123",
  "city": "Jakarta",
  "province": "DKI Jakarta",
  "postal_code": "12345",
  
  "department_id": 1,
  "position_id": 1,
  "work_shift_id": 1,
  "manager_id": 2,
  "employment_status": "probation",
  "join_date": "2025-11-01",
  
  "basic_salary": 5000000,
  "annual_leave_quota": 12,
  
  "bank_name": "BCA",
  "bank_account_number": "1234567890",
  "bank_account_name": "John Doe",
  
  "emergency_contact_name": "Jane Doe",
  "emergency_contact_phone": "08198765432",
  "emergency_contact_relation": "istri"
}
```

**Response 201:**
```json
{
  "message": "Employee created successfully",
  "employee": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "employee_profile": {
      "employee_id": "EMP001",
      "phone": "08123456789",
      "department": {
        "id": 1,
        "name": "IT"
      },
      "position": {
        "id": 1,
        "name": "Software Engineer"
      },
      ...
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

### 2. **Get All Employees** (Super Admin / HR)

**Endpoint:** `GET /api/employees`

**Query Parameters:**
- `search` - Search by name or email
- `department_id` - Filter by department
- `employment_status` - Filter by status (permanent, contract, etc)
- `per_page` - Items per page (default: 15)
- `page` - Page number

**Example:**
```
GET /api/employees?search=john&department_id=1&per_page=20&page=1
```

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "employee_profile": {...},
      "roles": [...]
    }
  ],
  "current_page": 1,
  "per_page": 20,
  "total": 50
}
```

---

### 3. **Get Employee Detail** (Super Admin / HR / Self)

**Endpoint:** `GET /api/employees/{id}`

**Response 200:**
```json
{
  "employee": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "employee_profile": {
      "employee_id": "EMP001",
      "phone": "08123456789",
      "birth_date": "1990-01-15",
      "gender": "male",
      "address": "Jl. Contoh No. 123",
      "city": "Jakarta",
      "province": "DKI Jakarta",
      "department": {...},
      "position": {...},
      "work_shift": {...},
      "manager": {...},
      "employment_status": "probation",
      "join_date": "2025-11-01",
      "basic_salary": 5000000,
      "annual_leave_quota": 12,
      "remaining_leave": 12,
      "bank_name": "BCA",
      ...
    },
    "roles": [...]
  }
}
```

---

### 4. **Update Employee** (Super Admin / HR)

**Endpoint:** `PUT /api/employees/{id}`

**Body:** (Semua field optional, kirim yang ingin diupdate saja)
```json
{
  "name": "John Doe Updated",
  "phone": "08123456789",
  "department_id": 2,
  "position_id": 3,
  "employment_status": "permanent",
  "permanent_date": "2025-12-01",
  "basic_salary": 6000000
}
```

**Response 200:**
```json
{
  "message": "Employee updated successfully",
  "employee": {...}
}
```

---

### 5. **Delete/Deactivate Employee** (Super Admin / HR)

**Endpoint:** `DELETE /api/employees/{id}`

**Response 200:**
```json
{
  "message": "Employee deactivated successfully"
}
```

---

## 📂 Department Endpoints

### 1. Create Department
**POST /api/departments**
```json
{
  "name": "Information Technology",
  "code": "IT",
  "description": "IT Department",
  "head_id": 2
}
```

### 2. Get All Departments
**GET /api/departments**

### 3. Update Department
**PUT /api/departments/{id}`**

### 4. Delete Department
**DELETE /api/departments/{id}**

---

## 📂 Position Endpoints

### 1. Create Position
**POST /api/positions**
```json
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

### 2. Get All Positions
**GET /api/positions**

### 3. Update Position
**PUT /api/positions/{id}**

### 4. Delete Position
**DELETE /api/positions/{id}**

---

## 🔒 Routes dengan Authorization

```php
// routes/api.php

// Public routes
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Employee routes - Super Admin & HR only
    Route::middleware(['role:super_admin,hr'])->group(function () {
        Route::apiResource('employees', EmployeeController::class);
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('positions', PositionController::class);
    });
    
    // Self profile
    Route::get('/profile', function (Request $request) {
        return $request->user()->load(['employeeProfile', 'roles']);
    });
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
```

---

## 🧪 Testing Flow

### 1. Register Super Admin (First Time)
```bash
POST /api/register
{
  "name": "Super Admin",
  "email": "admin@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

### 2. Manually Assign Super Admin Role (via Tinker)
```php
php artisan tinker

$user = User::where('email', 'admin@example.com')->first();
$user->assignRole('super_admin');
```

### 3. Login as Super Admin
```bash
POST /api/login
{
  "email": "admin@example.com",
  "password": "password"
}
# Save token
```

### 4. Create Department
```bash
POST /api/departments
Authorization: Bearer {token}
{
  "name": "IT",
  "code": "IT"
}
```

### 5. Create Position
```bash
POST /api/positions
Authorization: Bearer {token}
{
  "name": "Software Engineer",
  "code": "SE",
  "department_id": 1
}
```

### 6. Create Employee
```bash
POST /api/employees
Authorization: Bearer {token}
{...employee data...}
```

---

## 📝 Notes

1. **Password harus minimal 8 karakter** (Laravel default)
2. **Email harus unique**
3. **Employee ID harus unique**
4. **Role bisa: employee, manager, hr, super_admin**
5. **Employment status: permanent, contract, internship, probation**
6. **Gender: male, female**
7. **Soft delete** - Employee tidak dihapus permanent, hanya di-set `is_active = false`

---

## 🚀 Next Steps

1. **Buat seeder** untuk departments, positions, work_shifts
2. **Implementasi upload foto** untuk profile photo, check-in photo, dll
3. **Buat API endpoints** untuk:
   - Attendance (Check-in/Check-out)
   - Leave Management
   - Reimbursement
4. **Implementasi notifications** untuk approval
5. **Add logging** untuk audit trail

