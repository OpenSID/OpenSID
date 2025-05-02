<h1 align="center">Selamat Datang di Tema DeNava!</h1>

<p align="center">
  <img style="max-width: 100%;" width="500" alt="Tema DeNava" src="https://user-images.githubusercontent.com/46939846/182389893-dce046f9-d335-4ed4-af18-8f3cfa84a515.png">
</p>

## Tentang Tema DeNava
Tema DeNava adalah salah satu tema yang digunakan untuk halaman website OpenSID (Sistem Informasi Desa).

Sebelumnya, ada tema Natra dan tema DeNatra, yang merupakan kependekan dari Natai Raya. Tema Natra adalah pemenang Sayembara Tema Web OpenSID 2019.

## Fitur Lapak (Manual)
Untuk menambahkan fitur Lapak secara manual, ikuti langkah-langkah berikut:

### Edit File `lapak.json`
1. Buka file `lapak.json` di direktori `denava/partials/lapak/`.

2. Temukan dan edit bagian berikut sesuai petunjuk:
   - `"aktif": false` (ubah `false` menjadi `true` untuk menampilkan Lapak Desa bawaan tema)
   - `"id": "1"` (pastikan id bersifat unik dan urut jika Anda menambahkan produk baru)
   - `"gambar": "akar_pinang.jpg"` (nama gambar, mp4, atau file wave/wom yang ada di folder `denava/assets/lapak`)
   - `"hp": "628115222660"` (nomor HP Penjual)
   - `"lat": "-2.665093"` (Titik koordinat Latitude)
   - `"lng": "111.709899"` (Titik koordinat Langitude)

### Tampilan Produk
Tampilan produk bisa berupa gambar, mp4, atau bahkan embed dari YouTube. Misalnya, jika Anda ingin menampilkan produk dengan gambar, Anda cukup menyertakan nama gambar dalam file `lapak.json`.

Jika Anda ingin menampilkan produk menggunakan video YouTube, cukup tambahkan ID video YouTube ke dalam file `lapak.json`. Misalnya, jika tautan YouTube adalah https://youtu.be/R2-vCJisOPY, Anda hanya perlu menambahkan ID video, yaitu `R2-vCJisOPY`.

## Keterangan Tambahan
Berikut adalah langkah-langkah untuk melakukan perubahan pada Tema DeNava:

### Tambahkan Script pada Bagian Meta Web
Jika Anda ingin menambahkan script pada bagian meta web (sebelum tag `</head>`), ikuti langkah berikut:
- Sisipkan pada file `denava/partials/module_top.php`.

### Tambahkan Script pada Bagian Footer Web
Untuk menambahkan script pada bagian footer web (sebelum tag `</body>`), lakukan hal berikut:
- Sisipkan pada file `denava/partials/module_bottom.php`.

### Sesuaikan Sidebar
Jika Anda perlu menyesuaikan sidebar (tombol bagian atas), ikuti langkah ini:
- Edit file `denava/commons/sidebar.php` sesuai kebutuhan.

### Sesuaikan Banner di Halaman Utama
Untuk mengubah link tujuan dan link gambar pada banner di halaman utama, lakukan hal berikut:
- Edit file `denava/partials/home/banner.php` sesuai kebutuhan.

### Sesuaikan hitung mundur manual yang Muncul di Bagian Atas
Jika Anda ingin mengatur hitung mundur manual, ikuti instruksi ini:
- Edit file `denava/partials/event/event.json`.
- Ubah `"hitungmundur"` menjadi `true` untuk menampilkan countdown.

### Ubah Waktu pada Countdown Otomatis
Jika Anda perlu merubah jam pada countdown otomatis, lakukan langkah berikut:
- Edit file `denava/partials/event/countdown.php`.
- Sesuaikan waktu pada variabel `$customTime` di baris 5 sesuai kebutuhan.

### Aktifkan Autoplay Gambar Artikel pada Halaman Utama
Untuk mengaktifkan autoplay gambar artikel pada halaman utama, ikuti langkah ini:
- Edit file `denava/partials/artikel/index.php`.
- Ubah `"autoPlay": false` menjadi `"autoPlay": true` pada baris 70.

Pastikan untuk menyimpan perubahan yang Anda buat setelah mengedit file-file tersebut. Semoga panduan ini membantu!
