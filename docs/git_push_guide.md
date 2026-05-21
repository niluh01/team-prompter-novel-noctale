# Panduan Git Push & Daftar GitHub Issues (Noctale Web Novel)

Dokumen ini berisi panduan teknis langkah demi langkah untuk melakukan **Git Push** terhadap perubahan yang telah dibuat, daftar klasifikasi **GitHub Issues** (Bug, Feature, Refactor), serta pembagian file dan deskripsi commit antara **Front-End (FE)** dan **Back-End (BE)**.

---

## 1. Alur & Strategi Git Push

Pilih salah satu dari dua strategi di bawah ini sesuai dengan alur kerja tim Anda:

### Strategi A: Gabungan (Hotfix Consolidation) — *Direkomendasikan* ⭐
Karena perubahan FE dan BE saling terintegrasi (misal: fitur rating membutuhkan kueri di BE dan rendering kartu di FE), disarankan untuk melakukan push seluruh perubahan dalam satu branch hotfix terpadu.

Jalankan perintah berikut di terminal:
```bash
# 1. Buat branch baru untuk konsolidasian perbaikan dari branch main lokal Anda
git checkout -b hotfix/consolidation-fixes

# 2. Tambahkan seluruh perubahan ke staging area
git add .

# 3. Commit dengan deskripsi yang jelas
git commit -m "fix: resolve major system bugs, fix PSR-4 namespaces, and clean emojis to premium SVG icons"

# 4. Push branch ke repository GitHub remote
git push origin hotfix/consolidation-fixes
```
*Setelah berhasil di-push, buka GitHub repository Anda, buat Pull Request (PR) ke `main`, lalu lakukan Merge.*

---

### Strategi B: Pemisahan Terpisah (Selective Checkout & Branching)
Jika Anda harus menaruh masing-masing perbaikan ke branch remote-nya secara manual (misal: perubahan visual ke `feature/fe-...` dan logika ke `feature/be-...`):

1. **Simpan (Stash) perubahan saat ini:**
   ```bash
   git stash
   ```
2. **Pindah ke branch target (misal `feature/be-novel-management`):**
   ```bash
   git checkout feature/be-novel-management
   ```
3. **Ambil perubahan spesifik dari stash:**
   ```bash
   git stash apply
   # Lakukan git add hanya pada file yang relevan (misal NovelController.php)
   git add noctale/app/Http/Controllers/Web/NovelController.php
   git commit -m "fix(be): fix namespace mismatch and restore writer CRUD in NovelController"
   git push origin feature/be-novel-management
   ```
4. **Bersihkan working directory untuk branch berikutnya:**
   ```bash
   git checkout -- .
   git checkout main
   git stash pop
   # Ulangi langkah di atas untuk branch berikutnya
   ```

---

## 2. Klasifikasi & Draf GitHub Issues

Berikut adalah 13 draf issue siap pakai lengkap dengan klasifikasi jenis issue, langkah reproduksi, perilaku yang diharapkan, dan deskripsi Merge PR:

### Issue 1: Crash saat Mengakses Detail Profil Penulis (`UserProfileController` Hilang)
* **Klasifikasi:** 🔴 **Bug** (Fatal)
* **Label GitHub:** `bug`, `backend`, `frontend`
* **GitHub Issue Title:** `bug: Runtime crash when viewing author profile / accessing /users/{user}`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Terjadi runtime error (`Target class [App\Http\Controllers\Web\UserProfileController] does not exist`) ketika mencoba mengakses halaman profil penulis.

  ## 🚨 Impact
  - Pengguna umum tidak dapat melihat profil penulis dari halaman detail novel (klik nama penulis menyebabkan crash).
  - Admin tidak dapat memeriksa profil pengadu/terlapor dari dashboard moderasi laporan (klik "Cek Profil" menyebabkan crash).

  ## 🛠 Steps to Reproduce
  1. Masuk ke halaman detail novel mana saja secara publik.
  2. Klik nama penulis di bawah judul novel.
  3. Halaman akan menampilkan error `Target class [App\Http\Controllers\Web\UserProfileController] does not exist`.

  ## 📋 Expected Behavior
  Sistem seharusnya menampilkan halaman profil penulis (`profile.show`) yang berisi informasi bio, statistik tulisan, serta daftar novel yang telah dipublikasikan oleh penulis tersebut.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Membuat berkas controller yang hilang: `app/Http/Controllers/Web/UserProfileController.php`.
  - Mengimplementasikan method `show()` untuk mengambil data profil penulis berserta daftar novelnya yang berstatus `published`.
  ```

---

### Issue 2: Error Duplikasi & Fitur Penulis Tidak Berjalan (`Web\NovelController.php` Salah Namespace)
* **Klasifikasi:** 🔴 **Bug** (Fatal)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: Namespace mismatch and code duplication in Web\NovelController.php`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  File `app/Http/Controllers/Web/NovelController.php` dideklarasikan dengan `namespace App\Http\Controllers\Admin;` dan berisi logika admin. Hal ini menyebabkan konflik autoloading dan hilangnya logika pengelolaan novel bagi penulis serta rute detail novel.

  ## 🚨 Impact
  - Terjadi konflik fatal deklarasi ganda untuk kelas `Admin\NovelController`.
  - Fitur kelola novel penulis (create, store, edit, update, destroy) tidak dapat diakses dan memicu error.
  - Halaman detail novel publik (`novels.show`) crash karena method `show()` tidak ditemukan di controller yang ter-load.

  ## 🛠 Steps to Reproduce
  1. Buka rute `/novels/{novel_id}` sebagai pembaca biasa, ATAU
  2. Buka menu dashboard penulis dan akses "Kelola Novel".
  3. Perhatikan error crash yang berkaitan dengan kegagalan pemanggilan method `show` atau ketidakcocokan class loader.

  ## 📋 Expected Behavior
  `Web\NovelController` seharusnya berada di bawah namespace `App\Http\Controllers\Web` dan menyediakan fungsionalitas penulisan serta pembacaan novel (termasuk method `show`, `create`, `store`, dll), terpisah dari logika administrasi.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Memperbaiki penulisan namespace `App\Http\Controllers\Web` di dalam `app/Http/Controllers/Web/NovelController.php`.
  - Membersihkan duplikasi logika admin dan menulis ulang fungsionalitas asli untuk CRUD novel bagi peran Penulis (Writer).
  ```

---

### Issue 3: Error `BadMethodCallException` saat Berinteraksi dengan Komentar (`User::isAdmin()` Belum Didefinisikan)
* **Klasifikasi:** 🔴 **Bug** (Fatal)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: BadMethodCallException when calling Auth::user()->isAdmin()`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Sistem memanggil method `isAdmin()` pada instance `User` di controller komentar dan berkas view Blade, namun method tersebut belum dideklarasikan di model `App\Models\User`.

  ## 🚨 Impact
  - Terjadi crash/error 500 (`BadMethodCallException`) ketika pengguna berinteraksi dengan fitur komentar.
  - Fitur hapus komentar oleh admin terhambat karena pengecekan hak akses gagal dijalankan.

  ## 🛠 Steps to Reproduce
  1. Login sebagai Admin atau User biasa.
  2. Coba kirim komentar atau muat halaman diskusi yang memuat pengecekan peran admin.
  3. Terjadi crash dengan pesan error `Method App\Models\User::isAdmin does not exist`.

  ## 📋 Expected Behavior
  Pengecekan `$user->isAdmin()` harus mengembalikan nilai boolean (`true` jika role adalah admin, `false` jika sebaliknya) tanpa menimbulkan kegagalan pemanggilan method.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Menambahkan method `isAdmin(): bool` pada model `App\Models\User` untuk menguji apakah atribut `role` bernilai `'admin'`.
  ```

---

### Issue 4: Kegagalan Autoloading Banner & Pencarian Novel di Environment Sensitif Kasus (Linux)
* **Klasifikasi:** 🔴 **Bug** (Penyimpangan Standar PSR-4)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: PSR-4 Autoloading failures due to typo in controller filenames`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Terdapat ketidaksesuaian penamaan file dengan nama kelas asli di dalam berkas controller:
  1. File `ExploreControler.php` (satu 'l') berisi kelas `ExploreController` (dua 'l').
  2. File `Banner.Controller.php` (menggunakan titik) berisi kelas `BannerController`.

  ## 🚨 Impact
  Aplikasi dapat berjalan di Windows lokal (karena Windows bersifat case-insensitive terhadap penamaan file), namun akan langsung mengalami crash fatal (Class Not Found) ketika di-deploy ke production server berbasis Linux yang menerapkan aturan PSR-4 secara ketat.

  ## 🛠 Steps to Reproduce
  1. Jalankan aplikasi pada lingkungan server Linux.
  2. Akses menu manajemen banner di admin `/admin/banners`, atau menu pencarian novel `/novels`.
  3. Server akan mengembalikan HTTP 500 / Class not found error.

  ## 📋 Expected Behavior
  Semua nama file controller harus sesuai dengan nama kelasnya (`ExploreController.php` dan `BannerController.php`) agar autoloader PSR-4 composer dapat memetakan kelas dengan tepat di semua sistem operasi.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Mengubah nama file `ExploreControler.php` menjadi `ExploreController.php`.
  - Mengubah nama file `Banner.Controller.php` menjadi `BannerController.php`.
  - Mengoreksi seluruh import kelas di berkas routing dan controller terkait.
  ```

---

### Issue 5: Gambar Unggahan Mengalami Broken Image / Error 404
* **Klasifikasi:** 🔴 **Bug** (Konfigurasi File)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: Uploaded novel covers and banners return 404 not found`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Gambar cover novel atau banner yang diunggah oleh pengguna tidak muncul di halaman depan (menghasilkan status HTTP 404 / Broken Image).

  ## 🚨 Impact
  - Desain visual situs web terlihat berantakan karena gambar sampul default/unggahan rusak.
  - Pembaca tidak dapat melihat gambar cover dari novel yang diminati.

  ## 🛠 Steps to Reproduce
  1. Masuk ke halaman kelola novel sebagai penulis.
  2. Unggah gambar cover baru untuk novel.
  3. Simpan dan buka halaman utama atau detail novel publik.
  4. Gambar cover novel pecah (404).

  ## 📋 Expected Behavior
  Gambar yang diunggah harus dapat diakses melalui URL publik menggunakan `Storage::url()` secara normal.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Membuat folder `storage/app/public` secara fisik.
  - Memastikan konfigurasi `FILESYSTEM_DISK=public` di berkas `.env`.
  - Menjalankan ulang perintah `php artisan storage:link` untuk membuat symbolic link dari `storage/app/public` ke folder `public/storage`.
  ```

---

### Issue 6: Error `Target class [admin] does not exist` saat Akses Rute Admin
* **Klasifikasi:** 🔴 **Bug** (Middleware Rute)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: Target class [admin] does not exist when accessing admin routes`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Terjadi error `BindingResolutionException: Target class [admin] does not exist` ketika mencoba mengakses rute yang dilindungi oleh middleware `'admin'`.

  ## 🚨 Impact
  - Admin tidak dapat mengakses halaman dashboard admin (`/admin/dashboard` dan seluruh fitur admin lainnya).
  - Seluruh rute administratif mengalami crash fatal 500.

  ## 🛠 Steps to Reproduce
  1. Login sebagai Admin.
  2. Akses halaman admin `/admin/dashboard`.
  3. Halaman menampilkan error `Target class [admin] does not exist`.

  ## 📋 Expected Behavior
  Sistem seharusnya dapat mengenali alias middleware `'admin'` dan memproses pengecekan peran sebelum mengizinkan akses ke rute administratif.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Mendaftarkan alias middleware `'admin'` ke `bootstrap/app.php` di dalam metode `->withMiddleware()`.
  - Memastikan middleware menunjuk ke kelas `App\Http\Middleware\AdminMiddleware`.
  ```

---

### Issue 7: Filter Pencarian & Peran di Manajemen User Admin Mengalami Kegagalan
* **Klasifikasi:** 🔴 **Bug** (Fungsionalitas Panel Admin)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: Admin user management filtering and search does not persist pagination`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Fitur pencarian berdasarkan nama/email dan filter berdasarkan role (Admin/Penulis/Pembaca) pada halaman manajemen user di panel admin tidak berfungsi dengan benar atau parameter query hilang saat navigasi halaman (paginasi).

  ## 🚨 Impact
  - Admin kesulitan mencari pengguna tertentu di database yang besar.
  - Berpindah halaman paginasi membatalkan filter pencarian yang sudah dimasukkan.

  ## 🛠 Steps to Reproduce
  1. Masuk ke halaman Admin -> Manajemen User.
  2. Ketik nama tertentu di kolom pencarian atau pilih salah satu filter role, lalu klik Cari.
  3. Data tidak terfilter dengan benar, atau klik ke halaman 2 paginasi mengembalikan seluruh daftar user tanpa filter awal.

  ## 📋 Expected Behavior
  Pencarian dan penyaringan role harus mengembalikan data yang cocok secara presisi, serta query string pencarian harus tetap bertahan (`withQueryString`) saat berpindah halaman paginasi.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Memperbaiki query pencarian di `UserController@index` menggunakan kondisi `when($search)` dan `when($role)`.
  - Menambahkan pemanggilan `.withQueryString()` pada pemanggilan metode paginasi agar parameter pencarian tetap bertahan saat navigasi halaman.
  ```

---

### Issue 8: Teks Konten Bab Novel Keluar Wadah/Layar ke Kanan (No Wrap)
* **Klasifikasi:** 🔴 **Bug** (Visual/Responsivitas UI)
* **Label GitHub:** `bug`, `frontend`
* **GitHub Issue Title:** `bug: Chapter content text overflows container horizontally instead of wrapping`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Saat membaca isi bab novel, teks cerita yang panjang tidak melipat ke bawah (wrap) melainkan terus memanjang ke kanan melebihi batas wadah (container) layar.

  ## 🚨 Impact
  - Kenyamanan pembaca sangat terganggu karena harus melakukan scroll horizontal untuk membaca satu kalimat penuh.
  - Tampilan responsif pada perangkat seluler menjadi rusak.

  ## 🛠 Steps to Reproduce
  1. Buka salah satu bab novel dengan paragraf teks yang cukup panjang.
  2. Amati tampilan teks di layar komputer atau seluler.
  3. Paragraf memanjang ke kanan dan melebihi area putih wadah baca.

  ## 📋 Expected Behavior
  Teks cerita harus melipat ke bawah (wrap) secara otomatis mengikuti lebar layar pengguna dan menjaga format spasi baris asli penulis.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Memperbarui file view `resources/views/chapters/show.blade.php`.
  - Menambahkan kelas CSS `break-words` dan `whitespace-pre-wrap` pada elemen pembungkus konten bab novel agar responsif dan melipat baris baru dengan benar.
  ```

---

### Issue 9: Views Novel Tidak Bertambah & Selisih dengan Total Views Bab
* **Klasifikasi:** 🔴 **Bug** (Konsistensi Data)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: Novel view counts are not updated when reading chapters and mismatch with total chapter views`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Jumlah view pada halaman profil novel tidak bertambah meskipun pembaca sudah selesai membaca bab. Hal ini menyebabkan jumlah views novel di beranda selalu bernilai `0` dan tidak sesuai dengan total jumlah view di halaman kelola bab penulis.

  ## 🚨 Impact
  - Sistem pemeringkatan novel terpopuler di halaman utama tidak akurat karena view novel induk tidak pernah berubah.
  - Terjadi inkonsistensi data tampilan antara dasbor penulis dan halaman publik pembaca.

  ## 🛠 Steps to Reproduce
  1. Buka bab novel publik sebagai pembaca beberapa kali.
  2. Perhatikan views bab bertambah di kelola bab.
  3. Kembali ke detail novel publik, views novel tetap tidak bertambah (tetap 0).

  ## 📋 Expected Behavior
  Setiap kali sebuah bab dibaca oleh pengguna, views novel induk harus bertambah secara real-time dan jumlahnya harus selalu sama dengan total views seluruh babnya.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Menambahkan fungsi sinkronisasi agregasi (`$novel->update(['views' => $novel->chapters()->sum('views')])`) di dalam `ChapterController@show` saat bab dibaca.
  - Membuat dan mendaftarkan perintah Artisan baru `php artisan novels:sync-views` di `routes/console.php` untuk mereset dan menyinkronkan data view novel yang tidak selaras di database lama.
  ```

---

### Issue 10: Penerbitan Bab Terjadwal Otomatis Gagal Rilis
* **Klasifikasi:** 🔴 **Bug** (Zona Waktu & Otomatisasi)
* **Label GitHub:** `bug`, `backend`
* **GitHub Issue Title:** `bug: Scheduled chapters fail to auto-publish on time due to UTC timezone configuration`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Bab novel yang diatur terbit otomatis sesuai waktu terjadwal tidak muncul di halaman pembaca lain meskipun waktu jadwal tayangnya sudah lewat.

  ## 🚨 Impact
  - Penulis merasa fitur penerbitan terjadwal rusak karena bab tidak rilis tepat waktu.
  - Pembaca tidak dapat melihat pembaruan bab baru secara real-time.

  ## 🛠 Steps to Reproduce
  1. Buat bab novel dengan status `scheduled` dan atur waktu tayang 5 menit ke depan.
  2. Tunggu hingga waktu terjadwal tersebut lewat.
  3. Buka detail novel menggunakan akun pengguna lain. Bab tersebut tidak terdaftar di daftar bab publik.

  ## 📋 Expected Behavior
  Bab novel berstatus terjadwal harus langsung terbit dan dapat dibaca oleh publik sesaat setelah waktu terjadwalnya terlampaui.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Mengubah konfigurasi timezone default aplikasi dari `UTC` menjadi `Asia/Jakarta` di `config/app.php` dan `.env` agar selaras dengan zona waktu pengguna lokal (WIB).
  - Menambahkan kode auto-publish *on-the-fly* pada `AppServiceProvider@boot` untuk meng-update bab terjadwal yang sudah due setiap kali aplikasi dimuat di sisi client.
  - Mendaftarkan tugas scheduler otomatis (`Schedule::call(...)`) di `routes/console.php` agar dapat dipicu setiap menit oleh Laravel Task Scheduler di server produksi.
  ```

---

### Issue 11: Rata-rata Rating Tidak Muncul di Kartu Novel (Beranda, Jelajah, Bookmark)
* **Klasifikasi:** 🔴 **Bug** (Tampilan Kosong)
* **Label GitHub:** `bug`, `backend`, `frontend`
* **GitHub Issue Title:** `bug: Novel average rating stars do not display on home page and listing cards`
* **Issue Description:**
  ```markdown
  ## 🐛 Bug Description
  Nilai rata-rata rating (bintang ulasan) novel tidak muncul di halaman Beranda, Jelajah, dan Bookmark, meskipun novel-novel tersebut sudah memiliki ulasan dan penilaian bintang dari pembaca.

  ## 🚨 Impact
  - Pembaca tidak dapat melihat kualitas novel secara visual dari kartu novel.
  - Antarmuka (UI) terlihat kosong di area informasi ulasan kartu.

  ## 🛠 Steps to Reproduce
  1. Berikan ulasan bintang 5 pada salah satu novel.
  2. Buka halaman Beranda, Jelajah, atau Bookmark.
  3. Kartu novel tersebut tidak menampilkan ikon bintang maupun angka rata-rata rating.

  ## 📋 Expected Behavior
  Kartu novel (`novel-card`) harus menampilkan ikon bintang kuning beserta rata-rata ulasan rating (misal: ★ 4.8) di sebelah jumlah views jika novel memiliki setidaknya satu ulasan.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Mengoptimalkan pemuatan kueri novel di `HomeController`, `ExploreController`, dan `BookmarkController` menggunakan Eloquent Eager Loading `withAvg('reviews', 'rating')`.
  - Memperbarui komponen view `novel-card.blade.php` untuk menghitung dan merender data rating rata-rata (`reviews_avg_rating`) secara estetis di samping jumlah views.
  ```

---

### Issue 12: Fitur Menandai Semua Notifikasi sebagai Telah Dibaca Belum Tersedia
* **Klasifikasi:** ✨ **Feature** (Fitur Baru)
* **Label GitHub:** `feature`, `backend`, `frontend`
* **GitHub Issue Title:** `feature: Add "Mark All as Read" button to notification inbox`
* **Issue Description:**
  ```markdown
  ## 🚀 Feature Description
  Pengguna memiliki banyak notifikasi masuk dan harus mengeklik tombol "Tandai Dibaca" satu per satu untuk setiap pesan. Kami membutuhkan tombol tunggal untuk menandai seluruh pesan di kotak masuk sebagai telah dibaca secara instan.

  ## 📋 User Story
  Sebagai pengguna situs, saya ingin bisa menandai semua pesan notifikasi di kotak masuk sebagai telah dibaca dalam satu klik, agar saya tidak perlu membuang waktu mengeklik satu per satu ketika memiliki banyak notifikasi baru.

  ## 🛠 Acceptance Criteria
  - Terdapat tombol "Tandai Semua Telah Dibaca" di halaman kotak masuk notifikasi (`/inbox`).
  - Tombol hanya muncul ketika ada minimal satu notifikasi yang berstatus belum dibaca (`is_read = false`).
  - Ketika diklik, semua notifikasi pengguna tersebut berubah menjadi `is_read = true` dan halaman me-refresh dengan pesan sukses.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Menambahkan rute POST `/inbox/read-all` di `routes/web.php`.
  - Menambahkan method `readAll()` di `NotificationController` untuk melakukan operasi batch update status `is_read` pada database.
  - Menambahkan tombol "Tandai Semua Telah Dibaca" di bagian atas halaman `inbox.blade.php` beserta banner flash alert feedback penanda sukses.
  ```

---

### Issue 13: Pembersihan Emote Menjadi Ikon SVG & FontAwesome Premium
* **Klasifikasi:** 🎨 **Refactor / UI Enhancement** (Desain)
* **Label GitHub:** `refactor`, `frontend`
* **GitHub Issue Title:** `refactor: Clean up raw UI emojis and migrate to premium SVG/FontAwesome icons`
* **Issue Description:**
  ```markdown
  ## 🚀 Visual Refactoring Description
  Antarmuka platform (khususnya halaman baca chapter, list novel penulis, navigasi dropdown jelajah, sidebar admin, dan profil pengguna) menggunakan emoji mentah (seperti ⚠️, 📖, 📌, 🎭, 🛡️, ✍️) yang kurang konsisten penampilannya antar sistem operasi yang berbeda. Hal ini perlu diganti ke ikon SVG/vektor agar terlihat lebih premium dan profesional.

  ## 📋 Acceptance Criteria
  - Tidak ada lagi emoji mentah pada navigasi dropdown, sidebar admin, form pelaporan, tab profil, halaman bacaan, dan halaman pengelolaan novel.
  - Elemen digantikan dengan inline SVG dengan warna HSL modern yang seragam atau ikon FontAwesome.
  - Halaman dimuat dengan responsif tanpa merusak struktur visual lama.
  ```
* **Merge Description (PR):**
  ```markdown
  ### ⚙️ What changes were made?
  - Mengubah emotikon di `chapters/show.blade.php`, `history/index.blade.php`, dan `writer/novels/index.blade.php` dengan inline SVG.
  - Memodifikasi `layouts/navigation.blade.php` dan `layouts/app.blade.php` untuk mengganti emoji menu utama, admin menu, dan modal pelaporan dengan SVG.
  - Memperbarui `profile/show.blade.php` dengan ikon FontAwesome (`fa-shield-alt`, `fa-pen`, `fa-flag`).
  ```

---

## 3. Pembagian Push File (Front-End vs Back-End)

Jika Anda memisahkan push ke branch FE dan BE masing-masing, berikut adalah daftar klasifikasi file yang harus di-push ke masing-masing kelompok branch:

### 🖥️ Berkas Back-End (BE)
Pilih branch target BE yang relevan (misal `feature/be-...` atau `feature/setup-project`) untuk mem-push file berikut:

*   **Rute & Middleware:**
    *   [MODIFY] [app.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/bootstrap/app.php)
    *   [MODIFY] [web.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/routes/web.php)
    *   [MODIFY] [console.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/routes/console.php)
*   **Model & Konfigurasi:**
    *   [MODIFY] [User.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Models/User.php)
    *   [MODIFY] [app.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/config/app.php)
*   **Service Provider:**
    *   [MODIFY] [AppServiceProvider.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Providers/AppServiceProvider.php)
*   **Controllers:**
    *   [NEW] [UserProfileController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Web/UserProfileController.php)
    *   [MODIFY] [NovelController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Web/NovelController.php)
    *   [MODIFY] [ChapterController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Web/ChapterController.php)
    *   [MODIFY] [NotificationController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Web/NotificationController.php)
    *   [MODIFY] [BookmarkController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Web/BookmarkController.php)
    *   [MODIFY] [HomeController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Web/HomeController.php)
    *   [MODIFY] [UserController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Admin/UserController.php)
    *   [MODIFY] [DashboardController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Admin/DashboardController.php)
    *   [RENAME] [ExploreController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Web/ExploreController.php)
    *   [RENAME] [BannerController.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/app/Http/Controllers/Admin/BannerController.php)

---

### 🎨 Berkas Front-End (FE)
Pilih branch target FE yang relevan (misal `feature/fe-...` atau `feature/fe-layout`) untuk mem-push file berikut:

*   **Views & Layouts:**
    *   [MODIFY] [navigation.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/layouts/navigation.blade.php)
    *   [MODIFY] [app.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/layouts/app.blade.php)
*   **Halaman Fitur & Penulis:**
    *   [MODIFY] [show.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/chapters/show.blade.php)
    *   [MODIFY] [novel-card.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/components/novel-card.blade.php)
    *   [MODIFY] [index.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/writer/novels/index.blade.php)
    *   [MODIFY] [show.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/profile/show.blade.php)
    *   [MODIFY] [index.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/history/index.blade.php)
    *   [MODIFY] [inbox.blade.php](file:///c:/Users/ASUS/Desktop/prompter/team-prompter-novel-noctale/noctale/resources/views/user/inbox.blade.php)
