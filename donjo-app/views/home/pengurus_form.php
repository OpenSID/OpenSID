<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td style="background:#fff;padding:0px;">
<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></h3>
</div>
    <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
            <tr>
              <th class="top">Foto</th>
              <td>
                <div class="userbox-avatar">
                  <img src="<?php echo AmbilFoto($pamong['foto'])?>" alt=""/>
                </div>
              </td>
              <input type="hidden" name="old_foto" value="<?php echo $pamong['foto']?>">
            </tr>
            <tr>
              <th>Ganti Foto</th>
              <td><input type="file" name="foto" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah foto)</span></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><input name="pamong_nama" type="text" class="inputbox required" size="40" value="<?php echo unpenetration($pamong['pamong_nama'])?>"/></td>
            </tr>
            <tr>
                <th>NIP</th>
                <td><input name="pamong_nip" type="text" class="inputbox" size="20" value="<?php echo $pamong['pamong_nip']?>"/></td>
            </tr>
            <tr>
                <th>NIK</th>
                <td><input name="pamong_nik" type="text" class="inputbox" size="40" value="<?php echo $pamong['pamong_nik']?>"/></td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td><input name="jabatan" type="text" class="inputbox" size="20"  value="<?php echo unpenetration($pamong['jabatan'])?>"/></td>
            </tr>
            <tr>
				<th width="100">Status</th>
                <td>
                    <div class="uiradio">
                		<input type="radio" id="group1" name="pamong_status" value="1"/<?php if($pamong['pamong_status'] == '1' OR $pamong['pamong_status'] == ''){echo 'checked';}?>>
						<label for="group1">Aktif</label>
                		<input type="radio" id="group2" name="pamong_status" value="2"/<?php if($pamong['pamong_status'] == '2' ){echo 'checked';}?>>
						<label for="group2">Tidak Aktif</label>
                	</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="ui-layout-south panel bottom">
        <div class="left">
            <a href="<?php echo site_url()?>pengurus" class="uibutton icon prev">Kembali</a>
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
