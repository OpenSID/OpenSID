<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<script language="javascript" type="text/javascript">

  function ubah_saksi1(asal){
    $('#saksi1').val(asal);
    if(asal == 1){
      $('.saksi1_desa').show();
      $('.saksi1_luar_desa').hide();
    } else {
      $('.saksi1_desa').hide();
      $('.saksi1_luar_desa').show();
      $('#id_saksi1_validasi').val('*'); // Hapus $id_wanita
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('saksi1');
  }

  function ubah_saksi2(asal){
    $('#saksi2').val(asal);
    if(asal == 1){
      $('.saksi2_desa').show();
      $('.saksi2_luar_desa').hide();
    } else {
      $('.saksi2_desa').hide();
      $('.saksi2_luar_desa').show();
      $('#id_saksi2_validasi').val('*'); // Hapus $id_wanita
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('saksi2');
  }

  function ubah_pelapor(asal){
    $('#pelapor').val(asal);
    if(asal == 1){
      $('.pelapor_desa').show();
      $('.pelapor_luar_desa').hide();
    } else {
      $('.pelapor_desa').hide();
      $('.pelapor_luar_desa').show();
      $('#id_pelapor_validasi').val('*'); // Hapus $id_wanita
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('pelapor');
  }

  function ubah_bayi(asal){
    $('#bayi').val(asal);
    if(asal == 1){
      $('.bayi_desa').show();
      $('.bayi_luar_desa').hide();
    } else {
      $('.bayi_desa').hide();
      $('.bayi_luar_desa').show();
      $('#id_bayi_validasi').val('*'); // Hapus $id_bayi
      // Hapus data kelahiran
      $('.data_lahir').val('');
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('bayi');
  }

  function ubah_ibu(asal){
    $('#ibu').val(asal);
    if(asal == 1){
      $('.ibu_desa').show();
      $('.ibu_luar_desa').hide();
    } else {
      $('.ibu_desa').hide();
      $('.ibu_luar_desa').show();
      $('#id_ibu_validasi').val('*'); // Hapus $id_wanita
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('ibu');
  }

  function nomor_surat(nomor){
    $('#nomor').val(nomor);
  }

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
    var ibu = {};
    ibu.results = [
      <?php foreach($perempuan as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_ibu').flexbox(ibu, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: <?php if($ibu){?>'<?php echo $ibu['nik']?> - <?php echo spaceunpenetration($ibu['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('ibu');
        submit_form_ambil_data();
      }
    });
  });

  $(function(){
    var saksi1 = {};
    saksi1.results = [
      <?php foreach($penduduk as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_saksi1').flexbox(saksi1, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: <?php if($saksi1){?>'<?php echo $saksi1['nik']?> - <?php echo $saksi1['nama']?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('saksi1');
        submit_form_ambil_data();
      }
    });
  });

  $(function(){
    var saksi2 = {};
    saksi2.results = [
      <?php foreach($penduduk as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_saksi2').flexbox(saksi2, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: <?php if($saksi2){?>'<?php echo $saksi2['nik']?> - <?php echo $saksi2['nama']?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('saksi2');
        submit_form_ambil_data();
      }
    });
  });

  $(function(){
    var pelapor = {};
    pelapor.results = [
      <?php foreach($penduduk as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_pelapor').flexbox(pelapor, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: <?php if($pelapor){?>'<?php echo $pelapor['nik']?> - <?php echo $pelapor['nama']?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('pelapor');
        submit_form_ambil_data();
      }
    });
  });

  $(function(){
    var bayi = {};
    bayi.results = [
      <?php foreach($anak as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_bayi').flexbox(bayi, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: <?php if($bayi){?>'<?php echo $bayi['nik']?> - <?php echo $bayi['nama']?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('bayi');
        submit_form_ambil_data();
      }
    });
  });

  function submit_form_ambil_data(){
    $('input').removeClass('required');
    $('select').removeClass('required');
    $('#'+'validasi').attr('action','');
    $('#'+'validasi').attr('target','');
    $('#'+'validasi').submit();
  }

  function buat_penduduk_baru(){
    $('input[name=penduduk_baru]').val('1');
    $('input').removeClass('required');
    $('select').removeClass('required');
    $('.data_lahir').addClass('required');
    $('.data_lahir').addClass('required');
    $('#'+'validasi').attr('action','');
    $('#'+'validasi').attr('target','');
    $('#'+'validasi').submit();
  }

  $('document').ready(function(){

    /* pergi ke bagian halaman sesudah mengisi warga desa */
    location.hash = "#" + $('input[name=anchor]').val();

    /* set otomatis hari */
    $('input[name=tanggallahir]').change(function(){
      var hari = {
        0 : 'Minggu', 1 : 'Senin', 2 : 'Selasa', 3 : 'Rabu', 4 : 'Kamis', 5 : 'Jumat', 6 : 'Sabtu'
      };
      var t = $(this).datepicker('getDate');
      var i = t.getDay();
      $(this).closest('td').find('[name=hari]').val(hari[i]);
    });
    $('input[name=tanggallahir]').trigger('change');

    /* set nama_sex dari pilihan */
    $('input[name=nama_sex]').val($('#sex').find(':selected').text())

  });

</script>


<style>
  table.form span.judul{
    padding-left: 10px;
    padding-right: 5px;
  }
  .grey {
    background-color: lightgrey;
  }

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
  label { padding-left: 5px; padding-right: 15px; }
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
    <form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">

      <input id="nomor" name="nomor" type="hidden" value="<?php echo $_SESSION['post']['nomor']; ?>"/>
      <input name="anchor" type="hidden" value="<?php echo $anchor; ?>"/>

      <table width="919" class="form">
        <tr>
          <td colspan="2" style="height: auto;">
            <div class="box-perhatian">
              <p><strong>Form ini menghasilkan:<br><br>
              (1) Surat Keterangan Kelahiran<br>
              (2) Permohonan Penyelesaian Akta Kelahiran<br>
              (3) Lampiran F-2.01 SURAT KETERANGAN KELAHIRAN bagi warga yang akan dibuatkan akta kelahiran<br><br>
              Pastikan semua biodata orang tua warga yang lahir, pelapor dan saksi-saksi sudah lengkap sebelum mencetak surat dan lampiran.<br>
              Untuk melengkapi data itu, ubah data warga yang bersangkutan di form isian penduduk di modul Penduduk.<br><br>
              PERHATIAN: setelah Export Doc, pengisian/perubahan data kelahiran akan direkam ke database penduduk.
              </strong></p>
            </div>
          </td>
        </tr>

        <tr>
          <th>Nomor Surat</th>
          <td>
            <input name="nomor" type="text" class="inputbox required" size="12" value="<?php echo $_SESSION['post']['nomor']; ?>" onchange="nomor_surat(this.value);"/>
            <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
          </td>
        </tr>


          <!-- IBU Kandung -->
          <tr><th><a name="ibu"></a></th><td>&nbsp;</td></tr>
          <tr>
            <th class="grey">DATA IBU KANDUNG</th>
            <td class="grey">
              <div class="uiradio">
                <input type="radio" id="ibu_1" name="ibu" value="1" <?php if(!empty($ibu)){echo 'checked';}?> onchange="ubah_ibu(this.value);">
                <label for="ibu_1">Dari Database Penduduk</label>
                <input type="radio" id="ibu_2" name="ibu" value="2" <?php if(empty($ibu)){echo 'checked';}?> onchange="ubah_ibu(this.value);">
                <label id="label_ibu_2" for="ibu_2">Tidak Terdata</label>
              </div>
            </td>
          </tr>

          <tr class="ibu_desa" <?php if (empty($ibu)) echo 'style="display: none;"'; ?>>
            <th colspan="2">DATA IBU DARI DATABASE</th>
          </tr>
          <tr class="ibu_desa" <?php if (empty($ibu)) echo 'style="display: none;"'; ?>>
            <th width="120">NIK / Nama Ibu</th>
            <td width="665">
              <div id="id_ibu" name="id_ibu"></div>
            </td>
          </tr>

          <?php if($ibu): ?>
            <?php  //bagian info setelah terpilih
              $individu = $ibu;
              include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
            ?>
            <?php if(!empty($ayah)): ?>
              <tr>
                <th colspan='2'>DATA AYAH DARI DATABASE</th>
              </tr>
              <tr>
                <th width='120'>NIK / Nama Ayah</th>
                <td width='665'>
                  <?php echo $ayah['nik'].' / '.$ayah['nama'] ?>
                </td>
              </tr>
              <?php
                $individu = $ayah;
                include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
              ?>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (empty($ibu)) : ?>
            <tr class="ibu_luar_desa">
              <th class="style6">DATA IBU TIDAK TERDATA</th>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Nama</th>
              <td><input name="nama_ibu" type="text" class="inputbox required" size="100" value="<?php echo $_SESSION['post']['nama_ibu']?>"/>  </td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>NIK</th>
              <td><input name="nik_ibu" type="text" class="inputbox required" size="70" value="<?php echo $_SESSION['post']['nik_ibu']?>"/></td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Tempat Lahir </th>
              <td><input name="tempat_lahir_ibu" type="text" class="inputbox required" id="tempat_lahir_ibu" size="40" value="<?php echo $_SESSION['post']['tempat_lahir_ibu']?>"/>
            <span class="judul"> Tanggal Lahir : </span>
              <input name="tanggal_lahir_ibu" type="text" class="inputbox required datepicker" id="tanggal_lahir_ibu" size="11" value="<?php echo $_SESSION['post']['tanggal_lahir_ibu']?>"onchange="$('input[name=umur_ibu]').val(_calculateAge($(this).val()));"/>
              <span class="judul"> Umur : </span>
              <input name="umur_ibu" readonly="readonly" type="text" class="inputbox required" size="5" value="<?php echo $_SESSION['post']['umur_ibu']?>"/>
                tahun</td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Pekerjaan</th>
              <td>
                <input type="hidden" name="pekerjaanid_ibu">
                <select name="pekerjaanibu" class="required" id="pekerjaanibu" onchange="$('input[name=pekerjaanid_ibu]').val($(this).find(':selected').data('pekerjaanid'));">
                  <option value="">Pilih Pekerjaan</option>
                  <?php  foreach($pekerjaan as $data){?>
                    <option value="<?php echo $data['nama']?>" data-pekerjaanid="<?php echo $data['id']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaanibu']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
              </td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Tanggal Perkawinan</th>
              <td>
                <input name="tanggalperkawinan_ibu" type="text" class="inputbox required datepicker" size="11" value="<?php echo $_SESSION['post']['tanggalperkawinan_ibu']?>"/>
              </td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Alamat</th>
              <td>
                <p>Alamat <span class="judul"> : </span>
                  <input name="alamat_ibu" type="text" class="inputbox required" id="alamat_ibu" size="40" value="<?php echo $_SESSION['post']['alamat_ibu']?>"/>
                  <span class="judul"> RT : </span>
                  <input name="rt_ibu" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rt_ibu']?>"/>
                  <span class="judul"> RW : </span>
                  <input name="rw_ibu" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rw_ibu']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Desa <span class="judul"> : </span>
                  <input name="desaibu" type="text" class="inputbox required" id="desaibu" size="40" value="<?php echo $_SESSION['post']['desaibu']?>"/>
                  <span class="judul"> Kecamatan : </span>
                  <input name="kecibu" type="text" class="inputbox required" id="kecibu" size="40" value="<?php echo $_SESSION['post']['kecibu']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Kab<span class="judul"> &nbsp;:&nbsp; </span>
                  <input name="kabibu" type="text" class="inputbox required" id="kabibu" size="40" value="<?php echo $_SESSION['post']['kabibu']?>"/>
                  <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;:  </span>
                  <input name="provinsiibu" type="text" class="inputbox required" id="provinsiibu" size="40" value="<?php echo $_SESSION['post']['provinsiibu']?>"/>
                </p>
              </td>
            </tr>
          <?php endif; ?>

          <?php if(empty($ibu) or ($ibu and empty($ayah))): ?>
            <tr class="ibu_luar_desa">
              <th class="style6">DATA AYAH TIDAK TERDATA</th>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Nama</th>
              <td><input name="nama_ayah" type="text" class="inputbox required" size="100" value="<?php echo $_SESSION['post']['nama_ayah']?>"/>  </td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>NIK</th>
              <td><input name="nik_ayah" type="text" class="inputbox required" size="70" value="<?php echo $_SESSION['post']['nik_ayah']?>"/></td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Tempat Lahir </th>
              <td><input name="tempat_lahir_ayah" type="text" class="inputbox required" id="tempat_lahir_ayah" size="40" value="<?php echo $_SESSION['post']['tempat_lahir_ayah']?>"/>
            <span class="judul"> Tanggal Lahir : </span>
              <input name="tanggal_lahir_ayah" type="text" class="inputbox required datepicker" id="tanggal_lahir_ayah" size="11" value="<?php echo $_SESSION['post']['tanggal_lahir_ayah']?>"onchange="$('input[name=umur_ayah]').val(_calculateAge($(this).val()));"/>
              <span class="judul"> Umur : </span>
              <input name="umur_ayah" readonly="readonly" type="text" class="inputbox required" size="5" value="<?php echo $_SESSION['post']['umur_ayah']?>"/>
                tahun</td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Pekerjaan</th>
              <td>
                <input type="hidden" name="pekerjaanid_ayah">
                <select name="pekerjaanayah" class="required" id="pekerjaanayah" onchange="$('input[name=pekerjaanid_ayah').val($(this).find(':selected').data('pekerjaanid'));">
                  <option value="">Pilih Pekerjaan</option>
                  <?php  foreach($pekerjaan as $data){?>
                    <option value="<?php echo $data['nama']?>" data-pekerjaanid="<?php echo $data['id']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaanayah']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
              </td>
            </tr>
            <tr class="ibu_luar_desa">
              <th>Alamat</th>
              <td>
                <p>Alamat <span class="judul"> : </span>
                  <input name="alamat_ayah" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['alamat_ayah']?>"/>
                  <span class="judul"> RT : </span>
                  <input name="rt_ayah" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rt_ayah']?>"/>
                  <span class="judul"> RW : </span>
                  <input name="rw_ayah" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rw_ayah']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Desa <span class="judul"> : </span>
                  <input name="desaayah" type="text" class="inputbox required" id="desaayah" size="40" value="<?php echo $_SESSION['post']['desaayah']?>"/>
                  <span class="judul"> Kecamatan : </span>
                  <input name="kecayah" type="text" class="inputbox required" id="kecayah" size="40" value="<?php echo $_SESSION['post']['kecayah']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Kab<span class="judul"> &nbsp;:&nbsp; </span>
                  <input name="kabayah" type="text" class="inputbox required" id="kabayah" size="40" value="<?php echo $_SESSION['post']['kabayah']?>"/>
                  <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;:  </span>
                  <input name="provinsiayah" type="text" class="inputbox required" id="provinsiayah" size="40" value="<?php echo $_SESSION['post']['provinsiayah']?>"/>
                </p>
              </td>
            </tr>
          <?php endif; ?>

          <!-- BAYI -->
          <tr><th><a name="bayi"></a></th><td>&nbsp;</td></tr>
          <tr>
            <th class="grey">DATA KELAHIRAN</th>
            <td class="grey">
              <div class="uiradio">
                <input type="radio" id="bayi_1" name="bayi" value="1" <?php if(!empty($bayi)){echo 'checked';}?> onchange="ubah_bayi(this.value);">
                <label for="bayi_1">Dari Database Penduduk</label>
                <input type="radio" id="bayi_2" name="bayi" value="2" <?php if(empty($bayi)){echo 'checked';}?> onchange="ubah_bayi(this.value);">
                <label id="label_bayi_2" for="bayi_2">Belum Terdata</label>
              </div>
            </td>
          </tr>

          <tr class="bayi_desa" <?php if (empty($bayi)) echo 'style="display: none;"'; ?>>
            <th colspan="2">DATA KELAHIRAN DARI DATABASE</th>
          </tr>
          <tr class="bayi_desa" <?php if (empty($bayi)) echo 'style="display: none;"'; ?>>
            <th class="indent">NIK / Nama</th>
            <td>
              <div id="id_bayi" name="id_bayi"></div>
              <?php if($bayi){ //bagian info setelah terpilih
                  $individu = $bayi;
                  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
              }?>
            </td>
          </tr>

          <?php if (empty($bayi)) : ?>
            <tr class="bayi_luar_desa">
            	<th class="style6">DATA KELAHIRAN BELUM TERDATA</th>
            </tr>
            <tr class="bayi_luar_desa">
            	<th>Nama Yang Lahir </th>
            	<td><input name="nama_bayi" type="text" class="inputbox required data_lahir" size="70" value="<?php echo $_SESSION['post']['nama_bayi']?>"/></td>
            	</tr>
            <tr class="bayi_luar_desa">
            	<th>NIK</th>
            	<td><input name="nik_bayi" type="text" class="inputbox required data_lahir" id="nik_bayi" size="70" value="<?php echo $_SESSION['post']['nik_bayi']?>"/>
            	  <em>*isi tanda - jika belum memiliki NIK</em> </td>
            </tr>
            <tr class="bayi_luar_desa">
            	<th>Jenis Kelamin </th>
            	<td>
                <input type="hidden" name="nama_sex">
                <select name="sex" class="required data_lahir" id="sex" onchange="$('input[name=nama_sex]').val($(this).find(':selected').text());">
                  <option value="">Pilih Jenis Kelamin</option>
                  <?php foreach($sex as $data){?>
                    <option value="<?php echo $data['id']?>" <?php if($data['id']==$_SESSION['post']['sex']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
              </td>
            </tr>
          <?php endif; ?>

          <tr>
          	<th>Hari / Tanggal / Jam </th>
          	<td><input name="hari" readonly="readonly" type="text" class="inputbox required data_lahir" size="10" value="<?php echo $_SESSION['post']['hari']?>"/>
          /
            <input name="tanggallahir" type="text" class="inputbox required datepicker data_lahir" id="tanggallahir" size="11" value="<?php echo $_SESSION['post']['tanggallahir']?>"/>
          /
          <em>*Isi waktu kelahiran etc : 08:00</em>
          <input name="waktu_lahir" type="text" class="inputbox required data_lahir" size="10" value="<?php echo $_SESSION['post']['waktu_lahir']?>"/></td>
          </tr>

          <tr>
            <th>Tempat Dilahirkan </th>
            <td>
              <div class="uiradio">
                <?php foreach ($tempat_dilahirkan as $id => $nama): ?>
                  <input name="tempat_dilahirkan" class="data_lahir" type="radio" value="<?php echo $id?>" id="radio1<?php echo $id?>" <?php if($_SESSION['post']['tempat_dilahirkan']==$id){echo 'checked';}?>/><label for="radio1<?php echo $id?>"><?php echo $nama?></label>
                <?php endforeach; ?>
              </div>
            </td>
          </tr>
          <tr>
            <th>Tempat Kelahiran </th>
            <td><input name="tempatlahir" type="text" class="inputbox required data_lahir" id="tempatlahir" size="50" value="<?php echo $_SESSION['post']['tempatlahir']?>"/> <i>*(Nama Kota/Kabupaten)</i></td>
          </tr>
          <tr>
            <th>Jenis Kelahiran </th>
            <td valign="baseline">
              <div class="uiradio">
                <?php foreach ($jenis_kelahiran as $id => $nama): ?>
                  <input name="jenis_kelahiran" class="data_lahir" type="radio" value="<?php echo $id?>" id="radio2<?php echo $id?>" <?php if($_SESSION['post']['jenis_kelahiran']==$id){echo 'checked';}?>/><label for="radio2<?php echo $id?>"><?php echo $nama?></label>
                <?php endforeach; ?>
              </div>
            </td>
          </tr>
          <tr>
            <th>Kelahiran Anak Ke</th>
            <td>
              <input name="kelahiran_anak_ke" type="text" class="inputbox required data_lahir" id="kelahiran_anak_ke" size="8" value="<?php echo $_SESSION['post']['kelahiran_anak_ke']?>"/>
              &nbsp;<em>*isi dengan angka </em></td>
          </tr>
          <tr>
            <th>Penolong Kelahiran </th>
            <td>
              <div class="uiradio">
                <?php foreach ($penolong_kelahiran as $id => $nama): ?>
                  <input name="penolong_kelahiran" class="data_lahir" type="radio" value="<?php echo $id?>" id="radio3<?php echo $id?>" <?php if($_SESSION['post']['penolong_kelahiran']==$id){echo 'checked';}?>/><label for="radio3<?php echo $id?>"><?php echo $nama?></label>
                <?php endforeach; ?>
              </div>
            </td>
          </tr>
          <tr>
            <th>Berat Bayi</th>
            <td><input name="berat_lahir" type="text" class="inputbox required data_lahir" size="8" value="<?php echo $_SESSION['post']['berat_lahir']?>"/> Kg</td>
          </tr>
          <tr>
            <th>Panjang Bayi</th>
            <td><input name="panjang_lahir" type="text" class="inputbox required data_lahir" size="8" value="<?php echo $_SESSION['post']['panjang_lahir']?>"/> cm</td>
          </tr>
          <?php if($ibu and !$bayi): ?>
            <tr>
              <th>&nbsp;</th>
              <td>
                <input name="penduduk_baru" type="hidden" value="">
                <div class="uibutton-group">
                  <button type="button" onclick="buat_penduduk_baru();" class="uibutton confirm"><span class="fa fa-download">&nbsp;</span>Buat Penduduk Baru</button>
                </div>
              </td>
            </tr>
          <?php endif; ?>

          <!-- PELAPOR -->
          <tr><th><a name="pelapor"></a></th><td>&nbsp;</td></tr>
          <tr>
            <th class="grey">PELAPOR</th>
            <td class="grey">
              <div class="uiradio">
                <input type="radio" id="pelapor_1" name="pelapor" value="1" <?php if(!empty($pelapor)){echo 'checked';}?> onchange="ubah_pelapor(this.value);">
                <label for="pelapor_1">Warga Desa</label>
                <input type="radio" id="pelapor_2" name="pelapor" value="2" <?php if(empty($pelapor)){echo 'checked';}?> onchange="ubah_pelapor(this.value);">
                <label id="label_pelapor_2" for="pelapor_2">Warga Luar Desa</label>
              </div>
            </td>
          </tr>

          <tr class="pelapor_desa" <?php if (empty($pelapor)) echo 'style="display: none;"'; ?>>
            <th colspan="2">DATA PELAPOR WARGA DESA</th>
          </tr>
          <tr class="pelapor_desa" <?php if (empty($pelapor)) echo 'style="display: none;"'; ?>>
            <th class="indent">NIK / Nama</th>
            <td>
              <div id="id_pelapor" name="id_pelapor"></div>
              <?php if($pelapor){ //bagian info setelah terpilih
                  $individu = $pelapor;
                  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
              }?>
            </td>
          </tr>

          <?php if (empty($pelapor)) : ?>
            <tr class="pelapor_luar_desa">
              <th class="style6">DATA PELAPOR LUAR DESA</th>
            </tr>
            <tr class="pelapor_luar_desa">
              <th>Nama</th>
              <td><input name="nama_pelapor" type="text" class="inputbox required" size="100" value="<?php echo $_SESSION['post']['nama_pelapor']?>"/>  </td>
            </tr>
            <tr class="pelapor_luar_desa">
              <th>NIK</th>
              <td><input name="nik_pelapor" type="text" class="inputbox required" size="70" value="<?php echo $_SESSION['post']['nik_pelapor']?>"/></td>
            </tr>
            <tr class="pelapor_luar_desa">
              <th>Tempat Lahir </th>
              <td><input name="tempat_lahir_pelapor" type="text" class="inputbox required" id="tempat_lahir_pelapor" size="40" value="<?php echo $_SESSION['post']['tempat_lahir_pelapor']?>"/>
            <span class="judul"> Tanggal Lahir : </span>
              <input name="tanggal_lahir_pelapor" type="text" class="inputbox required datepicker" id="tanggal_lahir_pelapor" size="11" value="<?php echo $_SESSION['post']['tanggal_lahir_pelapor']?>"onchange="$('input[name=umur_pelapor]').val(_calculateAge($(this).val()));"/>
              <span class="judul"> Umur : </span>
              <input name="umur_pelapor" readonly="readonly" type="text" class="inputbox required" size="5" value="<?php echo $_SESSION['post']['umur_pelapor']?>"/>
                tahun</td>
            </tr>
            <tr class="pelapor_luar_desa">
              <th>Jenis kelamin </th>
              <td>
                <select name="jkpelapor" class="required" id="jkpelapor">
                  <option value="">Pilih Jenis Kelamin</option>
                  <?php foreach($sex as $data){?>
                    <option value="<?php echo $data['id']?>" <?php if($data['id']==$_SESSION['post']['jkpelapor']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
                <input type="hidden" name="pekerjaanid_pelapor">
                <span class="judul"> Pekerjaan </span>
                <select name="pekerjaanpelapor" class="required" id="pekerjaanpelapor" onchange="$('input[name=pekerjaanid_pelapor]').val($(this).find(':selected').data('pekerjaanid'));">
                  <option value="">Pilih Pekerjaan</option>
                  <?php  foreach($pekerjaan as $data){?>
                    <option value="<?php echo $data['nama']?>" data-pekerjaanid="<?php echo $data['id']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaanpelapor']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
              </td>
            </tr>
            <tr class="pelapor_luar_desa">
              <th>Alamat</th>
              <td>
                <p>Alamat <span class="judul"> : </span>
                  <input name="alamat_pelapor" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['alamat_pelapor']?>"/>
                  <span class="judul"> RT : </span>
                  <input name="rt_pelapor" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rt_pelapor']?>"/>
                  <span class="judul"> RW : </span>
                  <input name="rw_pelapor" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rw_pelapor']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Desa <span class="judul"> : </span>
                  <input name="desapelapor" type="text" class="inputbox required" id="desapelapor" size="40" value="<?php echo $_SESSION['post']['desapelapor']?>"/>
                  <span class="judul"> Kecamatan : </span>
                  <input name="kecpelapor" type="text" class="inputbox required" id="kecpelapor" size="40" value="<?php echo $_SESSION['post']['kecpelapor']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Kab<span class="judul"> &nbsp;:&nbsp; </span>
                  <input name="kabpelapor" type="text" class="inputbox required" id="kabpelapor" size="40" value="<?php echo $_SESSION['post']['kabpelapor']?>"/>
                  <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;:  </span>
                  <input name="provinsipelapor" type="text" class="inputbox required" id="provinsipelapor" size="40" value="<?php echo $_SESSION['post']['provinsipelapor']?>"/>
                </p>
              </td>
            </tr>
          <?php endif; ?>
          <tr>
            <th>Hubungan Pelapor dengan Bayi</th>
            <td><input name="hubungan_pelapor" type="text" class="inputbox required" id="hubungan_pelapor" size="60" value="<?php echo $_SESSION['post']['hubungan_pelapor']?>"/></td>
          </tr>

          <!-- SAKSI 1 -->
          <tr><th><a name="saksi1"></a></th><td>&nbsp;</td></tr>
          <tr>
            <th class="grey">SAKSI 1</th>
            <td class="grey">
              <div class="uiradio">
                <input type="radio" id="saksi1_1" name="saksi1" value="1" <?php if(!empty($saksi1)){echo 'checked';}?> onchange="ubah_saksi1(this.value);">
                <label for="saksi1_1">Warga Desa</label>
                <input type="radio" id="saksi1_2" name="saksi1" value="2" <?php if(empty($saksi1)){echo 'checked';}?> onchange="ubah_saksi1(this.value);">
                <label id="label_saksi1_2" for="saksi1_2">Warga Luar Desa</label>
              </div>
            </td>
          </tr>

          <tr class="saksi1_desa" <?php if (empty($saksi1)) echo 'style="display: none;"'; ?>>
            <th colspan="2">DATA SAKSI 1 WARGA DESA</th>
          </tr>
          <tr class="saksi1_desa" <?php if (empty($saksi1)) echo 'style="display: none;"'; ?>>
            <th class="indent">NIK / Nama</th>
            <td>
              <div id="id_saksi1" name="id_saksi1"></div>
              <?php if($saksi1){ //bagian info setelah terpilih
                  $individu = $saksi1;
                  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
              }?>
            </td>
          </tr>

          <?php if (empty($saksi1)) : ?>
            <tr class="saksi1_luar_desa">
              <th class="style6">DATA SAKSI 1 LUAR DESA</th>
            </tr>
            <tr class="saksi1_luar_desa">
              <th>Nama</th>
              <td><input name="nama_saksi1" type="text" class="inputbox required" id="nama_saksi1" size="100" value="<?php echo $_SESSION['post']['nama_saksi1']?>"/></td>
            </tr>
            <tr class="saksi1_luar_desa">
              <th>NIK</th>
              <td><input name="nik_saksi1" type="text" class="inputbox required" id="nik_saksi1" size="70" value="<?php echo $_SESSION['post']['nik_saksi1']?>"/></td>
            </tr>
            <tr class="saksi1_luar_desa">
              <th>Tempat Lahir  </th>
              <td>
                <input name="tempat_lahir_saksi1" type="text" class="inputbox required" id="tempat_lahir_saksi1" size="40" value="<?php echo $_SESSION['post']['tempat_lahir_saksi1']?>"/>
                <span class="judul"> Tanggal Lahir : </span>
                <input name="tanggal_lahir_saksi1" type="text" class="inputbox required datepicker" id="tanggal_lahir_saksi1" size="11" onchange="$('input[name=umur_saksi1]').val(_calculateAge($(this).val()));" value="<?php echo $_SESSION['post']['tanggal_lahir_saksi1']?>"/>
                <span class="judul"> Umur : </span>
                <input name="umur_saksi1" readonly="readonly" type="text" class="inputbox required" id="umur_saksi1" size="5" value="<?php echo $_SESSION['post']['umur_saksi1']?>"/>
                tahun
              </td>
            </tr>
            <tr class="saksi1_luar_desa">
              <th>Jenis Kelamin </th>
              <td>
                <select name="jksaksi1" class="required" id="jksaksi1">
                  <option value="">Pilih Jenis Kelamin</option>
                  <?php foreach($sex as $data){?>
                    <option value="<?php echo $data['id']?>" <?php if($data['id']==$_SESSION['post']['jksaksi1']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
                <span class="judul">Pekerjaan </span>
                <input type="hidden" name="pekerjaanid_saksi1">
                <select name="pekerjaansaksi1" class="required" id="pekerjaansaksi1" onchange="$('input[name=pekerjaanid_saksi1]').val($(this).find(':selected').data('pekerjaanid'));">
                  <option value="">Pilih Pekerjaan</option>
                  <?php  foreach($pekerjaan as $data){?>
                    <option value="<?php echo $data['nama']?>" data-pekerjaanid="<?php echo $data['id']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaansaksi1']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
              </td>
            </tr>
            <tr class="saksi1_luar_desa">
              <th>Alamat</th>
              <td>
                <p>Alamat <span class="judul"> : </span>
                  <input name="alamat_saksi1" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['alamat_saksi1']?>"/>
                  <span class="judul"> RT : </span>
                  <input name="rt_saksi1" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rt_saksi1']?>"/>
                  <span class="judul"> RW : </span>
                  <input name="rw_saksi1" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rw_saksi1']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Desa <span class="judul"> : </span>
                  <input name="desasaksi1" type="text" class="inputbox required" id="desasaksi1" size="40" value="<?php echo $_SESSION['post']['desasaksi1']?>"/>
                  <span class="judul"> Kecamatan : </span>
                  <input name="kecsaksi1" type="text" class="inputbox required" id="kecsaksi1" size="40" value="<?php echo $_SESSION['post']['kecsaksi1']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Kab<span class="judul"> &nbsp;:&nbsp; </span>
                  <input name="kabsaksi1" type="text" class="inputbox required" id="kabsaksi1" size="40" value="<?php echo $_SESSION['post']['kabsaksi1']?>"/>
                  <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;: </span>
                  <input name="provinsisaksi1" type="text" class="inputbox required" id="provinsisaksi1" size="40" value="<?php echo $_SESSION['post']['provinsisaksi1']?>"/>
                </p>
              </td>
            </tr>
            <tr class="saksi1_luar_desa">
              <th>&nbsp;</th>
            </tr>
          <?php endif; ?>

          <!-- SAKSI 2 -->
          <tr><th><a name="saksi2"></a></th><td>&nbsp;</td></tr>
          <tr>
            <th class="grey">SAKSI 2</th>
            <td class="grey">
              <div class="uiradio">
                <input type="radio" id="saksi2_1" name="saksi2" value="1" <?php if(!empty($saksi2)){echo 'checked';}?> onchange="ubah_saksi2(this.value);">
                <label for="saksi2_1">Warga Desa</label>
                <input type="radio" id="saksi2_2" name="saksi2" value="2" <?php if(empty($saksi2)){echo 'checked';}?> onchange="ubah_saksi2(this.value);">
                <label id="label_saksi2_2" for="saksi2_2">Warga Luar Desa</label>
              </div>
            </td>
          </tr>

          <tr class="saksi2_desa" <?php if (empty($saksi2)) echo 'style="display: none;"'; ?>>
            <th colspan="2">DATA SAKSI 2 WARGA DESA</th>
          </tr>
          <tr class="saksi2_desa" <?php if (empty($saksi2)) echo 'style="display: none;"'; ?>>
            <th class="indent">NIK / Nama</th>
            <td>
              <div id="id_saksi2" name="id_saksi2"></div>
              <?php if($saksi2){ //bagian info setelah terpilih
                  $individu = $saksi2;
                  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
              }?>
            </td>
          </tr>

          <?php if (empty($saksi2)) : ?>
            <tr class="saksi2_luar_desa">
              <th class="style6">DATA SAKSI 2 LUAR DESA</th>
            </tr>
            <tr class="saksi2_luar_desa">
              <th>Nama</th>
              <td><input name="nama_saksi2" type="text" class="inputbox required" id="nama_saksi2" size="100"  value="<?php echo $_SESSION['post']['nama_saksi2']?>"/></td>
            </tr>
            <tr class="saksi2_luar_desa">
              <th>NIK</th>
              <td><input name="nik_saksi2" type="text" class="inputbox required" id="nik_saksi2" size="70" value="<?php echo $_SESSION['post']['nik_saksi2']?>"/></td>
            </tr>
            <tr class="saksi2_luar_desa">
              <th>Tempat Lahir </th>
              <td>
                <input name="tempat_lahir_saksi2" type="text" class="inputbox required" id="tempat_lahir_saksi2" size="40" value="<?php echo $_SESSION['post']['tempat_lahir_saksi2']?>"/>
                <span class="judul"> Tanggal Lahir : </span>
                <input name="tanggal_lahir_saksi2" type="text" class="inputbox required datepicker" id="tanggal_lahir_saksi2" size="11" onchange="$('input[name=umur_saksi2]').val(_calculateAge($(this).val()));" value="<?php echo $_SESSION['post']['tanggal_lahir_saksi2']?>"/>
                <span class="judul"> Umur : </span>
                <input name="umur_saksi2" readonly="readonly" type="text" class="inputbox required" id="umur_saksi2" size="5" value="<?php echo $_SESSION['post']['umur_saksi2']?>"/>
                tahun
              </td>
            </tr>
            <tr class="saksi2_luar_desa">
              <th>Jenis Kelamin </th>
              <td>
                <select name="jksaksi2" class="required" id="jksaksi2">
                  <option value="">Pilih Jenis Kelamin</option>
                  <?php foreach($sex as $data){?>
                    <option value="<?php echo $data['id']?>" <?php if($data['id']==$_SESSION['post']['jksaksi2']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
                <span class="judul">Pekerjaan </span>
                <input type="hidden" name="pekerjaanid_saksi2">
                <select name="pekerjaansaksi2" class="required" id="pekerjaansaksi2" onchange="$('input[name=pekerjaanid_saksi2]').val($(this).find(':selected').data('pekerjaanid'));">
                  <option value="">Pilih Pekerjaan</option>
                  <?php  foreach($pekerjaan as $data){?>
                  <option value="<?php echo $data['nama']?>" data-pekerjaanid="<?php echo $data['id']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaansaksi2']) echo 'selected'?>><?php echo $data['nama']?></option>
                  <?php }?>
                </select>
              </td>
            </tr>
            <tr class="saksi2_luar_desa">
              <th>Alamat</th>
              <td>
                <p>Alamat <span class="judul"> : </span>
                  <input name="alamat_saksi2" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['alamat_saksi2']?>"/>
                  <span class="judul"> RT : </span>
                  <input name="rt_saksi2" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rt_saksi2']?>"/>
                  <span class="judul"> RW : </span>
                  <input name="rw_saksi2" type="text" class="inputbox required" size="7" value="<?php echo $_SESSION['post']['rw_saksi2']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>Desa <span class="judul"> : </span>
                  <input name="desasaksi2" type="text" class="inputbox required" id="desasaksi2" size="40" value="<?php echo $_SESSION['post']['desasaksi2']?>"/>
                  <span class="judul"> Kecamatan : </span>
                  <input name="kecsaksi2" type="text" class="inputbox required" id="kecsaksi2" size="40" value="<?php echo $_SESSION['post']['kecsaksi2']?>"/>
                </p>
                <p>&nbsp;</p>
                <p>
                  Kab<span class="judul"> &nbsp;:&nbsp; </span>
                  <input name="kabsaksi2" type="text" class="inputbox required" id="kabsaksi2" size="40" value="<?php echo $_SESSION['post']['kabsaksi2']?>"/>
                  <span class="judul"> Provinsi &nbsp;&nbsp;&nbsp;&nbsp;: </span>
                  <input name="provinsisaksi2" type="text" class="inputbox required" id="provinsisaksi2" size="40" value="<?php echo $_SESSION['post']['provinsisaksi2']?>"/>
                </p>
              </td>
            </tr>
            <tr class="saksi2_luar_desa">
              <th>&nbsp;</th>
            </tr>
          <?php endif; ?>

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
            <td><input name="lokasi_disdukcapil" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['lokasi_disdukcapil']?>"/></td>
          </tr>
        	<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
      </table>
    </form>
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
  </div>
</div>
</td></tr></table>
</div>
