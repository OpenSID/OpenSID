<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="maphome">
	<div class="rowsame">
		<div class="wilayah-left flexleft">
		<div class="padding-10" style="margin-top:20px;">
			<table class="table-noborder" style="width:100%;">
				<tr>
					<td>Latitude</td><td style="width:20px;text-align:center;">:</td><td><?=$data_config['lat']?></td>
				</tr>
				<tr>
					<td>Longitude</td><td style="width:20px;text-align:center;">:</td><td><?=$data_config['lng']?></td>
				</tr>
			</table>
			<p><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?>, <?=ucwords($this->setting->sebutan_kecamatan)?> <?=$desa['nama_kecamatan']?>, <?=ucwords($this->setting->sebutan_kabupaten)?> <?=$desa['nama_kabupaten']?> - <?=$desa['nama_propinsi']?></p>
			<p><a href="https://www.openstreetmap.org/#map=15/<?=$data_config['lat']."/".$data_config['lng']?>" rel="noopener noreferrer" target="_blank">Buka Peta</a></p>
		</div>
		</div>
		<div class="wilayah-right">
		<div id="map_wilayah" class="maphome-height"></div>
		</div>
	</div>
	<div class="mapleft-head"><h1 class="bgcolor-1 flexcenter"><?="Wilayah ".ucwords($this->setting->sebutan_desa)?></h1></div>
</div>
<?php if(IS_PREMIUM) : ?>
	<script>
		<?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
			var posisi = [<?=$data_config['lat'].",".$data_config['lng']?>];
			var zoom = <?=$data_config['zoom'] ?: 10?>;
		<?php else: ?>
			var posisi = [-1.0546279422758742,116.71875000000001];
			var zoom = 10;
		<?php endif; ?>
		var style_polygon = {
			stroke: true,
			color: '#FF0000',
			opacity: 1,
			weight: 2,
			fillColor: '#8888dd',
			fillOpacity: 0.5
		};
		var wilayah_desa = L.map('map_wilayah').setView(posisi, zoom);
		var baseLayers = getBaseLayers(wilayah_desa, '<?= $this->setting->mapbox_key; ?>');
		L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(wilayah_desa);
		<?php if (!empty($data_config['path'])): ?>
			var polygon_desa = <?= $data_config['path']; ?>;
			var kantor_desa = L.polygon(polygon_desa, style_polygon).bindTooltip("Wilayah Desa").addTo(wilayah_desa);
			wilayah_desa.fitBounds(kantor_desa.getBounds());
		<?php endif; ?>
	</script>
<?php else: ?>
	<script>
		<?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
			var posisi = [<?=$data_config['lat'].",".$data_config['lng']?>];
			var zoom = <?=$data_config['zoom'] ?: 10?>;
		<?php else: ?>
			var posisi = [-1.0546279422758742,116.71875000000001];
			var zoom = 10;
		<?php endif; ?>
		var options = {
			maxZoom: <?= setting('max_zoom_peta') ?>,
			minZoom: <?= setting('min_zoom_peta') ?>,
		};
		var style_polygon = {
			stroke: true,
			color: '#FF0000',
			opacity: 1,
			weight: 2,
			fillColor: '#8888dd',
			fillOpacity: 0.5
		};
		var wilayah_desa = L.map('map_wilayah', options).setView(posisi, zoom);
		var baseLayers = getBaseLayers(wilayah_desa, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");
		L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(wilayah_desa);
		<?php if (!empty($data_config['path'])): ?>
			var polygon_desa = <?= $data_config['path']; ?>;
			var kantor_desa = L.polygon(polygon_desa, style_polygon).bindTooltip("Wilayah Desa").addTo(wilayah_desa);
			wilayah_desa.fitBounds(kantor_desa.getBounds());
		<?php endif; ?>
	</script>
<?php endif; ?>