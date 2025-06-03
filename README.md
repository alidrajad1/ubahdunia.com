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
| Framework       | Laravel v12.16.0       |
| DBMS            | MariaDB                |
| UI Auth         | Laravel Jetstream      |
| Komponen UI     | InertiaJS              |
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
- `carosel` — Donasi teratas untuk promosi
- `comment` — KOmentar pengguna
- `admins` — Admin sistem (via Filament)

---

## ⚙️ Instalasi

```bash
git clone https://github.com/alidrajad1/ubahdunia.com.git
cd ubahdunia.com
composer install
cp .env.example .env
php artisan key:generate
