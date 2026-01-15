# 📦 Stock Management System

Sistem Manajemen Inventaris berbasis web yang powerful dan modern, dibangun dengan Laravel 12 dan Livewire 3. Aplikasi ini dirancang untuk membantu bisnis dalam mengelola stok barang, transaksi pembelian & penjualan, serta menghasilkan laporan keuangan secara real-time.

## 🚀 Fitur Utama

### 📊 Manajemen Inventaris
- **Multi-Warehouse Management**: Kelola stok di beberapa gudang sekaligus
- **Real-time Stock Tracking**: Pantau pergerakan stok secara real-time
- **Barcode Support**: Integrasi sistem barcode untuk tracking barang
- **Stock Alerts**: Notifikasi otomatis untuk stok menipis

### 💰 Sistem Penjualan & Pembelian
- **Point of Sale (POS)**: Interface kasir yang intuitif dan responsif
- **Sales Management**: Kelola transaksi penjualan dengan mudah
- **Purchase Orders**: Sistem pembelian barang dari pemasok
- **Payment Terms**: Dukungan pembayaran termin/cicilan
- **Payment Tracking**: Pencatatan dan monitoring pembayaran

### 👥 Manajemen Stakeholder
- **Customer Management**: Database pelanggan lengkap
- **Supplier Management**: Kelola data pemasok
- **User Management**: Sistem pengguna dengan role & permissions

### 📈 Laporan & Analitik
- **Profit & Loss Report**: Laporan laba rugi detail
- **Stock Reports**: Laporan persediaan barang
- **Transaction History**: Riwayat semua transaksi
- **Expense Tracking**: Pencatatan pengeluaran operasional

## 🛠️ Teknologi yang Digunakan

### Backend
- **Framework**: [Laravel 12](https://laravel.com) - PHP Framework modern dan elegant
- **PHP Version**: ^8.2 - Menggunakan fitur PHP terbaru
- **Livewire**: ^3.7 - Komponen reactive tanpa menulis JavaScript
- **Database**: MySQL/PostgreSQL/SQLite (kompatibel dengan semua)
- **Queue System**: Laravel Queue untuk background processing
- **Logging**: Laravel Pail untuk real-time log monitoring

### Frontend
- **Build Tool**: [Vite](https://vitejs.dev) ^7.0.7 - Lightning-fast build tool
- **CSS Framework**: [Tailwind CSS](https://tailwindcss.com) ^4.1.18 - Utility-first CSS framework
- **JavaScript**: Vanilla JS dengan Alpine.js (via Livewire)
- **Icons**: Tailwind Icons & Custom SVG
- **HTTP Client**: Axios ^1.11.0

### Development Tools
- **Package Manager**: Composer (Backend), NPM (Frontend)
- **Code Quality**: Laravel Pint untuk code formatting
- **Testing**: PHPUnit ^11.5.3
- **Task Runner**: Concurrently untuk menjalankan multiple services
- **Hot Module Replacement**: Vite HMR untuk development yang cepat

## ⚡ Optimasi & Performance

### 1. **Database Optimization**
- Eager Loading untuk mencegah N+1 query problem
- Database indexing pada kolom yang sering di-query
- Query optimization dengan select statements yang efisien

### 2. **Frontend Performance**
- **Vite Build System**: Build time yang sangat cepat
- **Tailwind CSS Purging**: Hanya CSS yang digunakan yang di-bundle
- **Lazy Loading**: Component Livewire di-load on-demand
- **Asset Bundling**: Minifikasi CSS & JS di production

### 3. **Caching Strategy**
- View caching untuk template Blade
- Route caching untuk performa routing
- Config caching untuk production deployment
- Optimized autoloader untuk faster class loading

### 4. **Code Organization**
- Service Layer Pattern untuk business logic
- Repository Pattern untuk data access
- Component-based architecture dengan Livewire
- DRY (Don't Repeat Yourself) principles

### 5. **Development Optimization**
- Hot Module Replacement (HMR) dengan Vite
- Excluded storage/framework/views dari file watching
- Concurrent development servers untuk productivity
- Automated setup scripts

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x & NPM
- MySQL >= 5.7 / PostgreSQL >= 12 / SQLite >= 3.8
- Web Server (Apache/Nginx)

## 🔧 Instalasi & Setup

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/stock-management.git
cd stock-management
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stock_management
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi dan seeder:
```bash
php artisan migrate --seed
```

### 5. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 6. Jalankan Aplikasi
```bash
# Single command untuk menjalankan semua services
composer run dev

# Atau manual:
php artisan serve
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🎯 Quick Setup (Automated)
```bash
composer run setup
```
Command ini akan otomatis:
- Install semua dependencies
- Copy .env file
- Generate app key
- Jalankan migrations
- Build assets

## 🧪 Testing
```bash
# Run all tests
composer run test

# Run specific test
php artisan test --filter=YourTestName
```

## 📁 Struktur Proyek

```
stock-management/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # HTTP Controllers
│   │   └── Middleware/      # Custom Middleware
│   ├── Livewire/           # Livewire Components
│   ├── Models/             # Eloquent Models
│   └── Services/           # Business Logic Services
├── database/
│   ├── migrations/         # Database Migrations
│   ├── seeders/           # Database Seeders
│   └── factories/         # Model Factories
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript Files
│   └── views/             # Blade Templates
│       ├── components/    # Blade Components
│       ├── layouts/       # Layout Templates
│       └── livewire/      # Livewire Views
├── routes/
│   ├── web.php           # Web Routes
│   └── console.php       # Console Commands
└── public/               # Public Assets
```

## 🔐 Fitur Keamanan

- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection
- ✅ Password Hashing (Bcrypt)
- ✅ Secure Session Management
- ✅ Input Validation & Sanitization
- ✅ Rate Limiting

## 🎨 UI/UX Features

- **Responsive Design**: Mobile-first approach dengan Tailwind CSS
- **Real-time Updates**: Tanpa refresh halaman menggunakan Livewire
- **Modern Interface**: UI yang clean dan intuitif
- **Dark Mode Ready**: (Opsional, bisa diaktifkan)
- **Fast Loading**: Optimasi untuk performa maksimal

## 📝 Environment Variables

Key environment variables yang perlu dikonfigurasi:

```env
APP_NAME="Stock Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stock_management
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
```

## 🚀 Deployment

### Production Build
```bash
# Optimize untuk production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up queue worker
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificate
- [ ] Configure backups
- [ ] Set up monitoring

## 🤝 Contributing

Kontribusi selalu diterima! Silakan:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lengkap.

## 👤 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Livewire](https://livewire.laravel.com) - A full-stack framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Vite](https://vitejs.dev) - Next generation frontend tooling

---

⭐ **Jika proyek ini membantu, berikan star di GitHub!**

💡 **Butuh bantuan?** Buka [Issues](https://github.com/yourusername/stock-management/issues)
