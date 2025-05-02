
* JADWAL SHALAT

OpenSID Umum :
- Mengaktifkan jadwal shalat silahkan buka file : desa/config/config.php :
   Kemudian tambahkan kode : $config['kode_kota'] = 687;   
- Ganti angka 687 dengan kode kota / kabupaten anda
   Cari Kode kota/kabupaten di link ini : https://github.com/rohmanudin05/jadwal-shalat/wiki/ID-KOTA-Jadwal-Shalat
   
OpenSID Premium : 
- Pilih Menu : Admin Web - Tema
- Pilih icon Gear (Pengaturan Tema) dan kemudian pilih kota/kabupaten
- Simpan

-------------------------------------------------------------------

* LOGO
Secara default, logo yang tampil adalah yang di setting dari admin OpenSID.
Jika menggunakan logo format gif, silahkan upload file logo ke folder : desa, dengan nama dan ekstensi (harus) : logo.gif
Logo yang diupload akan terus tampil meskipun upgrade versi Batuah karena lokasi file tersimpan di folder desa, bukan di folder tema Batuah

-------------------------------------------------------------------

* WARNA TEMA
Untuk mengganti warna default silahkan buka file : desa/config/config.php
Kemudian tambahkan kode : $config['hijau'] = true;

Silahkan ganti tulisan ['hijau'] dengan kode warna yang tersedia : 01/02/03 sampai 08
Contoh : $config['01'] = true;

Untuk OpenSID Premium, pada pengturan tema sudah tersedia pilihan warna sesuai keinginan

-------------------------------------------------------------------

* LAYOUT HOME & SIDEBAR
Untuk edit urutan layout bagian tengah halaman home, buka file : temabatuah/commons/home_layout.php
pindahkan salahsatu item dengan menseleksi - cut dan pastekan di posisi yang diinginkan 

Untuk edit urutan layout bagian sidebar, silahkan lakukan via halaman admin OpenSID di pengaturan Widget

-------------------------------------------------------------------

* HITUNG MUNDUR
Untuk mengaktifkan dan menonaktifkan fitur Hitung Mundur, buka file : temabatuah/event/moment.json
ganti : "hitungmundur": false, (tidak aktif)
menjadi "hitungmundur": true, (aktif)

Kemudian edit atau sesuaikan isi bagian dibawah :
"countdown": [
    {
	  "title": "Memperingati Hari Pahlawan",
	  "deskripsi": "10 November 1945 - 10 November 2022",
	  "tanggal": "10", ----> Tanpa disertai angka 0 jika tanggal 1 digit
	  "bulan": "11", ----> Tanpa disertai angka 0 jika bulan 1 digit
      "tahun": "2022"
    }
  ]

-------------------------------------------------------------------

* BANNER
Untuk menampilkan banner misal untuk ucapan moment tertentu silahkan buka : temabatuah/event/moment.json
Aktifkan salah satu dengan mengganti tulisan false menjadi true: 
"defaultevent": false, (tulisan dan gambar/logo sudah disetting default)
"customevent": false, (untuk banner statis / hanya gambar)

Dan buka file : temabatuah/event/moment.php
Edit dan sesuai kan : 
<?php 
$date = date('Y-m-d');
$start_date = date('Y-m-d', strtotime('2022-8-20')); ------> tanggal mulai tampil (tahun-bulan-tanggal)
$end_date = date('Y-m-d', strtotime('2022-9-10')); ?> -----> tanggal berakhir tampil (tahun-bulan-tanggal)
<?php if($date >= $start_date && $date <= $end_date){
	$this->load->view("$folder_themes/event/moment_item.php"); 
}
?>

-------------------------------------------------------------------

* LINK TAMBAHAN
Cara aktifkan fitur Link Tambahan di halaman Home :
1. Buka file : temabatuah/linkplus/link.json
2. Pada baris ke 2 ganti "aktif": false, menjadi "aktif": true,

Cara Edit :

1. Buka file : temabatuah/linkplus/link.json

2. Dan edit seperti contoh ini:
   "id": "1", ---> biarkan / tidak perlu dirubah
   "judulitem": "edit bagian ini", ---> Jangan terlalu panjang, contoh : "judulitem": "Buku Tamu",
   "gambar": "edit bagian ini", ---> Sesuaikan dengan nama file/gambar icon, contoh : "gambar": "namafile.png",
   "link": "edit bagian ini" ---> Contoh : "link": "https://opendesa.id/"
   
2. untuk icon, silahkan upload file/gambar pada : temabatuah/linkplus/icon
   sesuaikan nama file/gambar dengan isi di file temabatuah/link.json (format : jpg, png)
   
-------------------------------------------------------------------

* LAPAK HOME
Untuk aktifkan Lapak di halaman Home :
Edit file temabatuah/lapak.json
Pada baris ke 2 : "aktif": false,
ganti dengan : "aktif": true,

Edit dan tambah Lapak di Home :
Edit file temabatuah/lapak.json
dan ubah isi dari item Lapak :
    {
      "id": "edit bagian ini",
      "produk": "edit bagian ini",
      dst... hingga
    },
	
Untuk menambah, copy :
	{
      "id": "item",
      "produk": "item",
      dst... hingga
    },	
kemudian pastekan seperti contoh yang sudah ada.
Pastikan setiap kode } diakhiri dengan tanda koma = },
Dan pastikan produk Lapak terakhir tidak diakhiri dengan tanda koma = }

untuk gambar lapak, silahkan upload file/gambar pada : temabatuah/assets/lapak
sesuaikan nama file/gambar dengan isi di file lapak.json

-------------------------------------------------------------------

* VIDEO
Untuk menampilkan video di home silahkan buka file : temabatuah/commons/home_layout.php
Copy kode : <?php $this->load->view("$folder_themes/plus/video"); ?>
Pastekan pada baris : 15
menjadi <?php $this->load->view("$folder_themes/plus/video"); ?>

-------------------------------------------------------------------

* APPID
Untuk mengganti APPID dengan APPID anda sendiri buka file temabatuah/plus/fbappid.php

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v3.2&appId=328618625940706&autoLogAppEvents=1"></script>

Ganti angka :  328618625940706 dengan APPID anda

-------------------------------------------------------------------

* SDGS Portabel
Untuk mengaktifkan fitur SDGS Portabel silahkan buka file :
temabatuah/commons/home_layout.php
copy kode : <?php $this->load->view("$folder_themes/plus/sdgs"); ?>
pastekan pada baris 11

Selanjutnya buka file temabatuah/plus/sdgs.php
dan lakukan pengeditan dibawah setiap kode : <!-- Narasi --> (Sesuaikan dengan judul)
dan edit link yang berada dibawah kode :
<!-- Start Link -->
<div class="sdgs-link flexcenter">

Gunakan format ini jika link adalah artikel internal
<a href="<?= site_url('isi url setelah nama domain') ?>">
contoh : <a href="<?= site_url('artikel/2022/1/24/desa') ?>">

Gunakan format ini jika link eksternal
<a href="isi link eksternal" target="blank">
contoh : <a href="https://opendesa.id/" target="blank">

Jika tidak ada link silahkan hapus mulai dari kode : <!-- Start Link --> sampai <!-- End Link -->