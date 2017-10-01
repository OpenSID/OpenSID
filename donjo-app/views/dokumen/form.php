<script type="text/javascript">
  function tampilForm(){
    if ($('select[name=kategori]').val() == 2){
      $('.sk_kades').show();
      $('input.sk_kades').removeAttr('disabled');
    }
    else {
      $('.sk_kades').hide();
      $('input.sk_kades').attr('disabled','disabled');
    }
    if ($('select[name=kategori]').val() == 3){
      $('.perdes').show();
      $('input.perdes').removeAttr('disabled');
    }
    else {
      $('.perdes').hide();
      $('input.perdes').attr('disabled','disabled');
    }
  }
  $('document').ready(function(){
    tampilForm();
  });
</script>
<style type="text/css">
  #form_dokumen th {
    width: 1%;
    white-space: nowrap;
  }
</style>
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;">

<div class="content-header">
    <h3>Form Manajemen Dokumen</h3>
</div>
<div id="contentpane">
    <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table id="form_dokumen" class="form">
            <tr>
              <th>Judul / Tentang</th>
              <td><input name="nama" type="text" class="inputbox" size="100" value="<?php echo $dokumen['nama']?>"/></td>
            </tr>
            <tr>
              <th>Kategori</th>
              <td>
                <select name="kategori" class="required" onchange="tampilForm();">
                  <option value="">Pilih Kategori</option>
                  <?php foreach($list_kategori as $id => $nama){
                    if (!empty($dokumen)) $kat = $dokumen['kategori'];
                    ?>
                    <option value="<?php echo $id?>" <?php if($kat==$id){?>selected<?php }?>><?php echo $nama?></option>
                  <?php }?>
                </select>
              </td>
            </tr>

      			<?php if($dokumen['satuan']){?>
              <tr>
                <th class="top">Dokumen</th>
                <td>
          				<div class="slidebox-avatar">
          					<img src="<?php echo base_url().LOKASI_DOKUMEN.$dokumen['satuan']?>" width="300px" alt="<?php echo $dokumen['satuan']?>"/>
          				</div>
        				</td>
        				<input type="hidden" name="old_file" value="<?php echo $dokumen['satuan']?>">
              </tr>
      			<?php }?>
            <tr>
                <th>Upload Dokumen</th>
                <td><input type="file" name="satuan" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah dokumen)</span></td>
            </tr>
            <?php include ("donjo-app/views/dokumen/_sk_kades.php"); ?>
            <?php include ("donjo-app/views/dokumen/_perdes.php"); ?>
        </table>
    </div>

    <div class="ui-layout-south panel bottom">
        <div class="left">
            <a href="<?php echo site_url().$this->controller.'/index/'.$kat?>" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
                <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
