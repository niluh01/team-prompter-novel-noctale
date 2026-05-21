# Panduan Troubleshooting: Mengatasi Gambar Broken / Error 404 (Folder Storage Hilang)

Dokumen ini mencatat masalah, penyebab, serta langkah-langkah konkret yang telah dilakukan untuk menyelesaikan masalah gambar hasil unggahan (seperti cover novel atau banner) yang tidak muncul/mengalami *Error 404*.

---

## 1. Gejala Masalah
- Gambar cover novel, avatar, atau banner di halaman web menampilkan ikon gambar rusak (*broken image*).
- Jika tautan gambar dibuka secara langsung di tab baru (misalnya `http://localhost:8000/storage/covers/contoh.jpg`), browser menampilkan error **404 Not Found**.
- Menjalankan perintah `php artisan storage:link` mengembalikan pesan sukses, tetapi diiringi pesan sistem: *"The system cannot find the path specified"*.

---

## 2. Analisis Penyebab Utama
1. **Folder `storage` Hilang dari Workspace:**
   Folder `storage` beserta seluruh subfolder penting di dalamnya (`storage/app/public`, `storage/framework`, `storage/logs`) terhapus atau belum dibuat setelah kloning repositori (karena folder kosong diabaikan oleh `.gitignore`).
2. **Symlink Korup / Salah Arah:**
   Karena folder asal (`storage/app/public`) tidak ada secara fisik, pembuatan symbolic link (`public/storage`) oleh Laravel menjadi tidak valid (mengarah ke folder kosong yang tidak ada).

---

## 3. Langkah-Langkah Perbaikan yang Telah Dilakukan

### Langkah 1: Rekonstruksi Struktur Folder Storage Laravel
Kami membuat kembali seluruh struktur folder wajib Laravel di bawah direktori `storage/` dengan perintah terminal berikut:
```powershell
New-Item -ItemType Directory -Force -Path "storage/app/public", "storage/framework/cache/data", "storage/framework/sessions", "storage/framework/views", "storage/logs"
```
*Dengan ini, Laravel memiliki ruang fisik untuk menulis berkas unggahan, sesi, templat terkompilasi, dan log sistem.*

### Langkah 2: Bersihkan Symlink yang Rusak
Kami menghapus folder/tautan shortcut `public/storage` yang korup/tidak valid di direktori `public` agar tidak menghalangi pembuatan tautan baru:
```powershell
if (Test-Path public/storage) { Remove-Item -Recurse -Force public/storage }
```

### Langkah 3: Hubungkan Kembali Tautan Penyimpanan (Storage Link)
Setelah folder target ada dan tautan lama yang rusak dibersihkan, kami menjalankan ulang perintah generator tautan Laravel:
```bash
php artisan storage:link
```
*Hasil:* Laravel berhasil menghubungkan direktori `public/storage` secara langsung ke `storage/app/public` tanpa pesan error lagi.

---

## 4. Tips Pemeliharaan bagi Tim Developer
- **Jangan Menghapus Folder `storage`:** Selalu biarkan folder `storage` ada di direktori proyek lokal Anda.
- **Pindahkan Folder public/storage Saat Deploy:** Jika melakukan migrasi atau deploy ke server baru, pastikan untuk menghapus folder `public/storage` lama terlebih dahulu sebelum menjalankan `php artisan storage:link` pada lingkungan server baru tersebut.
