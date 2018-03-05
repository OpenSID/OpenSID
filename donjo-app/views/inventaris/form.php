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
    <h3>Form <?php echo ($inventaris) ? 'Perubahan' : 'Penambahan'?> Inventaris</h3>
  </div>
  <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
      <table class="form">
        <tr>
          <th class="nostretch">Jenis Barang</th>
          <td><?php echo $jenis['nama']?></td>
        </tr>
        <tr>
          <th class="nostretch">Tanggal Pengadaan</th>
          <td>
            <input name="tanggal_pengadaan" type="text" class="inputbox datepicker required" size="20"  value="<?php echo $inventaris['tanggal_pengadaan']?>"/>
          </td>
        </tr>
        <tr>
          <th>Nama Barang</th>
          <td><input name="nama_barang" type="text" class="inputbox" size="50" value="<?php echo $inventaris['nama_barang']?>"/></td>
        </tr>
        <tr>
          <th>Jumlah Barang</th>
          <td><input name="jml_barang" type="text" class="inputbox" size="8" value="<?php echo $inventaris['jml_barang']?>"/></td>
        </tr>
        <tr>
          <th>Asal Barang</th>
          <td>
            <select name="asal_barang" class="required">
              <option value="">Pilih Asal</option>
              <?php foreach($asal_inventaris as $id => $nama){?>
                <option value="<?php echo $id?>"<?php if($inventaris['asal_barang']==$id){?> selected<?php }?>><?php echo strtoupper($nama)?></option>
              <?php }?>
            </select>
          </td>
        </tr>
        <tr>
          <th>Keterangan</th>
          <td><input name="keterangan" type="text" class="inputbox" size="100" value="<?php echo $inventaris['keterangan']?>"/></td>
        </tr>
      </table>
    </div>

    <div class="ui-layout-south panel bottom">
      <div class="left">
        <a href="<?php echo site_url().$this->controller.'/rincian/'.$id_jenis?>" class="uibutton icon prev">Kembali</a>
      </div>
      <div class="right">
        <div class="uibutton-group">
          <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
          <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
        </div>
      </div>
    </div>
  </form>
</div>
</td></tr></table>
</div>
