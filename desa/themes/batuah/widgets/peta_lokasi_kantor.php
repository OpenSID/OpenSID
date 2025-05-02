<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="maphome">
<div id="map_canvas" class="maphome-height"></div>
<div class="mapleft-head"><h1 class="bgcolor-1 flexcenter"><?="Lokasi Kantor ".ucwords($this->setting->sebutan_desa)?></h1></div>
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
		var lokasi_kantor = L.map('map_canvas').setView(posisi, zoom);
		var baseLayers = getBaseLayers(lokasi_kantor, '<?= $this->setting->mapbox_key; ?>');
		L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(lokasi_kantor);
		<?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
			var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
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
		var lokasi_kantor = L.map('map_canvas', options).setView(posisi, zoom);
		var baseLayers = getBaseLayers(lokasi_kantor, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");
		L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(lokasi_kantor);
		<?php if (!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
			var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
		<?php endif; ?>
</script>
<?php endif; ?>