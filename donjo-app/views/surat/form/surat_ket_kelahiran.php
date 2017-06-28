<script>
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
.style4 {color: #FFFFFF}
.style6 {color: #FFFFFF; font-style: italic; }
</style>
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td width="108" class="side-menu">
				<fieldset>
<legend>Surat Administrasi</legend>
<div id="sidecontent2"  class="lmenu">
<ul>
<?php foreach($menu_surat AS $data){?>

        <li <?php  if($data['url_surat']==$lap){?>class="selected"<?php  }?>><a href="<?php echo site_url()?>surat/<?php echo $data['url_surat']?>"><?php echo unpenetration($data['nama'])?></a></li>
<?php }?>
</ul>
</div>
</fieldset>

	</td>
		<td width="937" style="background:#fff;padding:5px;">

<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Keterangan Kelahiran</h3>
</div>

  <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table width="919" class="form">
<tr>
<th width="210">NIK / Nama Ibu</th>
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
	<th bgcolor="#009933"><span class="style6">DATA KELAHIRAN :</span></th>
</tr>
<tr>
	<th>Nama Bayi </th>
	<td><input name="nama_bayi" type="text" class="inputbox required" size="100"/></td>
	</tr>
<tr>
	<th>Nik</th>
	<td><input name="nik_bayi" type="text" class="inputbox required" id="nik_bayi" size="70"/></td>
	</tr>
<tr>
	<th>Jenis Kelamin </th>
	<td><select name="jkbayi" size="1" id="jkbayi">
      <option>Silahkan Pilih Jenis Kelamin</option>
      <option value="Laki Laki">Laki Laki</option>
      <option value="Perempuan">Perempuan</option>
        </select></td>
		</tr>
<tr>
	<th>Hari / Tanggal Lahir / Jam </th>
	<td><input name="hari" readonly="readonly" type="text" class="inputbox required" size="10"/>
/
  <input name="tanggal" type="text" class="inputbox required datepicker" size="10"/>
/
<input name="jam" type="text" class="inputbox required" size="10"/></td>
</tr>
<tr>
  <th>Tempat Dilahirkan </th>
  <td><label>
    <input name="tempatlahirbayi" type="radio" id="radio2" value="1" />
    RS/RB </label>
    &nbsp;&nbsp;&nbsp;
    <input name="tempatlahirbayi" type="radio" value="2" id="radio3" />
    <label for="radio3">Puskesmas</label>
    <label> &nbsp;&nbsp;&nbsp;
      <input name="tempatlahirbayi" type="radio" value="3" id="radio4" />
      Rumah</label>
    &nbsp;&nbsp;&nbsp;
    <input name="tempatlahirbayi" type="radio" value="4" id="radio5" />
    <label for="radio5">Polindes
      &nbsp;&nbsp;&nbsp;
                  <input name="tempatlahirbayi" type="radio" value="5" id="radio6" />
      Lainnya</label></td>
</tr>
<tr>
  <th>Alamat Tempat Lahir </th>
  <td><input name="alamatlahirbayi" type="text" class="inputbox required" id="alamatlahirbayi" size="100"/></td>
</tr>
<tr>
  <th>Jenis Kelahiran </th>
  <td valign="baseline"><p>
    <label>
      <input name="jenislahir" type="radio" id="radio11" value="1" />
      Tunggal </label>
    &nbsp;&nbsp;&nbsp;
    <input name="jenislahir" type="radio" value="2" id="radio12" />
    <label for="radio12">Kembar 2 </label>
    <label>&nbsp;&nbsp;&nbsp;
      <input name="jenislahir" type="radio" value="3" id="radio13" />
      Kembar 3 </label>
    &nbsp;&nbsp;&nbsp;
    <input name="jenislahir" type="radio" value="4" id="radio14" />
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
      <input name="Kelahiranke" type="text" class="inputbox required" id="Kelahiranke" size="10"/>
        &nbsp;<em>*isi dengan angka </em></label></td>
</tr>
<tr>
  <th>Penolong Kelahiran </th>
  <td><label>
    <input name="penolong" type="radio" id="radio16" value="1" />
    Dokter </label>
    &nbsp;&nbsp;&nbsp;
    <input name="penolong" type="radio" value="2" id="radio8" />
    <label for="radio8">Bidan Perawat </label>
    <label>&nbsp;&nbsp;&nbsp;
      <input name="penolong" type="radio" value="3" id="radio9" />
      Dukun </label>
    &nbsp;&nbsp;&nbsp;
    <input name="penolong" type="radio" value="4" id="radio15" />
    <label for="radio10">Lainnya</label></td>
</tr>
<tr>
  <th>&nbsp;</th>
</tr>
<tr>
  <th bgcolor="#009933"><span class="style1 style4"><em>DATA PELAPOR :</em></span></th>
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
  <th>Umur</th>
  <td><input name="umur_pelapor" type="text" class="inputbox required" size="5"/>    
    tahun</td>
</tr>
<tr>
  <th>Jenis kelamin </th>
  <td><label>
    <input name="jkpelapor" type="radio" id="radio7" value="1" />
    Laki Laki</label>
    &nbsp;&nbsp;&nbsp;
    <input name="jkpelapor" type="radio" value="2" id="radio10" />
    <label for="radio10">Perempuan</label></td>
</tr>
<tr>
  <th>Pekerjaan</th>
  <td><input name="pek_pelapor" type="text" class="inputbox required" size="80"/></td>
</tr>
<tr>
  <th>Alamat</th>
  <td><p>Desa :
    <input name="desapelapor" type="text" class="inputbox required" id="desapelapor" value="Miau Merah" size="40"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kecamatan :
    <input name="kecpelapor" type="text" class="inputbox required" id="kecpelapor" value="Silat Hilir" size="40"/>
  </p>
      <p>&nbsp;</p>
    <p>Kab&nbsp;&nbsp;:
      <input name="kabpelapor" type="text" class="inputbox required" id="kabpelapor" value="Kapuas Hulu" size="40"/>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Provinsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
      <input name="provinsipelapor" type="text" class="inputbox required" id="provinsipelapor" value="Kalimantan Barat" size="40"/>
</p></td>
</tr>
<tr>
  <th>Hubungan Pelapor dengan Bayi</th>
  <td><input name="hubunganpelapor" type="text" class="inputbox required" id="hubunganpelapor" size="100"/></td>
</tr>
<tr>
  <th>&nbsp;</th>
</tr>
<tr>
  <th bgcolor="#009933"><span class="style1 style4"><em>SAKSI 1 </em></span></th>
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
  <td><input name="tmptlahirsaksi1" type="text" class="inputbox required" id="tmptlahirsaksi1" size="40"/> 
    Tanggal Lahir :      
      <input name="tgllhirsaksi1" type="text" class="inputbox required datepicker" id="tgllhirsaksi1" size="10"/></td>
</tr>
<tr>
  <th>Umur</th>
  <td><input name="umur_saksi1" type="text" class="inputbox required" id="umur_saksi1" size="5"/>
    tahun</td>
</tr>
<tr>
  <th>Jenis Kelamin </th>
  <td><label>
    <input name="jksaksi1" type="radio" id="radio17" value="1" />
    Laki Laki</label>
    &nbsp;&nbsp;&nbsp;
    <input name="jksaksi1" type="radio" value="2" id="radio18" />
    <label for="radio18">Perempuan</label></td>
</tr>
<tr>
  <th>Pekerjaan</th>
  <td><input name="peksaksi1" type="text" class="inputbox required" id="peksaksi1" size="100"/></td>
</tr>
<tr>
  <th>Alamat</th>
  <td><p>Desa :
    <input name="desasaksi1" type="text" class="inputbox required" id="desasaksi1" value="Miau Merah" size="40"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kecamatan :
    <input name="kecsaksi1" type="text" class="inputbox required" id="kecsaksi1" value="Silat Hilir" size="40"/>
  </p>
      <p>&nbsp;</p>
    <p>Kab&nbsp;&nbsp;:
      <input name="kabsaksi1" type="text" class="inputbox required" id="kabsaksi1" value="Kapuas Hulu" size="40"/>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Provinsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
      <input name="provinsisaksi1" type="text" class="inputbox required" id="provinsisaksi1" value="Kalimantan Barat" size="40"/>
</p></td>
</tr>
<tr>
  <th>&nbsp;</th>
</tr>
<tr>
  <th bgcolor="#009933"><span class="style4 style1"><em>SAKSI 2 </em></span></th>
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
  <th>Tempat Lahir</th>
  <td><input name="tmptlahirsaksi2" type="text" class="inputbox required" id="tmptlahirsaksi2" size="40"/>
Tanggal Lahir :
  <input name="tgllhirsaksi2" type="text" class="inputbox required datepicker" id="tgllhirsaksi2" size="10"/></td>
</tr>
<tr>
  <th>Umur</th>
  <td><input name="umur_saksi2" type="text" class="inputbox required" id="umur_saksi2" size="5"/>
    tahun</td>
</tr>
<tr>
  <th>Jenis Kelamin </th>
  <td><label>
    <input name="jksaksi2" type="radio" id="radio19" value="1" />
    Laki Laki</label>
    &nbsp;&nbsp;&nbsp;
    <input name="jksaksi2" type="radio" value="2" id="radio20" />
    <label for="radio20">Perempuan</label></td>
</tr>
<tr>
  <th>Pekerjaan</th>
  <td><input name="peksaksi2" type="text" class="inputbox required" id="peksaksi2" size="100"/></td>
</tr>
<tr>
  <th>Alamat</th>
  <td><p>Desa :
    <input name="desasaksi2" type="text" class="inputbox required" id="desasaksi2" value="Miau Merah" size="40"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kecamatan :
    <input name="kecsaksi2" type="text" class="inputbox required" id="kecsaksi2" value="Silat Hilir" size="40"/>
  </p>
      <p>&nbsp;</p>
    <p>Kab&nbsp;&nbsp;:
      <input name="kabsaksi2" type="text" class="inputbox required" id="kabsaksi2" value="Kapuas Hulu" size="40"/>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Provinsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
      <input name="provinsisaksi2" type="text" class="inputbox required" id="provinsisaksi2" value="Kalimantan Barat" size="40"/>
    </p></td>
</tr>
<tr>
  <th>&nbsp;</th>
  <td>&nbsp;</td>
</tr>
	<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
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
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
