Di rilis ini, versi 22.08-premium-rev02 [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.
Terima kasih pada [untuk diisi] yang terus berkontribusi.

#### Penambahan Fitur

#### Perbaikan BUG

1.[#5452](https://github.com/OpenSID/OpenSID/issues/5452) Menghilangkan tampil tag ["desa"] pada halaman periksa.
2.[#5457](https://github.com/OpenSID/OpenSID/issues/5457) Menambahkan inputan manual untuk nama desa jika tidak ada respon dari pantau.
3.[#1188](https://github.com/OpenSID/premium/issues/1188) Menambahkan notifikasi beberapa modul gagal muat jika tidak terhubung ke internet.
4.[#5472](https://github.com/OpenSID/OpenSID/issues/5472) Mengatasi data entri NIK warga luar desa tidak bisa input lebih dari satu kali di menu buku tanah.
5.[#5471](https://github.com/OpenSID/OpenSID/issues/5471) Memperbaiki perintah group by pada Laporan rincian realisasi hasil dari impor siekudes.
6.[#5456](https://github.com/OpenSID/OpenSID/issues/5456) Memperbaiki buat surat tinymce yang tidak berhasil generate pdf.
7.[#1202](https://github.com/OpenSID/premium/issues/1202) Menghapus pengecekan url tidak valid di identitas desa.
8.[#5475](https://github.com/OpenSID/OpenSID/issues/5475) Perbaiki last login tidak diperbarui setelah login.
9.[#5478](https://github.com/OpenSID/OpenSID/issues/5478) Memperbaiki jJudul pada jumlah covid desa tidak tampil.
10.[#5477](https://github.com/OpenSID/OpenSID/issues/5477) Memperbaiki notifikasi saat mengembalikan status dasar penduduk.
11.[#5486](https://github.com/OpenSID/OpenSID/issues/5486) Memperbaiki pencarian nama ibu dari tambah anak ke 2 dari menu kia.
12.[#5487](https://github.com/OpenSID/OpenSID/issues/5487) Memperbaiki tampilan konsisten infrastruktur desa yang ditampilkan pada web dan admin.
13.[#5497](https://github.com/OpenSID/OpenSID/issues/5497) Memperbaiki tampilan inputan klasifikasi pindah pada form lampiran F-1.03.
14.[#5504](https://github.com/OpenSID/OpenSID/issues/5504) Memperbaiki tampilan data pada tabel status gizi anak menjadi singkatan huruf.
15.[#5513](https://github.com/OpenSID/OpenSID/issues/5513) Memperbaiki tampilan pengaturan margin.
16.[#5498](https://github.com/OpenSID/OpenSID/issues/5498) Memperbaiki laporan analisis saat di unduh datanya tidak tampil.
17.[#1187](https://github.com/OpenSID/premium/issues/1187) Cek koneksi saat memanggil asset external agar dapat opensid jalan tanpa internet.
18.[#5476](https://github.com/OpenSID/OpenSID/issues/5476) Memperbaiki luas tanah tidak bisa tersimpan di menu bumindes tanah kas desa.
19.[#5522](https://github.com/OpenSID/OpenSID/issues/5522) Memperbaiki footer surat mepet dengan isi surat.
20.[#5482](https://github.com/OpenSID/OpenSID/issues/5482) Merubah nama file foto aparatur desa yang menampilkan NIK.

#### Perubahan Teknis

1. Teknis rapikan penulisan code seeder data awal.
2. Pengecekan validasi premium dimulai dari identitas desa.
3. Merapikan select option pada pilih tahun id menu status desa.
4. Menambahkan required pada form edit album.
5. Mengembalikan fungsi pencarian cetak surat.
6. Menambahkan penjelasan tambahan di impor analisis.
