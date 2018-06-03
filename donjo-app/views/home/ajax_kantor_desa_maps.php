<script>
$(document).ready(function(){
    $('#simpan_kantor').click(function(){
        var lat = $('#lat').val();
        var lng = $('#lng').val();
        var zoom = $('#zoom').val();
        var map_tipe = $('#map_tipe').val();
        $.ajax({
            type: "POST",
            url: "<?=$form_action?>",
            dataType: 'json',
            data: {lat: lat, lng: lng, zoom: zoom, map_tipe: map_tipe},
        });
        $(this).closest('.ui-dialog-content').dialog('close');
    });
});

(function() {
    //Jika posisi wilayah desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    <?php if(!empty($desa['lat'] && !empty($desa['lng']))): ?>
        var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
        var zoom = <?=$desa['zoom'] ?: 4?>;
    <?php else: ?>
        var posisi = [-1.0546279422758742,116.71875000000001];
        var zoom = 4;
    <?php endif; ?>
    //Inisialisasi tampilan peta
    var lokasi_kantor = L.map('mapx').setView(posisi, zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        id: 'mapbox.streets'
    }).addTo(lokasi_kantor);
    var kantor_desa = L.marker(posisi, {draggable: true}).addTo(lokasi_kantor);
    kantor_desa.on('dragend', function(e){
        document.getElementById('lat').value = e.target._latlng.lat;
            document.getElementById('lng').value = e.target._latlng.lng;
            document.getElementById('map_tipe').value = "HYBRID"
            document.getElementById('zoom').value = lokasi_kantor.getZoom();
    })
    lokasi_kantor.on('zoomstart zoomend', function(e){
        document.getElementById('zoom').value = lokasi_kantor.getZoom();
    })
})();
</script>

<style>
    #mapx {
      width: 420px;
      height: 320px;
      border: 1px solid #000;
    }
</style>

<div id="mapx"></div>
<input type="hidden" name="lat" id="lat" value="<?=$desa['lat']?>"/>
<input type="hidden" name="lng" id="lng"  value="<?=$desa['lng']?>"/>
<input type="hidden" name="zoom" id="zoom"  value="<?=$desa['zoom']?>"/>
<input type="hidden" name="map_tipe" id="map_tipe"  value="<?=$desa['map_tipe']?>"/>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$(this).closest('.ui-dialog-content').dialog('close');">Batal</button>
        <button class="uibutton confirm" id="simpan_kantor" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
