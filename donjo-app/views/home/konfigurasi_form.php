<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div class="content-header">
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo config_item('google_key'); ?>"></script>

</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Identitas <?php echo ucwords(config_item('sebutan_desa'))?></h3>
</div>
    <form action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
            <tr>
                <th width="110">Nama <?php echo ucwords(config_item('sebutan_desa'))?></th>
                <td><input name="nama_desa" type="text" class="inputbox" size="25" value="<?php echo unpenetration($main['nama_desa'])?>"/> Kode <input name="kode_desa" type="text" class="inputbox" size="6" value="<?php echo $main['kode_desa']?>"/> Kode POS <input name="kode_pos" type="text" class="inputbox" size="8" value="<?php echo $main['kode_pos']?>"/></td>
            </tr>
            <tr>
                <th>Nama Kepala <?php echo ucwords(config_item('sebutan_desa'))?></th>
                <td><input name="nama_kepala_desa" type="text" class="inputbox" size="40" value="<?php echo unpenetration($main['nama_kepala_desa'])?>"/></td>
            </tr>
            <tr>
                <th>Alamat Kantor <?php echo ucwords(config_item('sebutan_desa'))?></th>
                <td><input name="alamat_kantor" type="text" class="inputbox" size="40" value="<?php echo unpenetration($main['alamat_kantor'])?>"/></td>
            </tr>
            <tr>
                <th>Nama Kecamatan</th>
                <td><input name="nama_kecamatan" type="text" class="inputbox" size="25" value="<?php echo unpenetration($main['nama_kecamatan'])?>"/> Kode <input name="kode_kecamatan" type="text" class="inputbox" size="10" value="<?php echo $main['kode_kecamatan']?>"/></td></td>
            </tr>
            <tr>
                <th>Nama Camat</th>
                <td><input name="nama_kepala_camat" type="text" class="inputbox" size="40" value="<?php echo unpenetration($main['nama_kepala_camat'])?>"/></td>
            </tr>
            <tr>
                <th>NIP Camat</th>
                <td><input name="nip_kepala_camat" type="text" class="inputbox" size="25" value="<?php echo $main['nip_kepala_camat']?>"/></td>
            </tr>
            <tr>
                <th>Nama Kabupaten</th>
                <td><input name="nama_kabupaten" type="text" class="inputbox" size="25" value="<?php echo unpenetration($main['nama_kabupaten'])?>"/> Kode <input name="kode_kabupaten" type="text" class="inputbox" size="10" value="<?php echo $main['kode_kabupaten']?>"/></td></td>
            </tr>
            <tr>
                <th>Nama Provinsi</th>
                <td><input name="nama_propinsi" type="text" class="inputbox" size="25" value="<?php echo unpenetration($main['nama_propinsi'])?>"/> Kode <input name="kode_propinsi" type="text" class="inputbox" size="10" value="<?php echo $main['kode_propinsi']?>"/></td></td>
            </tr>
            <tr>
                <th class="top">Logo</th>
                <td>
				<div class="userbox-avatar">
				<?php if($main['logo']){?>
					<img src="<?php echo LogoDesa($main['logo'])?>" alt=""/>
				<?php }else{?>
					<img src="<?php echo base_url()?>assets/files/logo/home.png" alt=""/>
				<?php }?>
				</div>
				</td>
				<input type="hidden" name="old_logo" value="<?php echo $main['logo']?>">
            </tr>
            <tr>
                <th>Ganti Logo</th>
                <td><input type="file" name="logo" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah logo)</span></td>
            </tr>
             <tr>
                <th>Kantor / Wilayah <?php echo ucwords(config_item('sebutan_desa'))?></th>
                <td><a href="<?php echo site_url("hom_desa/ajax_kantor_maps")?>" class="uibutton confirm" target="ajax-modalz" rel="window-lok" header="Lokasi <?php echo $main['nama_desa']?>" title="Lokasi <?php echo $main['nama_desa']?>">Kantor <?php echo ucwords(config_item('sebutan_desa'))?></a><a href="<?php echo site_url("hom_desa/ajax_wilayah_maps")?>" class="uibutton confirm" target="ajax-modalz" rel="window-wil" header="Wilayah <?php echo $main['nama_desa']?>" title="Wilayah <?php echo $main['nama_desa']?>">Wilayah <?php echo ucwords(config_item('sebutan_desa'))?></a></td>
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
