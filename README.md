# SIKMA – Sistem Kehadiran Mahasiswa

SIKMA adalah aplikasi berbasis web untuk pencatatan dan monitoring kehadiran mahasiswa menggunakan RFID, dilengkapi dengan fitur real-time dashboard dan manajemen profil pengguna.

## 🚀 Fitur Utama

- 🏠 **Dashboard**: Menampilkan informasi presensi secara real-time.
- 👤 **Autentikasi**: Login, Register, Logout.
- 📝 **Profil Mahasiswa**: Lihat dan edit data diri.
- 📸 **Edit Profil**: Validasi dinamis dan preview gambar sebelum upload.
- 🔔 **Notifikasi Presensi**: (opsional, jika ada)
- 📊 **Rekap Presensi Admin**: (opsional, jika ada)

## 🧱 Teknologi yang Digunakan

- Laravel 12
- Blade Template + TailwindCSS 4
- MySQL
- RFID + ESP32 (pada implementasi hardware)

## ⚙️ Instalasi

1. Clone repo ini :

```bash
git clone https://github.com/rizkyromadhon/sikma-app.git
cd sikma-app
```

2. Install dependensi Laravel :

```bash
composer install
```

3. Copy .env :

```bash
cp .env.example .env
```

4. Generate app key :

```bash
php artisan key:generate
```

5. Buat database dan konfigurasi .env, lalu migrasi :

```bash
php artisan migrate
```

6. Jalankan server lokal :

```bash
php artisan serve
```

7. (Opsional) Buat symbolic link untuk storage :

```bash
php artisan storage:link
```

📂 Struktur Folder Penting
- app/Http/Controllers/ProfileController.php – Logika edit profil
- resources/views/edit-profile.blade.php – Halaman edit profil
- public/storage/foto-profil – Tempat penyimpanan foto

👨‍💻 Developer
- Nama: Mohammad Rizky Romadhon
- NIM: E32222530
- Tugas Akhir: Pengembangan Sistem Presensi Mahasiswa Menggunakan RFID untuk Monitoring dan Rekapitulasi Kehadiran Secara Real-Time
- POLITEKNIK NEGERI JEMBER

📄 Lisensi
Proyek ini dibuat untuk keperluan pembelajaran / Tugas Akhir. Tidak untuk digunakan secara komersial.
