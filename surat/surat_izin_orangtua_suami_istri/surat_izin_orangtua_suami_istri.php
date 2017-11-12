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
        $('#nik_validasi').val($('#nik_hidden').val());
        submit_form_ambil_data();
      }
    });
  });

  $(function(){
    var diberi_izin = {};
    diberi_izin.results = [
      <?php foreach($penduduk as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_diberi_izin').flexbox(diberi_izin, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: <?php if($diberi_izin){?>'<?php echo $diberi_izin['nik']?> - <?php echo spaceunpenetration($diberi_izin['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('diberi_izin');
        $('#id_diberi_izin_validasi').val($('#id_diberi_izin_hidden').val());
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

  function nomor_surat(nomor){
    $('#nomor').val(nomor);
    // $('#nomor_main').val(nomor);
  }

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
          <th>Nomor Surat</th>
          <td>
            <input name="nomor" type="text" class="inputbox required" size="12" value="<?php echo $_SESSION['post']['nomor']; ?>" onchange="nomor_surat(this.value);"/>
            <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
          </td>
        </tr>

        <form id="validasi" name="validasi" action="<?php echo $form_action?>" method="POST">
          <input id="nomor" name="nomor" type="hidden" value="<?php echo $_SESSION['post']['nomor']; ?>"/>
          <input id="nik_validasi" name="nik" type="hidden" value="<?php echo $_SESSION['post']['nik']?>">
          <input id="id_diberi_izin_validasi" name="id_diberi_izin" type="hidden" value="<?php echo $_SESSION['id_diberi_izin']?>"/>

        <tr>
          <th colspan="2">PIHAK YANG MEMBERI IZIN</th>
        </tr>
        <tr>
          <th>NIK / Nama Yang Memberi Izin</th>
          <td>
            <div id="nik" name="nik"></div>
          </td>
        </tr>
        <?php if($individu){ //bagian info setelah terpilih?>
          <?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
        <?php }?>
        <tr>
          <th>Memberi Izin Selaku</th>
          <td>
            <select name="selaku" id="selaku">
              <option value="" selected="selected">Pilih selaku</option>
                <?php  foreach($selaku as $data){?>
                  <option value="<?php echo $data?>" <?php if($data==$_SESSION['post']['selaku']) echo 'selected'?>><?php echo $data?></option>
                <?php }?>
            </select>
          </td>
        </tr>
        <tr>
          <th colspan="2">PIHAK YANG DIBERI IZIN</th>
        </tr>
        <tr>
          <th>Hubungan Dengan Pemberi Izin</th>
          <td>
            <select name="mengizinkan" id="mengizinkan">
              <option value="">Pilih hubungan</option>
                <?php  foreach($yang_diberi_izin as $data){?>
                  <option value="<?php echo $data?>" <?php if($data==$_SESSION['post']['mengizinkan']) echo 'selected'?>><?php echo $data?></option>
                <?php }?>
            </select>
          </td>
        </tr>
        <tr>
          <th width="120">NIK / Nama Yang Diberi Izin</th>
          <td width="665">
            <div id="id_diberi_izin" name="id_diberi_izin"></div>
          </td>
        </tr>
        <?php if($diberi_izin): ?>
          <?php  //bagian info setelah terpilih
            $individu = $diberi_izin;
            include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
          ?>
        <?php endif; ?>

        <tr>
          <th>Negara Tujuan</th>
          <td>
            <input name="negara_tujuan" type="text" class="inputbox required" size="30" value="<?php echo $_SESSION['post']['negara_tujuan']?>"/> Diisi dengan Negara yang dituju sprt: Malaysia, Korea, dll
          </td>
        </tr>

        <tr>
          <th>Nama PPTKIS</th>
          <td>
            <input name="nama_pptkis" type="text" class="inputbox required" size="30" value="<?php echo $_SESSION['post']['nama_pptkis']?>"/> *) Nama PT atau Perusahaan
          </td>
        </tr>

        <tr>
          <th>Status Pekerjaan/ TKI/ TKW</th>
          <td>
            <select name="pekerja" id="pekerja">
              <option value="">Pilih Status Pekerjaan/ TKI/ TKW</option>
              <?php  foreach($status_pekerjaan as $data){?>
                <option value="<?php echo $data?>" <?php if($data==$_SESSION['post']['pekerja']) echo 'selected'?>><?php echo $data?></option>
              <?php }?>
            </select>
          </td>
        </tr>
        <tr>
          <th>&nbsp;</th>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <th colspan="2" class="style6">PENANDA TANGAN</th>
        </tr>
        <tr>
          <th>Atas Nama</th>
          <td>
            <select name="atas_nama"  type="text" class="inputbox">
              <option value="">Atas Nama</option>
              <?php  foreach($atas_nama as $data){?>
                <option value="<?php echo $data?>" <?php if($data==$_SESSION['post']['atas_nama']) echo 'selected'?>><?php echo $data?></option>
              <?php }?>
            </select>
          </td>
        </tr>
        <?php include("donjo-app/views/surat/form/_pamong.php"); ?>
        </form>

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
    </div>
  </div>

</td>
</tr>
</table>
</div>