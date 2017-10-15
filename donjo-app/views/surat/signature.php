<p>Salah satu fungsi aplikasi Sistem Informasi Desa (SID) adalah untuk mengoptimalkan pelayanan administrasi publik berbasis data. Pelayanan administrasi publik yang bisa dilakukan dengan aplikasi SID meliputi pelayanan olah data dan pelayanan olah dokumen/surat. Pelayanan olah data dapat dilakukan dengan memanfaatkan fungsi-fungsi statistik yang dapat dimanfaatkan untuk laporan dan rujukan pengambilan keputusan. Pelayanan olah dokumen bisa dilakukan dari data yang telah diolah dan/atau dari pengelolaan administrasi surat-menyurat.<br /></p>
<p>Aplikasi SID menghimpun seluruh data penduduk desa, sehingga bisa digunakan untuk data dasar pembuatan surat administrasi kependudukan. Pelayanan administrasi persuratan itu dapat dikelola oleh pemerintah desa di kantor pemerintah desa masing-masing. Tata cara pemanfaatan module cetak surat aplikasi SID dalam alur pelayanan publik di kantor desa secara garis besar dapat dilakukan dengan urutan sebagai berikut:</p>
<p>
	<ol>
		<li>Penduduk pemohon surat datang dengan membawa kartu identitas diri (KTP atau Kartu Keluarga/KK) dan diterima oleh staf pemerintah desa yang bertugas dalam pelayanan.</li>
		<li>Pastikan keberadaan dan status penduduk tersebut dalam database SID di Module "Penduduk". Gunakan fasilitas "Cari" dengan mengisikan nama atau NIK penduduk tersebut. Jika ada perubahan status, perbarui saat itu juga berdasarkan laporan penduduk yang bersangkutan. Jika penduduk tersebut belum terdaftar dalam database, masukkan data penduduk yang bersangkutan ke dalam SID merujuk pada dokumen kependudukan yang dimilikinya (wajib disertai dengan dokumen pendukung lainnya bagi penduduk pendatang/tinggal sementara). Jika data penduduk tersebut sudah tersimpan dalam SID, pembuatan surat dapat dilakukan.</li>
		<li>Klik module "Cetak Surat" untuk memulai pembuatan surat.</li>
		<li>Klik salah satu jenis surat yang akan dibuat, sesuaikan dengan jenis urusan yang diajukan oleh penduduk pemohon surat. Pastikan surat yang akan dicetak telah disiapkan templatenya di Menu "Master Surat"</li>
		<li>Isikan NIK / Nama, nomor surat, keterangan, dan hal lainnya sesuai kolom isian pada jenis surat yang dibuat.</li>
		<li>Pilih nama dan jabatan kepala desa atau perangkat desa yang berwenang melakukan pengesahan atas nama kepala desa.</li>
		<li>Setelah semua kolom terisi dengan benar, surat bisa langsung dicetak dengan klik tombol "Cetak" di bagian kanan bawah, atau bisa diedit lebih lanjut ke versi .doc dengan klik "Unduh" di bagian kanan bawah.</li>
		<li>Surat dapat dicetak 2 eksemplar, 1 eks. untuk penduduk pemohon surat dan 1 eks. untuk arsip pemerintah desa.</li>
		<li>Setiap jenis surat yang tercetak akan tersimpan data lognya di Menu "Surat Keluar"</li>
	</ol></p>
<hr>
<p>PANDUAN KODE EKSPORT SURAT</p>
<div style="background-color:#fefecc;padding:10px;border:1px solid #aaaa77;">
<p>PRINSIP FUNGSI<br />[kata_template] -&gt; akan digantikan dengan data di bawah ini (sebelah kanan)<br /><br />DATA SURAT<br />[kode_surat] -> $surat[kode_surat]<br>
[judul_surat]",strtoupper("surat ".$surat['nama']),$buffer);<br>
[JUDUL_SURAT]",strtoupper("surat ".$surat['nama']),$buffer);<br>
[tgl_surat] -> $tgl<br>
[tahun] -> $thn<br>
[nomor_surat] -> $input[nomor]<br>
[nomor_sorat] -> $input[nomor]<br>
[mulai_berlaku] -> $input[berlaku_dari]<br>
[tgl_akhir] -> $input[berlaku_sampai]<br>
[jabatan] -> $input[jabatan]<br>
[nama_pamong] -> $input[pamong]<br>
[keterangan] -> $input[keterangan]<br>
[keperluan] -> $input[keperluan]<br>
[tujuan] -> $input[tujuan]<br>
[kode_desa] -> $config[kode_desa]<br>
[nama_kab] -> $config[nama_kabupaten]<br>
[nama_kec] -> $config[nama_kecamatan]<br>
[nama_des] -> $config[nama_desa]<br>
[NAMA_KAB]",strtoupper($config['nama_kabupaten']),$buffer);<br>
[NAMA_KEC]",strtoupper($config['nama_kecamatan']),$buffer);<br>
[NAMA_DES]",strtoupper($config['nama_desa']),$buffer);<br>
[nama_kepala_camat] -> $config[nama_kepala_camat]<br>
[kades] -> $config[nama_kepala_desa]<br>
[nip_kepala_camat] -> $config[nip_kepala_camat]<br>
[pos] -> $config[kode_pos]<br>
[alamat_des] -> $config[alamat_kantor] Pos : $config[kode_pos]<br>
[alamat] -> RT $individu[rt] / RW $individu[rw] $individu[dusun]<br>
[rt] -> $individu[rt]<br>
[rw] -> $individu[rw]<br>
[dusun] -> $individu[dusun]<br>
[nama_ayah] -> $individu[nama_ayah]<br>
[nama_ibu] -> $individu[nama_ibu]<br>
[nik_ayah] -> $individu[ayah_nik]<br>
[nik_ibu] -> $individu[ibu_nik]<br>
[nama] -> $individu[nama]<br>
[sex] -> $individu[sex]<br>
[agama] -> $individu[agama]<br>
[status_kawin] -> $individu[status_kawin]<br>
[gol_darah] -> $individu[gol_darah]<br>
[pekerjaan] -> $individu[pekerjaan]<br>
[warga_negara] -> $individu[warganegara]<br>
[no_ktp] -> $individu[nik]<br>
[nik] -> $individu[nik]<br>
*usia -> $individu[umur] Tahun<br>
[usia] -> $individu[umur] Tahun<br>
[no_kk] -> $individu[no_kk]<br>
[ttl] -> $individu[tempatlahir]/$tgllhr<br>
[px_nama] -> $pxnama<br>
[px_nik] -> $pxnik<br>
[px_hubungan] -> $pxhubungan<br>
[px_usia] -> $pxusia<br>
[px_tempatlahir] -> $pxtglahir<br>
[px_tanggallahir] -> $pxtmplahir<br>
[px_ttl] -> $pxttl<br>
[px_ttl2] -> $pxttl2<br>
[no] -> $pxno<br>
[kode_surat] -> $surat[kode_surat]<br>
[judul_surat]",strtoupper("surat ".$surat['nama']),$buffer);<br>
[JUDUL_SURAT]",strtoupper("surat ".$surat['nama']),$buffer);<br>
[tgl_surat] -> $tgl<br>
[tahun] -> $thn<br>
[nomor_surat] -> $input[nomor]<br>
[nomor_sorat] -> $input[nomor]<br>
[mulai_berlaku] -> $input[berlaku_dari]<br>
[tgl_akhir] -> $input[berlaku_sampai]<br>
[jabatan] -> $input[jabatan]<br>
[nama_pamong] -> $input[pamong]<br>
[keterangan] -> $input[keterangan]<br>
[keperluan] -> $input[keperluan]<br>
[tujuan] -> $input[tujuan]<br>
[hari] -> $input[hari]<br>
[tgl_keg] -> $input[tgl_keg]<br>
[waktu] -> $input[waktu]<br>
[jenis_keg] -> $input[jenis_keg]<br>
[lokasi_keg] -> $input[lokasi_keg]<br>
[bidang_keg] -> $input[bidang_keg]<br>
[alamat_sekarang] -> $input[alamat_sekarang]<br>
[kode_desa] -> $config[kode_desa]<br>
[nama_kab] -> $config[nama_kabupaten]<br>
[nama_kec] -> $config[nama_kecamatan]<br>
[nama_des] -> $config[nama_desa]<br>
[NAMA_KAB]",strtoupper($config['nama_kabupaten']),$buffer);<br>
[NAMA_KEC]",strtoupper($config['nama_kecamatan']),$buffer);<br>
[NAMA_DES]",strtoupper($config['nama_desa']),$buffer);<br>
[nama_kepala_camat] -> $config[nama_kepala_camat]<br>
[kades] -> $config[nama_kepala_desa]<br>
[nip_kepala_camat] -> $config[nip_kepala_camat]<br>
[pos] -> $config[kode_pos]<br>
[alamat_des] -> $config[alamat_kantor] Pos : $config[kode_pos]<br>
[alamat] -> $individu[dusun] RT $individu[rt] / RW $individu[rw]<br>
[rt] -> $individu[rt]<br>
[rw] -> $individu[rw]<br>
[dusun] -> $individu[dusun]<br>
[nama_ayah] -> $individu[nama_ayah]<br>
[nama_ibu] -> $individu[nama_ibu]<br>
[nik_ayah] -> $individu[ayah_nik]<br>
[nik_ibu] -> $individu[ibu_nik]<br>
[nama] -> $individu[nama]<br>
[sex] -> $individu[sex]<br>
[agama] -> $individu[agama]<br>
[status_kawin] -> $individu[status_kawin]<br>
[gol_darah] -> $individu[gol_darah]<br>
[pekerjaan] -> $individu[pekerjaan]<br>
[pendidikan] -> $individu[pendidikan]<br>
[warga_negara] -> $individu[warganegara]<br>
[no_ktp] -> $individu[nik]<br>
[hubungan] -> $individu[hubungan]<br>
[nik] -> $individu[nik]<br>
*usia -> $individu[umur] Tahun<br>
[usia] -> $individu[umur] Tahun<br>
[no_kk] -> $individu[no_kk]<br>
[ttl] -> $individu[tempatlahir]/$tgllhr<br>
[nama_lahir] -> $input[nama_lahir]<br>
[nik_lahir] -> $input[nik_lahir]<br>
[nama_mati] -> $input[nama_mati]<br>
[nik_mati] -> $input[nik_mati]<br>
[nama_doc] -> $input[nama_doc]<br>
[dokumen] -> $input[dokumen]<br>
[alamat_nikah] -> $input[alamat_nikah]<br>
[tgl_nikah] -> $input[tgl_nikah]<br>
[nama_wali] -> $input[nama_wali]<br>
[nik_wali] -> $input[nik_wali]<br>
[alamat_wali] -> $input[alamat_wali]<br>
[kelamin_wali] -> $input[kelamin_wali]<br>
[hubungan_wali] -> $input[hubungan_wali]<br>
[agama_wali] -> $input[agama_wali]<br>
[tptlhr_wali] -> $input[tptlhr_wali]<br>
[tgllhr_wali] -> $input[tgllhr_wali]<br>
[pekerjaan_wali] -> $input[pekerjaan_wali]<br>
[sebab_wali] -> $input[sebab_wali]<br>
[ayah_penghasilan] -> $rp_ayah<br>
[ibu_penghasilan] -> $rp_ibu<br>
[total] -> $total<br>
[rphuruf] -> $rpt<br>
[sekolah] -> $input[sekolah]<br>
[jurusan] -> $input[jurusan]<br>
[kelas] -> $input[kelas]<br>
[ayah_alamat] -> $ayah[dusun] RT $ayah[rt] / RW $ayah[rw]<br>
[ayah_rt] -> $ayah[rt]<br>
[ayah_rw] -> $ayah[rw]<br>
[ayah_dusun] -> $ayah[dusun]<br>
[ayah_nama_ayah] -> $ayah[nama_ayah]<br>
[ayah_nama_ibu] -> $ayah[nama_ibu]<br>
[ayah_nik_ayah] -> $ayah[ayah_nik]<br>
[ayah_nik_ibu] -> $ayah[ibu_nik]<br>
[ayah_nama] -> $ayah[nama]<br>
[ayah_sex] -> $ayah[sex]<br>
[ayah_agama] -> $ayah[agama]<br>
[ayah_status_kawin] -> $ayah[status_kawin]<br>
[ayah_gol_darah] -> $ayah[gol_darah]<br>
[ayah_pekerjaan] -> $ayah[pekerjaan]<br>
[ayah_pendidikan] -> $ayah[pendidikan]<br>
[ayah_warga_negara] -> $ayah[warganegara]<br>
[ayah_no_ktp] -> $ayah[nik]<br>
[ayah_nik] -> $ayah[nik]<br>
*ayah_usia -> $ayah[umur] Tahun<br>
[ayah_usia] -> $ayah[umur] Tahun<br>
[ayah_no_kk] -> $ayah[no_kk]<br>
[ayah_ttl] -> $ayah[tempatlahir]/$ayah_tgllhr<br>
[ibu_alamat] -> $ibu[dusun] RT $ibu[rt] / RW $ibu[rw]<br>
[ibu_rt] -> $ibu[rt]<br>
[ibu_rw] -> $ibu[rw]<br>
[ibu_dusun] -> $ibu[dusun]<br>
[ibu_nama_ayah] -> $ibu[nama_ayah]<br>
[ibu_nama_ibu] -> $ibu[nama_ibu]<br>
[ibu_nik_ayah] -> $ibu[ibu_nik]<br>
[ibu_nik_ibu] -> $ibu[ibu_nik]<br>
[ibu_nama] -> $ibu[nama]<br>
[ibu_sex] -> $ibu[sex]<br>
[ibu_agama] -> $ibu[agama]<br>
[ibu_status_kawin] -> $ibu[status_kawin]<br>
[ibu_gol_darah] -> $ibu[gol_darah]<br>
[ibu_pekerjaan] -> $ibu[pekerjaan]<br>
[ibu_pendidikan] -> $ibu[pendidikan]<br>
[ibu_warga_negara] -> $ibu[warganegara]<br>
[ibu_no_ktp] -> $ibu[nik]<br>
[ibu_nik] -> $ibu[nik]<br>
*ibu_usia -> $ibu[umur] Tahun<br>
[ibu_usia] -> $ibu[umur] Tahun<br>
[ibu_no_kk] -> $ibu[no_kk]<br>
[ibu_ttl] -> $ibu[tempatlahir]/$ibu_tgllhr<br>
[kua] -> $input[kua]<br>
[nomor_nikah] -> $input[nomor_nikah]<br>
[hari_lahir] -> $input[hari_lahir]<br>
[tgl_lahir] -> $input[tgl_lahir]<br>
[jam_lahir] -> $input[jam_lahir]<br>
[tpt_lahir] -> $input[tpt_lahir]<br>
[sex_lahir] -> $input[sex_lahir]<br>
[hub_lapor] -> $input[hub_lapor]<br>
[hari_mati] -> $input[hari_mati]<br>
[tgl_mati] -> $input[tgl_mati]<br>
[jam_mati] -> $input[jam_mati]<br>
[tpt_mati] -> $input[tpt_mati]<br>
[sebab_mati] -> $input[sebab_mati]<br>
[nama_baru1] -> $input[nama_baru1]<br>
[tpt_baru1] -> $input[tpt_baru1]<br>
[tgl_baru1] -> $input[tgl_baru1]<br>
[hubkel_baru1] -> $input[hubkel_baru1]<br>
[nama_baru2] -> $input[nama_baru2]<br>
[tpt_baru2] -> $input[tpt_baru2]<br>
[tgl_baru2] -> $input[tgl_baru2]<br>
[hubkel_baru2] -> $input[hubkel_baru2]<br>
[nama_baru3] -> $input[nama_baru3]<br>
[tpt_baru3] -> $input[tpt_baru3]<br>
[tgl_baru3] -> $input[tgl_baru3]<br>
[hubkel_baru3] -> $input[hubkel_baru3]<br>
[nama_baru4] -> $input[nama_baru4]<br>
[tpt_baru4] -> $input[tpt_baru4]<br>
[tgl_baru4] -> $input[tgl_baru4]<br>
[hubkel_baru4] -> $input[hubkel_baru4]<br>
[nama_baru5] -> $input[nama_baru5]<br>
[tpt_baru5] -> $input[tpt_baru5]<br>
[tgl_baru5] -> $input[tgl_baru5]<br>
[hubkel_baru5] -> $input[hubkel_baru5]<br>
[nama_baru6] -> $input[nama_baru6]<br>
[tpt_baru6] -> $input[tpt_baru6]<br>
[tgl_baru6] -> $input[tgl_baru6]<br>
[hubkel_baru6] -> $input[hubkel_baru6]<br>
[saksi_baru1] -> $input[saksi_baru1]<br>
[saksi_baru2] -> $input[saksi_baru2]<br>
[pria_status] -> $input[pria_status]<br>
[wanita_status] -> $input[wanita_status]<br>
[istri_lama] -> $input[istri_lama]<br>
[nama_calon] -> $input[nama_calon]<br>
[binti] -> $input[binti]<br>
[alamat_calon] -> $input[alamat_calon]<br>
[tpt_lahir_calon] -> $input[tpt_lahir_calon]<br>
[tgl_lahir_calon] -> $input[tgl_lahir_calon]<br>
[warga_negara_calon] -> $input[warga_negara_calon]<br>
[agama_calon] -> $input[agama_calon]<br>
[kerja_calon] -> $input[kerja_calon]<br>
[hari_nikah] -> $input[hari_nikah]<br>
[tgl_nikah] -> $input[tgl_nikah]<br>
[jam_nikah] -> $input[jam_nikah]<br>
[tpt_nikah] -> $input[tpt_nikah]<br>
[mahar_nikah] -> $input[mahar_nikah]<br>
[kartu_beda] -> $input[kartu_beda]<br>
[identitas_beda] -> $input[identitas_beda]<br>
[nama_beda] -> $input[nama_beda]<br>
[tempatlahir] -> $input[tempatlahir]<br>
[tgllahir] -> $input[tgllahir]<br>
</p>
<i>Referensi file -> surat_model.php baris ke 277</i>
</div><br>
<p>Demikian panduan pembuatan surat dengan menggunakan aplikasi SID. Selamat menyelenggarakan pelayanan administrasi publik.</p>