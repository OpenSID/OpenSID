<script type="text/javascript">
  function ubah_jenis_mutasi(jenis){
    if(jenis!=1 && jenis!=4){
      // Hapus barang baik atau rusak
      $('.hapus').attr('disabled','disabled');
      $('.hapus > input').removeClass('required');
      $('.hapus > select').removeClass('required');
      $('.hapus').hide();
    } else {
      $('.hapus').removeAttr('disabled');
      $('.hapus > input').addClass('required');
      $('.hapus > select').addClass('required');
      $('.hapus').show();
    }
  }

  $('document').ready(function(){
    ubah_jenis_mutasi($('select[name=jenis_mutasi').val());
    $( "#mutasi" ).validate({
      rules: {
        jml_mutasi: {
          required: true,
          range: [1,<?php echo ($inventaris['jml_sekarang']+$mutasi['jml_mutasi'])?>]
        }
      }
    });
  });
</script>
<style type="text/css">
  table.form th.indent {
    padding-left: 4em;
    white-space: nowrap;
  }
</style>

<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
    <td class="side-menu">
    <?php
    $this->load->view('inventaris/menu_kiri.php')
    ?>
    </td>

		<td style="background:#fff;padding:0px;">

<div class="content-header">
</div>
<div id="contentpane">
  <div class="ui-layout-north panel">
    <h3>Form Mutasi Inventaris</h3>
  </div>
  <form id="mutasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
      <table class="form">
        <tr>
          <th class="nostretch">Nama Barang</th>
          <td><?php echo $inventaris['nama_barang']?></td>
        </tr>
        <tr>
          <th class="nostretch">Tanggal Pengadaan / Asal Barang</th>
          <td><?php echo $inventaris['tanggal_pengadaan'].' / '.$asal_inventaris[$inventaris['asal_barang']]?></td>
        </tr>
        <tr>
          <th class="nostretch">Jumlah Pengadaan</th>
          <td><?php echo $inventaris['jml_barang']?></td>
        <tr>
          <th class="nostretch">Jumlah Sekarang</th>
          <td>
            <?php echo $inventaris['jml_sekarang']?>
            (Status baik: <?php echo $inventaris['status_baik']?>; Status rusak: <?php echo $inventaris['status_rusak']?>)
          </td>
        </tr>
        <tr>
          <th class="nostretch">Tanggal Mutasi</th>
          <td>
            <input name="tanggal_mutasi" type="text" class="inputbox datepicker required" size="20"  value="<?php echo $mutasi['tanggal_mutasi']?>"/>
          </td>
        </tr>
        <tr>
          <th>Jenis Mutasi</th>
          <td>
            <select name="jenis_mutasi" class="required" onchange="ubah_jenis_mutasi($(this).val())">
              <option value="">Pilih Jenis Mutasi</option>
              <?php foreach($jenis_mutasi as $id => $nama){?>
                <option value="<?php echo $id?>"<?php if($mutasi['jenis_mutasi']==$id){?> selected<?php }?>><?php echo strtoupper($nama)?></option>
              <?php }?>
            </select>
          </td>
        </tr>
        <tr>
          <th class="nostretch hapus">Jenis Penghapusan</th>
          <td class="hapus">
            <select name="jenis_penghapusan" class="required">
              <option value="">Pilih Jenis Penghapusan</option>
              <?php foreach($jenis_penghapusan as $id => $nama){?>
                <option value="<?php echo $id?>"<?php if($mutasi['jenis_penghapusan']==$id){?> selected<?php }?>><?php echo strtoupper($nama)?></option>
              <?php }?>
            </select>
          </td>
        </tr>
        <tr>
          <th>Jumlah Mutasi</th>
          <td><input id="jml_mutasi" name="jml_mutasi" type="text" class="inputbox required" size="10" value="<?php echo $mutasi['jml_mutasi'] ? $mutasi['jml_mutasi'] : 0?>"/></td>
        </tr>
        <tr>
          <th>Keterangan</th>
          <td><input name="keterangan" type="text" class="inputbox" size="100" value="<?php echo $mutasi['keterangan']?>"/></td>
        </tr>
        <tr>
          <th colspan="2">&nbsp;</th>
        </tr>
      </table>

      <table class="list">
        <thead>
          <tr>
            <th rowspan="2" class="nostretch">No</th>
            <th rowspan="2" class="nostretch">Aksi</th>
            <th rowspan="2">Tanggal Mutasi</th>
            <th rowspan="2">Jenis Mutasi</th>
            <th rowspan="2">Jenis Penghapusn</th>
            <th rowspan="2">Jumlah</th>
            <th rowspan="2">Keterangan</th>
        </thead>
        <tbody>
          <?php $i = 0;
            foreach($main as $data){
              $i++; ?>
            <tr>
              <td align="center" width="2"><?php echo $i+$paging->offset?></td>
              <td>
                <div class="uibutton-group" style="display: flex;">
                  <a href="<?php echo site_url("{$this->controller}/mutasi/$jenis[id]/$p/$o/$inventaris[id]/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Ubah Data"><span class="fa fa-edit fa-tipis"> Ubah</span></a>
                  <a href="<?php echo site_url("{$this->controller}/delete_mutasi/$jenis[id]/$p/$o/$inventaris[id]/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"><span></a>
                </div>
              </td>
              <td><?php echo tgl_indo_out($data['tanggal_mutasi'])?></td>
              <td><?php echo $jenis_mutasi[$data['jenis_mutasi']]?></td>
              <td><?php echo $jenis_penghapusan[$data['jenis_penghapusan']]?></td>
              <td><?php echo $data['jml_mutasi']?></td>
              <td><?php echo $data['keterangan']?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>

    </div>

    <div class="ui-layout-south panel bottom">
      <div class="left">
        <a href="<?php echo site_url().$this->controller.'/rincian/'.$id_jenis?>" class="uibutton icon prev">Kembali</a>
      </div>
      <div class="right">
        <div class="uibutton-group">
          <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
          <button class="uibutton confirm" type="submit" value="Validate!"><span class="fa fa-save"></span> Simpan</button>
        </div>
      </div>
    </div>
  </form>
</div>
</td></tr></table>
</div>
