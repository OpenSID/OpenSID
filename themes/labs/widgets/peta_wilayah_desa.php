<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script src="<?php echo base_url()?>assets/js/leaflet.js"></script>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title">
            <i class="si si-globe-alt"></i>
             <?="Wilayah ".ucwords($this->setting->sebutan_desa)?>
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
    <div class="block-content">
        <div id="map_wilayah" style="height:200px;"></div><br>
        <a
            href="https://www.openstreetmap.org/#map=15/<?=$data_config['lat']."
            /".$data_config['lng']?>"
            class="btn btn-primary btn-block"
            target="_blank">Buka Peta</a>
            <br>
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
    //Style polygon
    var style_polygon = {
        stroke: true,
        color: '#555555',
        opacity: 0.5,
        weight: 2,
        fillColor: '#8888dd',
        fillOpacity: 0.05
    };
    var wilayah_desa = L.map('map_wilayah').setView(posisi, zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        id: 'wilayah_desa'
    }).addTo(wilayah_desa);
//Jika wilayah belum ada, maka posisi peta akan menampilkan seluruh Indonesia
<?php if (!empty($data_config['path'])): ?>
    var polygon_desa = <?= $data_config['path']; ?>;
    var kantor_desa = L.polygon(polygon_desa, style_polygon).bindTooltip("Wilayah Desa").addTo(wilayah_desa);
    wilayah_desa.fitBounds(kantor_desa.getBounds());
<?php endif; ?>
</script>