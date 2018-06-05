<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/bootstrap/dist/css/bootstrap.min.css">	
<script src="<?php echo base_url()?>assets/plugins/jquery/dist/jquery.slim.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- OpenStreetMap Css -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/leaflet.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/leaflet.pm.css" />
<!-- OpenStreetMap Js-->
<script src="<?php echo base_url()?>assets/js/leaflet.js"></script>
<script src="<?php echo base_url()?>assets/js/leaflet.pm.min.js"></script>

<script>
$(document).ready(function(){
    $('#simpan_wilayah').click(function(){
        var path = $('#path').val();
        $.ajax({
            type: "POST",
            url: "<?=$form_action?>",
            dataType: 'json',
            data: {path: path},
        });
        $(this).closest('.ui-dialog-content').dialog('close');
    });
});
//Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
<?php if(!empty($desa['lat']) && !empty($desa['lng'])): ?>
    var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
    var zoom = <?=$desa['zoom'] ?: 10?>;
<?php else: ?>
    var posisi = [-1.0546279422758742,116.71875000000001];
    var zoom = 4;
<?php endif; ?>

//Menggunakan https://github.com/codeofsumit/leaflet.pm
//Inisialisasi tampilan peta
    var peta_desa = L.map('map').setView(posisi, zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        id: 'mapbox.streets'
    }).addTo(peta_desa);

<?php if(!empty($desa['path'])): ?>
    //Poligon wilayah desa yang tersimpan
    var daerah_desa = <?=$desa['path']?>;

    //Titik awal dan titik akhir poligon harus sama
    daerah_desa[0].push(daerah_desa[0][0]);

    //Tampilkan poligon desa untuk diedit
    var poligon_desa = L.polygon(daerah_desa).addTo(peta_desa);

    //Event untuk mengecek perubahan poligon
    poligon_desa.on('pm:edit', function(e){
        document.getElementById('path').value = getLatLong('Poly', e.target).toString();
    })

    //Fokuskan peta ke poligon
    peta_desa.fitBounds(poligon_desa.getBounds());
<?php endif; ?>

    //Tombol yang akan dimunculkan dipeta
    var options = {
        position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
        drawMarker: false, // adds button to draw markers
        drawPolyline: false, // adds button to draw a polyline
        drawRectangle: false, // adds button to draw a rectangle
        drawPolygon: true, // adds button to draw a polygon
        drawCircle: false, // adds button to draw a cricle
        cutPolygon: false, // adds button to cut a hole in a polygon
        editMode: true, // adds button to toggle edit mode for all layers
        removalMode: true, // adds a button to remove layers
    };

    //Menambahkan toolbar ke peta
    peta_desa.pm.addControls(options);

    //Event untuk menangkap polygon yang dibuat
    peta_desa.on('pm:create', function(e) {
        //Ambil list poligon yang ada
        var keys = Object.keys(peta_desa._layers);
        //Tambahkan event edit ke poligon yang telah dibuat
        peta_desa._layers[keys[2]].on('pm:edit', function(f){
            document.getElementById('path').value = getLatLong(e.shape, e.layer).toString();
        })
        document.getElementById('path').value = getLatLong(e.shape, e.layer).toString();
    });

    function getLatLong(x, y) {
        var hasil;
        if (x == 'Rectangle' || x == 'Line' || x == 'Poly') {
            hasil = JSON.stringify(y._latlngs);
        } else {
            hasil = JSON.stringify(y._latlng);
        }
        hasil = hasil.replace(/\}/g, ']').replace(/(\{)/g, '[').replace(/(\"lat\"\:|\"lng\"\:)/g, '');
        return hasil;
    }
</script>
<style>
#map {
  width: 100%;
  height: 250px;
  border: 1px solid #000;
}
</style>
