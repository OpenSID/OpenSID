<?php
$tgl =  date('d_m_Y');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=data_penduduk_$tgl.csv");
header("Pragma: no-cache");
header("Expires: 0");
?>
Dusun,RW,RT,Nama,Nomor KK,Nomor NIK,Jenis Kelamin,Tempat Lahir,Tanggal Lahir,Agama,Pendidikan (dLm KK),Pendidikan (sdg ditemph),Pekerjaan,Kawin,Hub. Keluarga,Kewarganegaraan,NIK Ayah,Nama Ayah,NIK Ibu,Nama Ibu,Gol. Darah
<?php foreach($main as $data){ ?>
<?php echo strtoupper(ununderscore($data['dusun']))?>,<?php echo $data['rw']?>,<?php echo $data['rt']?>,"<?php echo strtoupper($data['nama'])?>",<?php echo $data['no_kk']?>,<?php echo $data['nik']?>,<?php echo $data['sex']?>,<?php echo $data['tempatlahir']?>,<?php echo $data['tanggallahir']?>,<?php echo $data['agama_id']?>,<?php echo $data['pendidikan_kk_id']?>,<?php echo $data['pendidikan_sedang_id']?>,<?php echo $data['pekerjaan_id']?>,<?php echo $data['status_kawin']?>,<?php echo $data['kk_level']?>,<?php echo $data['warganegara_id']?>,<?php echo $data['nik_ayah']?>,"<?php echo $data['nama_ayah']?>",<?php echo $data['nik_ibu']?>,"<?php echo $data['nama_ibu']?>",<?php echo $data['golongan_darah_id']."\n"; ?>
<?php }?>