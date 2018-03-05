<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;">

<div class="content-header">
</div>
<div id="contentpane">
  <div class="ui-layout-north panel">
    <h3><?php echo ($jenis) ? 'Ubah' : 'Tambah'?> Jenis Barang</h3>
  </div>
  <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
      <table class="form">
        <tr>
          <th>Jenis Barang</th>
          <td><input name="nama" type="text" class="inputbox" size="30" value="<?php echo $jenis['nama']?>"/></td>
        </tr>
        <tr>
          <th>Keterangan</th>
          <td><input name="keterangan" type="text" class="inputbox" size="100" value="<?php echo $jenis['keterangan']?>"/></td>
        </tr>
      </table>
    </div>

    <div class="ui-layout-south panel bottom">
      <div class="left">
        <a href="<?php echo site_url().$this->controller.'/index/'?>" class="uibutton icon prev">Kembali</a>
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
