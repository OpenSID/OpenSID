<?php
  $tgl =  date('d_m_Y');
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=data_penduduk_$tgl.csv");
  header("Pragma: no-cache");
  header("Expires: 0");
?>
Dusun,RW,RT,Nama,Nomor KK,Nomor NIK,Jenis Kelamin,Tempat Lahir,Tanggal Lahir,Agama,Pendidikan (dLm KK),Pendidikan (sdg ditemph),Pekerjaan,Kawin,Hub. Keluarga,Kewarganegaraan,NIK Ayah,Nama Ayah,NIK Ibu,Nama Ibu,Gol. Darah
<?php foreach ($main as $data): ?>
  <?= strtoupper($data['dusun'])?>,<?= $data['rw']?>,<?= $data['rt']?>,"<?= strtoupper($data['nama'])?>",<?= $data['no_kk']?>,<?= $data['nik']?>,<?= $data['sex']?>,<?= $data['tempatlahir']?>,<?= $data['tanggallahir']?>,<?= $data['agama_id']?>,<?= $data['pendidikan_kk_id']?>,<?= $data['pendidikan_sedang_id']?>,<?= $data['pekerjaan_id']?>,<?= $data['status_kawin']?>,<?= $data['kk_level']?>,<?= $data['warganegara_id']?>,<?= $data['nik_ayah']?>,"<?= $data['nama_ayah']?>",<?= $data['nik_ibu']?>,"<?= $data['nama_ibu']?>",<?= $data['golongan_darah_id']."\n"; ?>
<?php endforeach; ?>