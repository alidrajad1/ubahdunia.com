# 🌍 ubahdunia.com

**ubahdunia.com** adalah platform donasi berbasis web yang dirancang untuk memudahkan aksi sosial melalui donasi uang, barang, dan tenaga secara transparan dan terstruktur. Platform ini juga mengadopsi sistem gamifikasi dan penukaran poin untuk mendorong partisipasi aktif dari pengguna.

Repository: [github.com/alidrajad1/ubahdunia.com.git](https://github.com/alidrajad1/ubahdunia.com.git)

---

## ✨ Fitur Unggulan

- 🔐 Registrasi & Login Akun (Laravel Breeze + Livewire)
- 💸 Donasi Uang, 📦 Barang, 🙋‍♂️ Tenaga Sukarela
- 🔁 Top-up & Riwayat Transaksi
- 📦 Pelacakan Status Donasi
- 📢 Berita dan Update Sosial
- 🧩 Sistem Poin Reward & Gamifikasi
- 🤲 Permohonan Bantuan oleh Penerima
- 🛠️ Admin Panel (Filament) untuk manajemen user, donasi, dan permohonan

---

## 🧑‍💻 Teknologi

| Komponen        | Stack                  |
|-----------------|------------------------|
| Framework       | Laravel v12.15.0       |
| DBMS            | MariaDB                |
| UI Auth         | Laravel Breeze         |
| Komponen UI     | Livewire               |
| Admin Panel     | FilamentPHP            |

---

## 📂 Struktur Modul

- `users` — Akun pengguna (donatur, relawan, penerima, admin)
- `donations` — Catatan donasi (uang, barang, tenaga)
- `campaigns` — Kampanye sosial
- `requests` — Permohonan bantuan
- `transactions` — Top-up saldo & transaksi
- `rewards` — Sistem poin dan hadiah
- `reward_redemptions` — Histori penukaran poin
- `admins` — Admin sistem (via Filament)

---

## ⚙️ Instalasi

```bash

# 1. Clone Repository
echo "1. Cloning repository..."
git clone https://github.com/alidrajad1/ubahdunia.com.git

# Masuk ke direktori proyek
echo "   Navigating into project directory..."
cd ubahdunia.com

# 2. Instal Dependensi PHP (Composer)
echo "2. Installing PHP dependencies with Composer..."
composer install

# 3. Konfigurasi Environment
echo "3. Creating .env file from .env.example..."
cp .env.example .env

# Penting: Setelah ini, kamu HARUS MENGEDIT file .env secara manual!
# Buka file .env dan sesuaikan pengaturan database (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
# Pastikan juga DB_CONNECTION sudah diatur ke 'mysql' jika menggunakan MariaDB.
echo "   --- PENTING: Harap edit file .env Anda sekarang! ---"
echo "   Buka file .env dan sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD)."
echo "   Tekan ENTER setelah selesai mengedit .env..."
read -r

# 4. Generate Application Key
echo "4. Generating Laravel application key..."
php artisan key:generate

# 5. Instal Dependensi Frontend & Kompilasi Aset (NPM)
echo "5. Installing Node.js dependencies and compiling assets..."
npm install
npm run dev

# Opsional: Jika Anda menggunakan Yarn, uncomment baris di bawah ini dan komentari baris NPM di atas.
# echo "   (Opsional) Using Yarn to install and compile assets..."
# yarn
# yarn dev

# 6. Jalankan Migrasi Database
echo "6. Running database migrations..."
php artisan migrate

# Opsional: Jalankan Seeder jika ada data dummy yang ingin diisi
# echo "   (Opsional) Running database seeders..."
# php artisan db:seed
# atau:
# php artisan migrate --seed

# --- Menjalankan Aplikasi ---
echo "--- Instalasi Selesai! ---"
echo "Untuk menjalankan aplikasi, gunakan perintah:"
echo "php artisan serve"
echo "Aplikasi akan tersedia di http://127.0.0.1:8000 (biasanya)."
