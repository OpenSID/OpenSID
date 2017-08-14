<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<style>
table.form.detail th{
	padding:5px;
	background:#fafafa;
	border-right:1px solid #eee;
}
table.form.detail td{
	padding:5px;
}
table.form span.judul{
	padding-left: 10px;
	padding-right: 5px;
}
</style>
<script>
function _calculateAge(birthday) { // birthday is a date (dd-mm-yyyy)
  var parts =birthday.split('-');
  // Ubah menjadi format ISO yyyy-mm-dd
  // please put attention to the month (parts[0]), Javascript counts months from 0:
  // January - 0, February - 1, etc
  // https://stackoverflow.com/questions/5619202/converting-string-to-date-in-js
  var birthdate = new Date(parts[2],parts[1]-1,parts[0]);
  var ageDifMs = (new Date()).getTime() - birthdate.getTime();
  var ageDate = new Date(ageDifMs); // miliseconds from epoch
  return Math.abs(ageDate.getUTCFullYear() - 1970);
}

$(function(){
  var nik = {};
  nik.results = [
  <?php foreach($perempuan as $data){?>
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

/* set otomatis hari */
$('input[name=tanggal]').change(function(){
  var hari = {
    0 : 'Minggu', 1 : 'Senin', 2 : 'Selasa', 3 : 'Rabu', 4 : 'Kamis', 5 : 'Jumat', 6 : 'Sabtu'
  };
  var t = $(this).datepicker('getDate');
  var i = t.getDay();
  $(this).closest('td').find('[name=hari]').val(hari[i]);
})

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
.style6 {
  color: #FFFFFF;
  background-color: #1d93dd;
  font-style: italic;
}
</style>

<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td width="937" style="background:#fff;padding:5px;">

<div class="content-header"></div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Keterangan Kelahiran</h3>
</div>

  <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table width="919" class="form">
<tr>
<th width="120">NIK / Nama Ibu</th>
<td width="665">
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form></tr>
<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>"  class="inputbox required">
<?php if($individu){ //bagian info setelah terpilih?>
  <?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
<?php }?>
<tr>
<th>Nomor Surat</th>
<td><input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span></td>
</tr>
<tr>
	<th>&nbsp;</th>
	</tr>
<tr>
	<th class="style6">DATA KELAHIRAN :</th>
</tr>
<tr>
	<th>Nama Bayi </th>
	<td><input name="nama_bayi" type="text" class="inputbox required" size="70"/></td>
	</tr>
<tr>
	<th>NIK</th>
	<td><input name="nik_bayi" type="text" class="inputbox required" id="nik_bayi" size="70"/>
	  <em>*isi tanda - jika belum memiliki NIK</em> </td>
</tr>
<tr>
	<th>Jenis Kelamin </th>
	<td>
    <input type="hidden" name="nama_sex">
    <select name="sex" class="required" id="sex" onchange="$('input[name=nama_sex]').val($(this).find(':selected').text());">
      <option value="">Pilih Jenis Kelamin</option>
      <?php foreach($sex as $data){?>
        <option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
	<th>Hari / Tanggal / Jam </th>
	<td><input name="hari" readonly="readonly" type="text" class="inputbox required" size="10"/>
/
  <input name="tanggal" type="text" class="inputbox required datepicker" id="tanggal" size="11"/>
/
<em>*Isi waktu kelahiran etc : 08:00</em>
<input name="jam" type="text" class="inputbox required" size="10"/></td>
</tr>
<tr>
  <th>Tempat Dilahirkan </th>
  <td><label>
    <input name="tempatlahirbayi" type="radio" id="radio2" value="1" />
    RS/RB </label> <span class="judul"></span><input name="tempatlahirbayi" type="radio" value="2" id="radio3" /><label for="radio3">Puskesmas</label>
    <label>
      <span class="judul"></span><input name="tempatlahirbayi" type="radio" value="3" id="radio4" /> Rumah</label>
    <span class="judul"></span><input name="tempatlahirbayi" type="radio" value="4" id="radio5" />
    <label for="radio5">Polindes
    <span class="judul"></span><input name="tempatlahirbayi" type="radio" value="5" id="radio6" />
      Lainnya</label></td>
</tr>
<tr>
  <th>Alamat Tempat Lahir </th>
  <td><input name="alamat_lahir_bayi" type="text" class="inputbox required" id="alamat_lahir_bayi" size="100"/></td>
</tr>
<tr>
  <th>Jenis Kelahiran </th>
  <td valign="baseline"><p>
    <label>
      <input name="jenislahir" type="radio" id="radio11" value="1" />
      Tunggal </label>
    <span class="judul"></span>
    <input name="jenislahir" type="radio" value="2" id="radio12" />
    <label for="radio12">Kembar 2</label><label>
      <span class="judul"></span><input name="jenislahir" type="radio" value="3" id="radio13" />
      Kembar 3</label>
    <span class="judul"></span><input name="jenislahir" type="radio" value="4" id="radio14" />
    <label for="radio14">Kembar 4 </label>
    <br />
    <label></label>
    <br />
  </p></td>
</tr>
<tr>
  <th>Kelahiran Anak Ke </th>
  <td><label></label>
      <label for="radio10">
      <input name="Kelahiranke" type="text" class="inputbox required" id="Kelahiranke" size="8"/>
      &nbsp;<em>*isi dengan angka </em></label></td>
</tr>
<tr>
  <th>Penolong Kelahiran </th>
  <td><label>
    <input name="penolong" type="radio" id="radio16" value="1" />
    Dokter<span class="judul"></span></label>
    <input name="penolong" type="radio" value="2" id="radio8" />
    <label for="radio8">Bidan Perawat </label>
    <label>
      <span class="judul"></span><input name="penolong" type="radio" value="3" id="radio9" />
      Dukun </label><span class="judul"></span>
    <input name="penolong" type="radio" value="4" id="radio15" />
    <label for="radio10">Lainnya</label></td>
</tr>
<tr>
  <th>&nbsp;</th>
</tr>
<tr>
  <th class="style6">DATA PELAPOR :</th>
</tr>
<tr>
  <th>Nama</th>
  <td><input name="nama_pelapor" type="text" class="inputbox required" size="100"/>  </td>
</tr>
<tr>
  <th>NIK</th>
  <td><input name="nik_pelapor" type="text" class="inputbox required" size="70"/></td>
</tr>
<tr>
  <th>Tempat Lahir </th>
  <td><input name="tempat_lahir_pelapor" type="text" class="inputbox required" id="tempat_lahir_pelapor" size="40"/>
<span class="judul"> Tanggal Lahir : </span>
  <input name="tanggal_lahir_pelapor" type="text" class="inputbox required datepicker" id="tanggal_lahir_pelapor" size="11" onchange="$('input[name=umur_pelapor]').val(_calculateAge($(this).val()));"/>
  <span class="judul"> Umur : </span>
  <input name="umur_pelapor" readonly="readonly" type="text" class="inputbox required" size="5"/>
    tahun</td>
</tr>
<tr>
  <th>Jenis kelamin </th>
  <td>
    <select name="jkpelapor" class="required" id="jkpelapor">
      <option value="">Pilih Jenis Kelamin</option>
      <?php foreach($sex as $data){?>
        <option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
      <?php }?>
    </select>
    <span class="judul"> Pekerjaan </span>
    <select name="pekerjaanpelapor" class="required" id="pekerjaanpelapor">
      <option value="">Pilih Pekerjaan</option>
      <?php  foreach($pekerjaan as $data){?>
        <option value="<?php echo $data['nama']?>"><?php echo $data['nama']?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Alamat</th>
  <td><p>Desa <span class="judul"> : </span>
      <input name="desapelapor" type="text" class="inputbox required" id="desapelapor" size="40"/>
      <span class="judul"> Kecamatan : </span>
      <input name="kecpelapor" type="text" class="inputbox required" id="kecpelapor" size="40"/>
  </p>
    <p>&nbsp;</p>
    <p>Kab<span class="judul"> &nbsp;:&nbsp; </span>
        <input name="kabpelapor" type="text" class="inputbox required" id="kabpelapor" size="40"/>
     <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;:  </span>
      <input name="provinsipelapor" type="text" class="inputbox required" id="provinsipelapor" size="40"/>
</p>    </td>
</tr>
<tr>
  <th>Hubungan Pelapor dengan Bayi</th>
  <td><input name="hubungan_pelapor" type="text" class="inputbox required" id="hubungan_pelapor" size="100"/></td>
</tr>
<tr>
  <th>&nbsp;</th>
</tr>
<tr>
  <th class="style6">DATA SAKSI 1</th>
</tr>
<tr>
  <th>Nama</th>
  <td><input name="nama_saksi1" type="text" class="inputbox required" id="nama_saksi1" size="100"/></td>
</tr>
<tr>
  <th>NIK</th>
  <td><input name="nik_saksi1" type="text" class="inputbox required" id="nik_saksi1" size="70"/></td>
  </tr>
<tr>
  <th>Tempat Lahir  </th>
  <td><input name="tempat_lahir_saksi1" type="text" class="inputbox required" id="tempat_lahir_saksi1" size="40"/>
    <span class="judul"> Tanggal Lahir : </span>
      <input name="tanggal_lahir_saksi1" type="text" class="inputbox required datepicker" id="tanggal_lahir_saksi1" size="11" onchange="$('input[name=umur_saksi1]').val(_calculateAge($(this).val()));" />
        <span class="judul"> Umur : </span>
      <input name="umur_saksi1" readonly="readonly" type="text" class="inputbox required" id="umur_saksi1" size="5"/>
tahun</td>
</tr>
<tr>
  <th>Jenis Kelamin </th>
  <td>
    <select name="jksaksi1" class="required" id="jksaksi1">
      <option value="">Pilih Jenis Kelamin</option>
      <?php foreach($sex as $data){?>
        <option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
      <?php }?>
    </select>
    <span class="judul">Pekerjaan </span>
    <select name="pekerjaansaksi1" class="required" id="pekerjaansaksi1">
      <option value="">Pilih Pekerjaan</option>
      <?php  foreach($pekerjaan as $data){?>
        <option value="<?php echo $data['nama']?>"><?php echo $data['nama']?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Alamat</th>
  <td><p>Desa <span class="judul"> : </span>
      <input name="desasaksi1" type="text" class="inputbox required" id="desasaksi1" size="40"/>
      <span class="judul"> Kecamatan : </span>
      <input name="kecsaksi1" type="text" class="inputbox required" id="kecsaksi1" size="40"/>
</p>
      <p>&nbsp;</p>
      <p>Kab<span class="judul"> &nbsp;:&nbsp; </span>
          <input name="kabsaksi1" type="text" class="inputbox required" id="kabsaksi1" size="40"/>
          <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;: </span>
          <input name="provinsisaksi1" type="text" class="inputbox required" id="provinsisaksi1" size="40"/>
      </p></td>
</tr>
<tr>
  <th>&nbsp;</th>
</tr>
<tr>
  <th class="style6">DATA SAKSI 2</th>
</tr>
<tr>
  <th>Nama</th>
  <td><input name="nama_saksi2" type="text" class="inputbox required" id="nama_saksi2" size="100"/></td>
</tr>
<tr>
  <th>NIK</th>
  <td><input name="nik_saksi2" type="text" class="inputbox required" id="nik_saksi2" size="70"/></td>
</tr>
<tr>
  <th>Tempat Lahir </th>
  <td><input name="tempat_lahir_saksi2" type="text" class="inputbox required" id="tempat_lahir_saksi2" size="40"/>
    <span class="judul"> Tanggal Lahir : </span>
    <input name="tanggal_lahir_saksi2" type="text" class="inputbox required datepicker" id="tanggal_lahir_saksi2" size="11" onchange="$('input[name=umur_saksi2]').val(_calculateAge($(this).val()));"/>
    <span class="judul"> Umur : </span>
    <input name="umur_saksi2" readonly="readonly" type="text" class="inputbox required" id="umur_saksi2" size="5"/>
    tahun</td>
</tr>
<tr>
  <th>Jenis Kelamin </th>
  <td>
    <select name="jksaksi2" class="required" id="jksaksi2">
      <option value="">Pilih Jenis Kelamin</option>
      <?php foreach($sex as $data){?>
        <option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
      <?php }?>
    </select>
    <span class="judul">Pekerjaan </span>
    <select name="pekerjaansaksi2" class="required" id="pekerjaansaksi2">
      <option value="">Pilih Pekerjaan</option>
      <?php  foreach($pekerjaan as $data){?>
      <option value="<?php echo $data['nama']?>"><?php echo $data['nama']?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Alamat</th>
  <td><p>Desa <span class="judul"> : </span>
          <input name="desasaksi2" type="text" class="inputbox required" id="desasaksi2" size="40"/>
          <span class="judul"> Kecamatan : </span>
          <input name="kecsaksi2" type="text" class="inputbox required" id="kecsaksi2" size="40"/>
  </p>
      <p>&nbsp;</p>
    <p>Kab<span class="judul"> &nbsp;:&nbsp; </span>
          <input name="kabsaksi2" type="text" class="inputbox required" id="kabsaksi2" size="40"/>
          <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;: </span>
          <input name="provinsisaksi2" type="text" class="inputbox required" id="provinsisaksi2" size="40"/>
    </p></td>
	</tr>
<tr>
  <th>&nbsp;</th>
  </tr>
<tr>
  <th class="style6">PENANDA TANGAN</th>
  <td><p>&nbsp;</p>      </td>
</tr>
<tr>
  <th>&nbsp;</th>
  <td>&nbsp;</td>
</tr>
<tr>
  <th>Lokasi Disdukcapil <?php echo ucwords($this->setting->sebutan_kabupaten)?></th>
  <td><input name="lokasi_disdukcapil" type="text" class="inputbox required" size="40"/></td>
</tr>
	<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
        </table>
    </div>

    <div class="ui-layout-south panel bottom">
        <div class="left">
            <a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>

							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
            </div>
        </div>
    </div> </form>
</div></td></tr></table>
</div>
