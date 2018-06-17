<!-- widget Peta Lokasi Kantor Desa -->
<div class="box box-default box-solid">
    <div class="box-header">
        <h3 class="box-title">
        <i class="fa fa-map-marker"></i>
        <?="Lokasi Kantor ".ucwords($this->setting->sebutan_desa)?></h3>
    </div>
    <div class="box-body">
        <div id="map_canvas" style="height:200px;"></div>
        <a href="https://www.openstreetmap.org/#map=15/<?=$data_config['lat']."/".$data_config['lng']?>">Buka peta</a>
    </div>
</div>

<script>
//Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
<?php if(!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
    var posisi = [<?=$data_config['lat'].",".$data_config['lng']?>];
    var zoom = <?=$data_config['zoom'] ?: 10?>;
<? else: ?>
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
<?php if(!empty($data_config['lat']) && !empty($data_config['lng'])): ?>
    var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
<?php endif; ?>
</script>
