<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];

$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($individu){?>'<?php echo $individu['nik']?> - <?php echo spaceunpenetration($individu['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
$('#'+'main').submit();
}
});

});
</script>


<style>
table.form.detail th{
padding:5px;
background:#fafafa;
border-right:1px solid #eee;
}
table.form.detail td{
padding:5px;
}
</style>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:5px;">
<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Keterangan Izin Orang Tua /Suami/Istri</h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>NIK / Nama yang memberi Izin</th>
<td>
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>
</tr>

<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>">
<?php if($individu){ //bagian info setelah terpilih?>
  <?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
<?php }?>
<tr>
<th>Nomor Surat</th>
<td>
<input name="nomor" type="text" class="inputbox required" size="6"/>
</td>
</tr>

<th></th>
<th>Saya selaku sebagai
<select name="selaku" id="selaku" onclick="GetVal()">
<option value="Saya selaku sebagai" selected="selected">Saya selaku sebagai</option>
<option value="ORANG TUA">Orang Tua</option>
<option value="SUAMI">Suami</option>
<option value="ISTRI">Istri</option>
<option value="KELUARGA">Keluarga</option>
</select>

mengizinkan serta menyetujui
<select name="mengizinkan" id="mengizinkan" onclick="GetVal()">
<option value="mengizinkan serta menyetujui" selected="selected">mengizinkan serta menyetujui</option>
<option value="SUAMI">Suami</option>
<option value="ISTRI">Istri</option>
<option value="ANAK">Anak</option>
<option value="KELUARGA">Keluarga</option>
</select>
dengan identitas tersebut di bawah ini : </th>

<tr>
<th>Nama Lengkap</th>
<td>
<input name="nama" type="text" class="inputbox required" size="30"/>
</td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td>
<input name="tempatlahir" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir" type="text" class="inputbox required datepicker" size="10"/>
</td>
</tr>

<tr>
<th>Agama</th>
<td>
<select name="agama" class="inputbox required">
<option value="">Pilih Agama</option>
<option value="Islam">Islam</option>
<option value="Kristen">Kristen</option>
<option value="Katholik">Katholik</option>
<option value="Hindu">Hindu</option>
<option value="Budha">Budha</option>
<option value="Konghuchu">Konghuchu</option>
<option value="Aliran Kepercayaan">Aliran Kepercayaan</option>
</select>
</td>
</tr>

<tr>
<th>Pekerjaan</th>
<td>
	  <select name="pekerjaann" class="inputbox required">
	  <option value="">Pilih Pekerjaan</option>
	  <option value="Belum/ Tidak Bekerja">BELUM/ TIDAK BEKERJA</option>
      <option value="Mengurus Rumah Tangga">MENGURUS RUMAH TANGGA</option>
      <option value="Pelajar/ Mahasiswa">PELAJAR/MAHASISWA</option>
      <option value="Pensiunan">PENSIUNAN</option>
      <option value="Pegawai Negeri Sipil">PEGAWAI NEGERI SIPIL</option>
      <option value="Tentara Nasional Indonesia">TENTARA NASIONAL INDONESIA</option>
      <option value="Kepolisian RI">KEPOLISIAN RI</option>
      <option value="Perdagangan">PERDAGANGAN</option>
      <option value="Petani/ Pekebun">PETANI/PEKEBUN</option>
      <option value="Peternak">PETERNAK</option>
      <option value="Nelayan/ Perikanan">NELAYAN/PERIKANAN</option>
      <option value="Industri">INDUSTRI</option>
      <option value="Kontruksi">KONSTRUKSI</option>
      <option value="Transportasi">TRANSPORTASI</option>
      <option value="Karyawan Swasta">KARYAWAN SWASTA</option>
      <option value="Karyawan BUMN">KARYAWAN BUMN</option>
      <option value="Karyawan BUMD">KARYAWAN BUMD</option>
      <option value="Karyawan Honorer">KARYAWAN HONORER</option>
      <option value="Buruh Harian Lepas">BURUH HARIAN LEPAS</option>
      <option value="Buruh Tani/ Perkebunan">BURUH TANI/PERKEBUNAN</option>
      <option value="Buruh Nelayan/ Perikanan">BURUH NELAYAN/PERIKANAN</option>
      <option value="Buruh Peternakan">BURUH PETERNAKAN</option>
      <option value="Pembantu Rumah Tangga">PEMBANTU RUMAH TANGGA</option>
      <option value="Tukang Cukur">TUKANG CUKUR</option>
      <option value="Tukang Listrik">TUKANG LISTRIK</option>
      <option value="Tukang Batu">TUKANG BATU</option>
      <option value="Tukang Kayu">TUKANG KAYU</option>
      <option value="Tukang Sol Sepatu">TUKANG SOL SEPATU</option>
      <option value="Tukang Las/ Pandai Besi">TUKANG LAS/PANDAI BESI</option>
      <option value="Tukang Jahit">TUKANG JAHIT</option>
      <option value="Tukang Gigi">TUKANG GIGI</option>
      <option value="Penata Rias">PENATA RIAS</option>
      <option value="Penata Busana">PENATA BUSANA</option>
      <option value="Penata Rambut">PENATA RAMBUT</option>
      <option value="Mekanik">MEKANIK</option>
      <option value="Seniman">SENIMAN</option>
      <option value="Tabib">TABIB</option>
      <option value="Paraji">PARAJI</option>
      <option value="Perancang Busana">PERANCANG BUSANA</option>
      <option value="Penterjemah">PENTERJEMAH</option>
      <option value="Imam Masjid">IMAM MASJID</option>
      <option value="Pendeta">PENDETA</option>
      <option value="Pastor">PASTOR</option>
      <option value="Wartawan">WARTAWAN</option>
      <option value="Ustadz/ Mubaligh">USTADZ/MUBALIGH</option>
      <option value="Juru Masak">JURU MASAK</option>
      <option value="Promotor Acara">PROMOTOR ACARA</option>
      <option value="Anggota DPR-RI">ANGGOTA DPR-RI</option>
      <option value="Anggota DPD">ANGGOTA DPD</option>
      <option value="Anggota BPK">ANGGOTA BPK</option>
      <option value="Presiden">PRESIDEN</option>
      <option value="Wakil Presiden">WAKIL PRESIDEN</option>
      <option value="Anggota Mahkamah Konstitusi">ANGGOTA MAHKAMAH KONSTITUSI</option>
      <option value="Anggota Kabinet/ Kementerian">ANGGOTA KABINET/KEMENTERIAN</option>
      <option value="Duta Besar">DUTA BESAR</option>
      <option value="Gubernur">GUBERNUR</option>
      <option value="Wakil Gubernur">WAKIL GUBERNUR</option>
      <option value="Bupati">BUPATI</option>
      <option value="Wakil Bupati">WAKIL BUPATI</option>
      <option value="Walikota">WALIKOTA</option>
      <option value="Wakil Walikota">WAKIL WALIKOTA</option>
      <option value="Anggota DPRD Provinsi">ANGGOTA DPRD PROVINSI</option>
      <option value="Anggota DPRD Kabupaten/ Kota">ANGGOTA DPRD KABUPATEN/KOTA</option>
      <option value="Dosen">DOSEN</option>
      <option value="Guru">GURU</option>
      <option value="Pilot">PILOT</option>
      <option value="Pengacara">PENGACARA</option>
      <option value="Notaris">NOTARIS</option>
      <option value="Arsitek">ARSITEK</option>
      <option value="Akuntan">AKUNTAN</option>
      <option value="Konsultan">KONSULTAN</option>
      <option value="Dokter">DOKTER</option>
      <option value="Bidan">BIDAN</option>
      <option value="Perawat">PERAWAT</option>
      <option value="Apoteker">APOTEKER</option>
      <option value="Psikiater/ Psikolog">PSIKIATER/PSIKOLOG</option>
      <option value="Penyiar Televisi">PENYIAR TELEVISI</option>
      <option value="Penyiar Radio">PENYIAR RADIO</option>
      <option value="Pelaut">PELAUT</option>
      <option value="Peneliti">PENELITI</option>
      <option value="Sopir">SOPIR</option>
      <option value="Pialang">PIALANG</option>
      <option value="Paranormal">PARANORMAL</option>
      <option value="Pedagang">PEDAGANG</option>
      <option value="Perangkat Desa">PERANGKAT DESA</option>
      <option value="Kepala Desa">KEPALA DESA</option>
      <option value="Biarawati">BIARAWATI</option>
      <option value="Wiraswasta">WIRASWASTA</option>
      <option value="Lainnya">LAINNYA</option>
	  </select>
</td>
</tr>

<tr>
<th>Kewarganegaraan</th>
<td>
<select name="wni" id="wni" onclick="GetVal()">
<option value="Pilih Kewarganegaraan" selected="selected">Pilih Kewarganegaraan</option>
<option value="WNI">WNI</option>
<option value="WNA">WNA</option>
<option value="DUA KEWARGANEGARAAN">DUA KEWARGANEGARAAN</option>
</select>
</tr>

<tr>
<th>Alamat Lengkap</th>
<td><input name="alamatt" type="text" class="inputbox" size="45"/> RT <input name="rtt" type="text" class="inputbox required" size="5"/> RW <input name="rww" type="text" class="inputbox required" size="5"/> Dusun <input name="dusunn" type="text" class="inputbox required" size="25"/>
</tr>
<tr>
<th></th>
<th>Desa <input name="desaa" type="text" class="inputbox required" size="35"/<option value="<?php echo unpenetration($desa['nama_desa'])?>">  Kecamatan <input name="kecc" type="text" class="inputbox required" size="15"/<option value="<?php echo unpenetration($desa['nama_kecamatan'])?>">  Kabupaten <input name="kabb" type="text" class="inputbox required" size="15"/<option value="<?php echo unpenetration($desa['nama_kabupaten'])?>"></td>
</tr>

<tr>
<th>Negara Tujuan</th>
<td>
<input name="negara_tujuan" type="text" class="inputbox required" size="30"/> Diisi dengan Negara yang dituju sprt: Malaysia, Korea, dll
</td>
</tr>

<tr>
<th>Nama PPTKIS</th>
<td>
<input name="nama_pptkis" type="text" class="inputbox required" size="30"/> *) Nama PT atau Perusahaan
</td>
</tr>

<tr>
<th>Status Pekerjaan/ TKI/ TKW</th>
<td>
<select name="pekerja" id="pekerja" onclick="GetVal()">
<option value="Pilih Status Pekerjaan/ TKI/ TKW" selected="selected">Pilih Status Pekerjaan/ TKI/ TKW</option>
<option value="Tenaga Kerja Indonesia (TKI)">Tenaga Kerja Indonesia (TKI)</option>
<option value="Tenaga Kerja Wanita (TKW)">Tenaga Kerja Wanita (TKW)</option>
</select>
</tr>

 <tr>
<th>Atas Nama</th>
<td>
<select name="atas_nama"  type="text" class="inputbox">
<option value="">Atas Nama</option>
<option value=""> </option>
<option value="An. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?>"> An. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?> </option>
<option value="Ub. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?>"> Ub. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?> </option>
</select>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo unpenetration($data['jabatan'])?></option>
<?php }?>
</select>
<tr>
<th>Staf Pemerintah Desa</th>
<td>
<select name="pamong"  class="inputbox required">
<option value="">Pilih Staf Pemerintah Desa</option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo unpenetration($data['pamong_nama'])?></font> (<?php echo unpenetration($data['jabatan'])?>)</option>
<?php }?>
<tr>
<th>N I P</th>
<td>
<select name="pamong_nip"  type="text" class="inputbox">
<option value="">Pilih No NIP</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo($data['pamong_nip'])?></option>
<?php }?>
</select>

</td>
</tr>
</td>
</tr>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>

<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
<?php if (file_exists("surat/$url/$url.rtf")) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>