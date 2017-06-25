<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div class="content-header">
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->setting->google_key; ?>"></script>

</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Identitas <?php echo ucwords($this->setting->sebutan_desa)?></h3>
</div>
    <form action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
            <tr>
                <th style="width: 140px;">Nama <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><input name="nama_desa" type="text" class="inputbox" size="25" value="<?php echo $main['nama_desa']?>"/> Kode <input name="kode_desa" type="text" class="inputbox" size="6" value="<?php echo $main['kode_desa']?>"/> Kode POS <input name="kode_pos" type="text" class="inputbox" size="8" value="<?php echo $main['kode_pos']?>"/></td>
            </tr>
            <tr>
                <th>Nama Kepala <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><input name="nama_kepala_desa" type="text" class="inputbox" size="40" value="<?php echo $main['nama_kepala_desa']?>"/></td>
            </tr>
            <tr>
                <th>NIP Kepala <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><input name="nip_kepala_desa" type="text" class="inputbox" size="40" value="<?php echo $main['nip_kepala_desa']?>"/></td>
            </tr>
            <tr>
                <th>Alamat Kantor <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><input name="alamat_kantor" type="text" class="inputbox" size="40" value="<?php echo $main['alamat_kantor']?>"/></td>
            </tr>
            <tr>
                <th>Email <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><input name="email_desa" type="text" class="inputbox" size="40" value="<?php echo $main['email_desa']?>"/></td>
            </tr>
            <tr>
                <th>Telepon <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><input name="telepon" type="text" class="inputbox" size="40" value="<?php echo $main['telepon']?>"/></td>
            </tr>
            <tr>
                <th>Website <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><input name="website" type="text" class="inputbox" size="40" value="<?php echo $main['website']?>"/></td>
            </tr>
            <tr>
                <th>Nama <?php echo ucwords($this->setting->sebutan_kecamatan)?></th>
                <td><input name="nama_kecamatan" type="text" class="inputbox" size="25" value="<?php echo $main['nama_kecamatan']?>"/> Kode <input name="kode_kecamatan" type="text" class="inputbox" size="10" value="<?php echo $main['kode_kecamatan']?>"/></td></td>
            </tr>
            <tr>
                <th>Nama <?php echo ucwords($this->setting->sebutan_camat)?></th>
                <td><input name="nama_kepala_camat" type="text" class="inputbox" size="40" value="<?php echo $main['nama_kepala_camat']?>"/></td>
            </tr>
            <tr>
                <th>NIP <?php echo ucwords($this->setting->sebutan_camat)?></th>
                <td><input name="nip_kepala_camat" type="text" class="inputbox" size="25" value="<?php echo $main['nip_kepala_camat']?>"/></td>
            </tr>
            <tr>
                <th>Nama <?php echo ucwords($this->setting->sebutan_kabupaten)?></th>
                <td><input name="nama_kabupaten" type="text" class="inputbox" size="25" value="<?php echo $main['nama_kabupaten']?>"/> Kode <input name="kode_kabupaten" type="text" class="inputbox" size="10" value="<?php echo $main['kode_kabupaten']?>"/></td></td>
            </tr>
            <tr>
                <th>Nama Provinsi</th>
                <td><input name="nama_propinsi" type="text" class="inputbox" size="25" value="<?php echo $main['nama_propinsi']?>"/> Kode <input name="kode_propinsi" type="text" class="inputbox" size="10" value="<?php echo $main['kode_propinsi']?>"/></td></td>
            </tr>
            <tr>
                <th class="top">Lambang</th>
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
                <th>Ganti Lambang</th>
                <td><input type="file" name="logo" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah lambang)</span></td>
            </tr>
			<?php if (!$this->setting->offline_mode || (int) $this->setting->offline_level <= 1) { ?>
             <tr>
                <th>Kantor / Wilayah <?php echo ucwords($this->setting->sebutan_desa)?></th>
                <td><a href="<?php echo site_url("hom_desa/ajax_kantor_maps")?>" class="uibutton confirm tipsy south" target="ajax-modalz" rel="window-lok" header="Lokasi <?php echo $main['nama_desa']?>" title="Lokasi <?php echo $main['nama_desa']?>"><span class="fa fa-map-marker"></span> Kantor <?php echo ucwords($this->setting->sebutan_desa)?></a><a href="<?php echo site_url("hom_desa/ajax_wilayah_maps")?>" class="uibutton confirm tipsy south" target="ajax-modalz" rel="window-wil" header="Wilayah <?php echo $main['nama_desa']?>" title="Wilayah <?php echo $main['nama_desa']?>"><span class="fa fa-map"></span> Wilayah <?php echo ucwords($this->setting->sebutan_desa)?></a></td>
            </tr>
			<?php } ?>
        </table>
    </div>

    <div class="ui-layout-south panel bottom">
        <div class="left">
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
                <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
            </div>
        </div>
    </div>
    </form>
</div>
</td></tr></table>
</div>
