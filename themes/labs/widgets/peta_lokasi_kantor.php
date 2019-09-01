<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script src="<?php echo base_url()?>assets/js/leaflet.js"></script>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title"><i class="si si-support"></i>  <?="Lokasi Kantor ".ucwords($this->setting->sebutan_desa)?>
        </h3>
        <div class="block-options mr-15">
        <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    <div class="block-content pb-10">
    <div id="map_canvas" style="height:200px;"></div><br>
		<button class="btn btn-success btn-block"><a href="https://www.openstreetmap.org/#map=15/<?=$data_config['lat']."/".$data_config['lng']?>" style="color:#fff;" target="_blank">Buka Peta</a></button>
		<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-popout">Detail Kantor</button>
    </div>
</div>
<div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-labelledby="modal-popout" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title"><?="Lokasi Kantor ".ucwords($this->setting->sebutan_desa)?></h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
				<table width="100%">
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px">Alamat</td>
						<td class="isi-info-desa" width="70%"><?= $desa['alamat_kantor']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px"><?= ucwords($this->setting->sebutan_desa)." "?></td>
						<td class="isi-info-desa" width="70%" height="30px"><?= $desa['nama_desa']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px"><?= ucwords($this->setting->sebutan_kecamatan)?></td>
						<td class="isi-info-desa" width="70%" height="30px"><?= $desa['nama_kecamatan']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px"><?= ucwords($this->setting->sebutan_kabupaten)?></td>
						<td class="isi-info-desa" width="70%" height="30px"><?= $desa['nama_kabupaten']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px">Kodepos</td>
						<td class="isi-info-desa" width="70%" height="30px"><?= $desa['kode_pos']?></td>
					</tr>
					<tr style="border-bottom: 1px solid #ddd;">
						<td class="label-info-desa" width="25%" height="30px">Telepon</td>
						<td class="isi-info-desa" width="70%" height="30px"><?= $desa['telepon']?></td>
					</tr>
					<tr>
						<td class="label-info-desa" width="25%" height="40px">Email</td>
						<td class="isi-info-desa" width="70%" height="40px"><?= $desa['email_desa']?></td>
					</tr>
				</table>  
				</div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-alt-success" data-dismiss="modal">
                    <i class="fa fa-check"></i> Tutup
                </button>
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

