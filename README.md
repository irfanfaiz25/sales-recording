<h1 align="center">Sales Recording System</h1>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Livewire-3.x-blue?style=for-the-badge&logo=livewire" alt="Livewire">
  <img src="https://img.shields.io/badge/TailwindCSS-4.x-cyan?style=for-the-badge&logo=tailwindcss" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/PHP-8.2+-purple?style=for-the-badge&logo=php" alt="PHP">
</p>

## 📋 Deskripsi Sistem

Sales Recording System adalah aplikasi web berbasis Laravel yang dirancang untuk mengelola pencatatan penjualan secara komprehensif. Sistem ini menyediakan dashboard analitik, manajemen transaksi penjualan, sistem pembayaran bertahap, dan manajemen data master dengan kontrol akses berbasis role.

### ✨ Fitur Utama

-   **Dashboard Analitik** dengan widget dan chart interaktif
-   **Manajemen Penjualan** dengan sistem multi-item per transaksi
-   **Sistem Pembayaran Bertahap** dengan validasi otomatis
-   **Manajemen Data Master** (Items & Users)
-   **Role-Based Access Control** menggunakan Spatie Laravel Permission
-   **Real-time Updates** dengan Livewire

## 🏗️ Teknologi yang Digunakan

-   **Backend**: Laravel 12.x
-   **Frontend**: Livewire 3.x, Tailwind CSS 4.x
-   **Database**: MySQL (dapat diganti dengan SQLite/PostgreSQL)
-   **Charts**: Chart.js
-   **Image Processing**: Intervention Image
-   **Permissions**: Spatie Laravel Permission

## 👥 User Roles & Permissions

### 🔐 Admin Role

**Akses Penuh ke Seluruh Sistem**

| Modul     | Permissions                        |
| --------- | ---------------------------------- |
| Dashboard | ✅ View dashboard dengan analytics |
| Users     | ✅ Create, Read, Update, Delete    |
| Items     | ✅ Create, Read, Update, Delete    |
| Sales     | ✅ Create, Read, Update, Delete    |
| Payments  | ✅ Create, Read, Update, Delete    |
| Reports   | ✅ View all reports                |

### 🏪 Kasir Role

**Akses Terbatas untuk Operasional Harian**

| Modul     | Permissions                     |
| --------- | ------------------------------- |
| Dashboard | ❌ No access                    |
| Users     | ❌ No access                    |
| Items     | ✅ Create, Read, Update, Delete |
| Sales     | ✅ Create, Read, Update, Delete |
| Payments  | ✅ Create, Read, Update, Delete |
| Reports   | ❌ No access                    |

### 👤 Default Users

| Username | Email           | Password   | Role  |
| -------- | --------------- | ---------- | ----- |
| Admin    | admin@gmail.com | rahasia123 | Admin |
| Kasir    | kasir@gmail.com | rahasia123 | Kasir |

## 🚀 Petunjuk Instalasi

### Prerequisites

Pastikan sistem Anda memiliki:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   Git

### 1. Clone Repository

```bash
git clone https://github.com/irfanfaiz25/sales-recording.git
cd sales-recording
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### 5. Storage Setup

```bash
# Create storage link for file uploads
php artisan storage:link
```

### 6. Build Assets

```bash
# Build frontend assets
npm run build

# Or for development with hot reload
npm run dev
```

### 7. Start Development Server

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 📊 Struktur Database

### Core Tables

-   **users** - Data pengguna dengan role
-   **items** - Master data produk/item
-   **sales** - Header transaksi penjualan
-   **sale_items** - Detail item per transaksi
-   **payments** - Catatan pembayaran

### Permission Tables (Spatie)

-   **permissions** - Daftar permission
-   **roles** - Daftar role
-   **model_has_permissions** - Mapping user-permission
-   **model_has_roles** - Mapping user-role

## 🎯 Fitur Unggulan

### 📈 Dashboard Analytics

-   Filter date range dengan real-time update
-   Widget: Total Transaksi, Total Penjualan, Item Terjual
-   Chart: Penjualan per Bulan (Line Chart)
-   Chart: Item Terlaris (Bar Chart)

### 💰 Sistem Pembayaran Bertahap

-   Support pembayaran parsial
-   Status otomatis: "Belum Dibayar", "Belum Dibayar Sepenuhnya", "Sudah Dibayar"
-   Validasi nominal pembayaran
-   History pembayaran lengkap

### 🛡️ Security Features

-   Role-based access control
-   CSRF protection
-   Input validation
-   File upload security

### 📱 User Experience

-   Responsive design untuk semua device
-   Real-time updates tanpa refresh
-   Interactive DataTables
-   Toast notifications
-   Loading states

## 🧪 Testing

### Login Credentials untuk Testing

**Admin Access:**

-   Email: `admin@gmail.com`
-   Password: `rahasia123`

**Kasir Access:**

-   Email: `kasir@gmail.com`
-   Password: `rahasia123`

### Test Scenarios

1. **Admin Flow:**

    - Login sebagai admin
    - Akses dashboard dan lihat analytics
    - Kelola users, items, sales, dan payments
    - Test semua CRUD operations

2. **Kasir Flow:**
    - Login sebagai kasir
    - Redirect otomatis ke sales index
    - Buat transaksi penjualan baru
    - Proses pembayaran (full/partial)
    - Verifikasi akses terbatas

## 🔧 Konfigurasi Tambahan

### Database Configuration

Untuk production, ubah konfigurasi database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sales_recording
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

**Developed with ❤️ by [Irfan Faiz](https://github.com/irfanfaiz25) using Laravel & Livewire**
