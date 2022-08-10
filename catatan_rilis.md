Di rilis ini, versi 22.08-premium-beta01 [untuk diisi]. Rilis ini juga berisi penambahan fitur dan perbaikan lain yang diminta Komunitas SID.
Terima kasih pada Irvan1609 yang terus berkontribusi.

#### Penambahan Fitur

1. [#5232](https://github.com/OpenSID/OpenSID/issues/5232) Penambahan pengaturan default jenis peta yang digunakan.
2. [#5308](https://github.com/OpenSID/OpenSID/issues/5308) Penambahan unduh template rtf bawaan sistem yang sudah di ubah.
3. [#5266](https://github.com/OpenSID/OpenSID/issues/5266) Penambahan tampilan dan pencarian tag id card pada data pemilih.
4. [#5304](https://github.com/OpenSID/OpenSID/issues/5304) Menambahkan format surat tinymce kepermohonan surat layanan mandiri.
5. [#5270](https://github.com/OpenSID/OpenSID/issues/5270) Sesuaikan alur pemeriksaan surat.
6. [#5273](https://github.com/OpenSID/OpenSID/issues/5273) Menambahkan notifikasi ketika ada surat permintaan surat untuk diperiksa atau ditandatangani.
7. [#5412](https://github.com/OpenSID/OpenSID/issues/5412) Menambahkan fitur untuk menentukan kepala desa dan sekdes hanya di pemerintah desa.
8. [#5233](https://github.com/OpenSID/OpenSID/issues/5233) Menambahkan menu tupoksi pada menu pemerintah desa.
9. [#4962](https://github.com/OpenSID/OpenSID/issues/4962) Menambahkan filter data pencarian pendudukan bukan peserta program bantuan.
10. [#5301](https://github.com/OpenSID/OpenSID/issues/5301) Menambahkan input ibu dengan status di kk selain istri tidak bisa ( menantu dll ) dan anak ( cucu dll) di menu kia.
11. [#4464](https://github.com/OpenSID/OpenSID/issues/4464) Menambahkan opsi sembunyikan luas area di map.
12. [#5271](https://github.com/OpenSID/OpenSID/issues/5271) Menambahkan penyesuaian alur pemeriksaan surat pada layanan mandiri.
13. [#1161](https://github.com/OpenSID/premium/issues/1161) Penyesuaian fungsi tombol a.n dan u.b pada pengaturan perangkat desa.

#### Perbaikan BUG

1.[#5452](https://github.com/OpenSID/OpenSID/issues/5452) Menghilangkan tampil tag ["desa"] pada halaman periksa.
2.[#5457](https://github.com/OpenSID/OpenSID/issues/5457) Menambahkan inputan manual untuk nama desa jika tidak ada respon dari pantau.
3.[#1188](https://github.com/OpenSID/premium/issues/1188) Menambahkan notifikasi beberapa modul gagal muat jika tidak terhubung ke internet.
4.[#5472](https://github.com/OpenSID/OpenSID/issues/5472) Mengatasi data entri NIK warga luar desa tidak bisa input lebih dari satu kali di menu buku tanah.
5.[#5471](https://github.com/OpenSID/OpenSID/issues/5471) Memperbaiki perintah group by pada Laporan rincian realisasi hasil dari impor siekudes.
6.[#5456](https://github.com/OpenSID/OpenSID/issues/5456) Memperbaiki buat surat tinymce yang tidak berhasil generate pdf.
7.[#1202](https://github.com/OpenSID/premium/issues/1202) Menghapus pengecekan url tidak valid di identitas desa.
8.[#5475](https://github.com/OpenSID/OpenSID/issues/5475) Perbaiki last login tidak diperbarui setelah login.

#### Perubahan Teknis

1. Teknis rapikan penulisan code seeder data awal.
2. Pengecekan validasi premium dimulai dari identitas desa.
3. Merapikan select option pada pilih tahun id menu status desa.
4. Menmbahkan required pada form edit album.
