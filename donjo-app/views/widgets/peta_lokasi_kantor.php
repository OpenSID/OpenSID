<style type="text/css">
	button.btn { margin-left: 0px; }
	#collapse2 { margin-top: 5px; }
	button[aria-expanded=true] .fa-chevron-down {
	   display: none;
	}
	button[aria-expanded=false] .fa-chevron-up {
	   display: none;
	}
</style>
<!-- widget Peta Lokasi Kantor Desa -->
<div class="box box-default box-solid">
  <div class="box-header">
    <h3 class="box-title">
    <i class="fa fa-map-marker"></i>
    <?="Lokasi Kantor ".ucwords($this->setting->sebutan_desa)?></h3>
  </div>
  <div class="box-body">
    <div id="map_canvas" style="height:200px;"></div>
    <button class="btn btn-success btn-block"><a href="https://www.openstreetmap.org/#map=15/<?=$data_config['lat']."/".$data_config['lng']?>" style="color:#fff;" target="_blank">Buka Peta</a></button>
		<button class="btn btn-success btn-block" data-toggle="collapse" data-target="#collapse2" aria-expanded="false">
			Detail
      <i class="fa fa-chevron-up pull-right"></i>
      <i class="fa fa-chevron-down pull-right"></i>
		</button>
		<div id="collapse2" class="panel-collapse collapse">
			<div class="info-desa">
				<table width="100%">
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px">Alamat</td>
						<td class="isi-info-desa" width="70%"><?php echo $desa['alamat_kantor']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px"><?php echo ucwords($this->setting->sebutan_desa)." "?></td>
						<td class="isi-info-desa" width="70%" height="30px"><?php echo $desa['nama_desa']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px"><?php echo ucwords($this->setting->sebutan_kecamatan)?></td>
						<td class="isi-info-desa" width="70%" height="30px"><?php echo $desa['nama_kecamatan']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px"><?php echo ucwords($this->setting->sebutan_kabupaten)?></td>
						<td class="isi-info-desa" width="70%" height="30px"><?php echo $desa['nama_kabupaten']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px">Kodepos</td>
						<td class="isi-info-desa" width="70%" height="30px"><?php echo $desa['kode_pos']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px">Telepon</td>
						<td class="isi-info-desa" width="70%" height="30px"><?php echo $desa['telepon']?></td>
					</tr>
					<tr>
						<td class="label-info-desa" width="25%" height="40px">Email</td>
						<td class="isi-info-desa" width="70%" height="40px"><?php echo $desa['email_desa']?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
//Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
<?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
    var posisi = [<?=$data_config['lat'].",".$data_config['lng']?>];
    var zoom = <?=$data_config['zoom'] ?: 10?>;
<?php else: ?>
    var posisi = [-1.0546279422758742,116.71875000000001];
    var zoom = 10;
<?php endif; ?>

    var lokasi_kantor = L.map('map_canvas').setView(posisi, zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        id: 'mapbox.streets'
    }).addTo(lokasi_kantor);
//Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
<?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
    var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
<?php endif; ?>
</script>
