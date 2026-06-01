<p align="center">
  <img src="https://github.com/user-attachments/assets/8a1fce8b-f8bf-43d8-af42-5758a252bab0" alt="Logo Noctale Banner" width="100%">
</p>

# Noctale

Noctale adalah platform membaca dan menulis novel digital yang dibangun menggunakan framework Laravel. Platform ini memfasilitasi interaksi antara pembaca dan penulis dalam satu wadah yang mudah digunakan, dilengkapi dengan berbagai fitur interaktif dan sistem manajemen konten yang komprehensif.

## Fitur Utama

### 1. Pengguna Publik (Tamu)

- **Eksplorasi Novel:** Menjelajahi berbagai macam novel berdasarkan genre dan popularitas.
- **Membaca Novel:** Akses dan baca bab-bab novel yang tersedia.
- **Profil Pengguna:** Lihat profil para penulis dan karya-karyanya.

### 2. Pembaca Terautentikasi (Pembaca)

- **Dashboard Personal:** Ringkasan aktivitas seperti riwayat bacaan, jumlah bookmark, dan rekomendasi.
- **Sistem Interaksi:** Berikan komentar pada bab, sukai komentar pengguna lain, dan tulis ulasan (rating & review) untuk novel.
- **Bookmark & Riwayat:** Simpan novel favorit ke daftar bookmark untuk dibaca nanti dan pantau riwayat bacaan.
- **Kotak Masuk (Inbox):** Terima notifikasi terkait aktivitas interaksi dan pembaruan karya.
- **Pelaporan (Report):** Fitur untuk melaporkan novel atau komentar yang tidak pantas.
- **Manajemen Profil:** Edit dan perbarui profil pengguna secara mandiri.

### 3. Penulis

Semua fitur Pembaca, ditambah:
- **Manajemen Novel:** Membuat, memperbarui, dan mengelola karya novel sendiri.
- **Manajemen Bab (Bab):** Tambahkan bab-bab baru, kelola urutan, dan unggah gambar ilustrasi untuk bab.
- **Statistik Karya:** Pantau jumlah penayangan (views), jumlah interaksi, dan performa dari masing-masing novel.

### 4. Administrator

- **Dashboard Admin:** Pemantauan dan ringkasan seluruh aktivitas di dalam platform.
- **Manajemen Pengguna:** Kelola akun pembaca, penulis, dan admin lainnya.
- **Manajemen Konten Utama:** Kelola daftar Genre dan awasi seluruh novel yang dipublikasikan.
- **Moderasi Platform:** Kelola dan tindak lanjuti laporan pengguna, serta hapus komentar/ulasan yang melanggar aturan.
- **Manajemen Banner:** Mengatur spanduk promosi yang tampil di halaman utama platform.

## Teknologi yang digunakan

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Tailwind CSS, Alpine.js, Vite
- **Basis data:** MySQL
- **Autentikasi & Keamanan:** Laravel Breeze

## Cara Instalasi & Jangkauan Proyek Secara Lokal

### 1. Kloning repository ini

```bash
git clone https://github.com/niluh01/team-prompter-novel-noctale
cd noctale
```

### 2. Instal dependensi PHP & Node.js

Pastikan sudah menginstal Composer dan Node.js.

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`

```bash
cp .env.example .env
```

Lalu buka file `.env` dan sesuaikan konfigurasi database:

`DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

---

### 4. Hasilkan Kunci Aplikasi

```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database

```bash
php artisan migrate
```

Jika memiliki seeder:

```bash
php artisan migrate --seed
```

### 6. Jalankan Development Server

Untuk frontend:

```bash
npm run dev
```

Buka terminal baru untuk backend:

```bash
php artisan serve
```

### 7. Akses Aplikasi

Buka browser:

```bash
http://localhost:8000
```

## Dokumentasi & Aset Pendukung

Untuk informasi lebih lanjut mengenai pengembangan, desain, dan pemecahan masalah, silakan merujuk ke dokumen berikut di dalam folder `docs/`:

*   [**Dokumentasi Desain UI/UX & Figma**](docs/design.md): Tautan Figma aktif untuk desain antarmuka, slide presentasi kelompok, dan pratinjau gambar mockup.
*   [**Panduan Troubleshooting Gambar Broken & Storage**](docs/troubleshoot.md): Solusi lengkap untuk mengatasi gambar sampul/banner yang error 404 pada local development.

## Team

Proyek **Web Novel Noctale** dikembangkan oleh **Kelompok 13 Team Prompter** sebagai bagian dari pengembangan aplikasi web novel digital berbasis Laravel. Tim ini berkolaborasi dalam merancang, membangun, dan mengembangkan platform yang interaktif, modern, serta mudah digunakan bagi pembaca maupun penulis.

### Anggota Tim

| Nama | NPM | Peran |
|------|------|------|
| Bintang Maulana | 2313020011 | Frontend Developer |
| Amelia Putri Syahroza | 2313020022 | Backend Developer |
| Niluh Anggraini | 2313020043 | Project Manager |

Tim berfokus pada pengembangan sistem yang tidak hanya memiliki tampilan modern dan responsif, tetapi juga didukung dengan pengelolaan data serta fitur interaktif yang optimal untuk memberikan pengalaman pengguna yang nyaman dan efisien.

---

*Proyek ini dirancang untuk memberikan pengalaman membaca dan menulis karya fiksi dengan ekosistem yang terkelola dengan baik.*
