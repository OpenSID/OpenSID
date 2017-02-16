<div id="pageC"> 
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Pengaturan</h3>
</div>
 <form action="<?php echo site_url("hom_desa/update/$main[id]")?>" method="POST" enctype="multipart/form-data">
 <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
 <table class="form">
 <tr>
 <th width="110">Nama Desa</th>
 <td><input name="nama_desa" type="text" class="inputbox" size="25" value="<?php echo $main['nama_desa']?>"/> Kode <input name="kode_desa" type="text" class="inputbox" size="6" value="<?php echo $main['kode_desa']?>"/> Kode POS <input name="kode_pos" type="text" class="inputbox" size="8" value="<?php echo $main['kode_pos']?>"/></td>
 </tr>
 <tr>
 <th>Nama Kepala Desa</th>
 <td><input name="nama_kepala_desa" type="text" class="inputbox" size="40" value="<?php echo $main['nama_kepala_desa']?>"/></td>
 </tr>
 <tr>
 <th>NIP Kepala Desa</th>
 <td><input name="nip_kepala_desa" type="text" class="inputbox" size="40" value="<?php echo $main['nip_kepala_desa']?>"/></td>
 </tr>
 <tr>
 <th>Alamat Kantor Desa</th>
 <td><input name="alamat_kantor" type="text" class="inputbox" size="40" value="<?php echo $main['alamat_kantor']?>"/></td>
 </tr>
 <tr>
 <th>Email Desa</th>
 <td><input name="email_desa" type="text" class="inputbox" size="40" value="<?php echo $main['email_desa']?>"/></td>
 </tr>
 <tr>
 <th>Nama Kecamatan</th>
 <td><input name="nama_kecamatan" type="text" class="inputbox" size="25" value="<?php echo $main['nama_kecamatan']?>"/> Kode <input name="kode_kecamatan" type="text" class="inputbox" size="10" value="<?php echo $main['kode_kecamatan']?>"/></td></td>
 </tr>
 <tr>
 <th>Nama Camat</th>
 <td><input name="nama_kepala_camat" type="text" class="inputbox" size="40" value="<?php echo $main['nama_kepala_camat']?>"/></td>
 </tr>
 <tr>
 <th>NIP Camat</th>
 <td><input name="nip_kepala_camat" type="text" class="inputbox" size="25" value="<?php echo $main['nip_kepala_camat']?>"/></td>
 </tr>
 <tr>
 <th>Nama Kabupaten</th>
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
					<img src="<?php echo base_url()?>assets/files/logo/<?php echo $main['logo']?>" alt=""/>
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
 <tr>
 <th>Lokasi / Wilayah Desa Dalam Peta</th>
 <td>
	 <a href="<?php echo site_url("hom_desa/ajax_kantor_maps")?>" class="uibutton confirm" target="ajax-modalz" rel="window-lok" header="Lokasi <?php echo $main['nama_desa']?>" title="Lokasi <?php echo $main['nama_desa']?>">Kantor Desa</a><p class="kan" style="display:none;">*) data kantor desa telah diperbaharui, kliksimpan untuk melanjutkan...</p></br></br>
	 <a href="<?php echo site_url("hom_desa/ajax_wilayah_maps")?>" class="uibutton confirm" target="ajax-modalz" rel="window-wil" header="Wilayah <?php echo $main['nama_desa']?>" title="Wilayah <?php echo $main['nama_desa']?>">Wilayah Desa</a><p class="wil" style="display:none;">*) data wilayah telah diperbaharui, kliksimpan untuk melanjutkan...</p>
 </td>
 </tr>
 </table>
 </div>
 
 <div class="ui-layout-south panel bottom">
 <div class="left"> 
 <input type="hidden" name="lat" id="lat" value="<?php echo $main['lat']?>"/>
 <input type="hidden" name="lng" id="lng" value="<?php echo $main['lng']?>"/>
 <input type="hidden" id="dataPanel" name="path" value="<?php echo $main['path']?>">
 <input type="hidden" name="zoom" id="zoom" value="<?php echo $main['zoom']?>"/>
 <input type="hidden" name="map_tipe" id="map_tipe" value="<?php echo $main['map_tipe']?>"/>
 </div>
 <div class="right">
 <div class="uibutton-group">
 
 <button class="uibutton confirm" type="submit">Simpan</button>
 </div>
 </div>
 </div>
 </form>
</div>
</td></tr></table>
</div>