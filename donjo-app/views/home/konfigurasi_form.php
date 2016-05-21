<div id="pageC"> 
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Konfigurasi</h3>
</div>
    <form action="<?=site_url("hom_desa/update/$main[id]")?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
            <tr>
                <th width="110">Nama Desa</th>
                <td><input name="nama_desa" type="text" class="inputbox" size="25" value="<?=unpenetration($main['nama_desa'])?>"/> Kode <input name="kode_desa" type="text" class="inputbox" size="6" value="<?=$main['kode_desa']?>"/> Kode POS <input name="kode_pos" type="text" class="inputbox" size="8" value="<?=$main['kode_pos']?>"/></td>
            </tr>
            <tr>
                <th>Nama Kepala Desa</th>
                <td><input name="nama_kepala_desa" type="text" class="inputbox" size="40" value="<?=unpenetration($main['nama_kepala_desa'])?>"/></td>
            </tr>
            <tr>
                <th>Alamat Kantor Desa</th>
                <td><input name="alamat_kantor" type="text" class="inputbox" size="40" value="<?=unpenetration($main['alamat_kantor'])?>"/></td>
            </tr>
            <tr>
                <th>Nama Kecamatan</th>
                <td><input name="nama_kecamatan" type="text" class="inputbox" size="25" value="<?=unpenetration($main['nama_kecamatan'])?>"/> Kode <input name="kode_kecamatan" type="text" class="inputbox" size="10" value="<?=$main['kode_kecamatan']?>"/></td></td>
            </tr>
            <tr>
                <th>Nama Camat</th>
                <td><input name="nama_kepala_camat" type="text" class="inputbox" size="40" value="<?=unpenetration($main['nama_kepala_camat'])?>"/></td>
            </tr>
            <tr>
                <th>NIP Camat</th>
                <td><input name="nip_kepala_camat" type="text" class="inputbox" size="25" value="<?=$main['nip_kepala_camat']?>"/></td>
            </tr>
            <tr>
                <th>Nama Kabupaten</th>
                <td><input name="nama_kabupaten" type="text" class="inputbox" size="25" value="<?=unpenetration($main['nama_kabupaten'])?>"/> Kode <input name="kode_kabupaten" type="text" class="inputbox" size="10" value="<?=$main['kode_kabupaten']?>"/></td></td>
            </tr>
            <tr>
                <th>Nama Provinsi</th>
                <td><input name="nama_propinsi" type="text" class="inputbox" size="25" value="<?=unpenetration($main['nama_propinsi'])?>"/> Kode <input name="kode_propinsi" type="text" class="inputbox" size="10" value="<?=$main['kode_propinsi']?>"/></td></td>
            </tr>
            <tr>
                <th class="top">Logo</th>
                <td>
				<div class="userbox-avatar">
				<?if($main['logo']){?>
					<img src="<?=base_url()?>assets/images/logo/<?=$main['logo']?>" alt=""/>
				<?}else{?>
					<img src="<?=base_url()?>assets/images/logo/home.png" alt=""/>
				<?}?>
				</div>
				</td>
				<input type="hidden" name="old_logo" value="<?=$main['logo']?>">
            </tr>
            <tr>
                <th>Ganti Logo</th>
                <td><input type="file" name="logo" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah logo)</span></td>
            </tr>
        </table>
    </div>
    
    <div class="ui-layout-south panel bottom">
        <div class="left">     
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit">Simpan</button>
            </div>
        </div>
    </div>
    </form>
</div>
</td></tr></table>
</div>
