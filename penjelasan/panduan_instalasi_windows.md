# Panduan Instalasi Manual Aplikasi RSB (Windows)

Panduan ini menjelaskan langkah-langkah instalasi aplikasi dari awal (clone dari GitHub) hingga siap dijalankan.

---

## ✅ Prasyarat (Wajib Terinstall)

Sebelum memulai, pastikan software berikut sudah terpasang di komputer Windows Anda:

| Software | Versi Minimum | Link Download |
|---|---|---|
| **Git** | Terbaru | [git-scm.com](https://git-scm.com/downloads/win) |
| **PHP** | 8.2+ | Bisa via [Laragon](https://laragon.org/) atau [XAMPP](https://www.apachefriends.org/) |
| **Composer** | Terbaru | [getcomposer.org](https://getcomposer.org/download/) |
| **Node.js & NPM** | 18+ | [nodejs.org](https://nodejs.org/) |
| **MySQL** | 5.7+ | Sudah termasuk di Laragon/XAMPP |

> **Rekomendasi:** Gunakan **Laragon** karena sudah menyertakan PHP, MySQL, dan fitur virtual host secara otomatis.

---

## Langkah 1: Clone Repositori dari GitHub

Buka **Command Prompt** atau **Git Bash**, lalu jalankan perintah berikut:

```bash
git clone https://github.com/suryawanabakti/rsb.git
```

Masuk ke folder proyek:

```bash
cd rsb
```

---

## Langkah 2: Install Dependensi PHP (Composer)

Jalankan perintah berikut untuk mengunduh semua library PHP yang dibutuhkan:

```bash
composer install
```

> Proses ini mungkin memakan waktu beberapa menit tergantung koneksi internet.

---

## Langkah 3: Buat File Konfigurasi `.env`

Salin file contoh konfigurasi:

```bash
copy .env.example .env
```

Buka file `.env` dengan teks editor (Notepad, VS Code, dll.) dan sesuaikan pengaturan database:

```env
APP_NAME=RSB
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suratbhayangkara      # Nama database yang akan dibuat
DB_USERNAME=root                   # Username MySQL Anda
DB_PASSWORD=                       # Password MySQL Anda (kosongkan jika tidak ada)
```

---

## Langkah 4: Generate Application Key

```bash
php artisan key:generate
```

> Perintah ini mengisi nilai `APP_KEY` di file `.env` secara otomatis. Wajib dilakukan agar session dan enkripsi berjalan dengan benar.

---

## Langkah 5: Buat Database

Buka aplikasi MySQL Anda (phpMyAdmin, Laragon, atau MySQL Workbench) dan buat database baru dengan nama:

```
suratbhayangkara
```

---

## Langkah 6: Jalankan Migrasi & Seeder Database

Perintah berikut akan membuat seluruh tabel dan mengisi data awal:

```bash
php artisan migrate --seed
```

> Jika hanya ingin membuat tabel tanpa data awal, gunakan `php artisan migrate` saja.

---

## Langkah 7: Buat Symbolic Link Storage

Perintah ini menghubungkan folder penyimpanan file agar bisa diakses dari browser:

```bash
php artisan storage:link
```

---

## Langkah 8: Install Dependensi JavaScript (NPM)

```bash
npm install
```

---

## Langkah 9: Build Aset Frontend

Untuk versi **produksi** (final/siap deploy):

```bash
npm run build
```

Atau untuk mode **pengembangan** (dengan hot-reload):

```bash
npm run dev
```

---

## Langkah 10: Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses:

```
http://localhost:8000
```

---

## 🎉 Instalasi Selesai!

Aplikasi sudah siap digunakan. Berikut ringkasan akun default (sesuaikan jika seeder diubah):

| Role | URL Login | Username |
|---|---|---|
| Admin | `/admin/login` | Sesuai seeder |
| Dokter | `/admin/login` | Sesuai seeder |
| Petugas Lab | `/admin/login` | Sesuai seeder |
| Pasien | `/register` | Daftar baru |

---

## Troubleshooting Umum

**❌ Error: `php` is not recognized**
→ Pastikan PHP sudah ditambahkan ke PATH Windows, atau gunakan terminal bawaan Laragon.

**❌ Error: `Class ... not found`**
→ Jalankan `composer dump-autoload`

**❌ Error: `SQLSTATE: Access denied`**
→ Periksa kembali `DB_USERNAME` dan `DB_PASSWORD` di file `.env`.

**❌ Halaman tampil tapi aset CSS/JS tidak muncul**
→ Pastikan sudah menjalankan `npm run build` atau `npm run dev` sedang berjalan.
