<script>
(function() {
	var infoWindow;
	window.onload = function(){
    <?php if(!empty($desa['lat'] AND !empty($desa['lng']))): ?>
        var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
        var zoom   = <?=$desa['zoom'] ?: 10?>;
    <?php elseif(!empty($desa['path'])): ?>
        var wilayah_desa = <?=$desa['path']?>;
        var posisi       = wilayah_desa[0][0];
        var zoom         = <?=$desa['zoom'] ?: 10?>;
    <?php else: ?>
        var posisi = [-1.0546279422758742,116.71875000000001];
        var zoom   = 10;
    <?php endif; ?>

        //Membuat peta, dan menyimpannya ke variabel 'peta' secara global
        var mymap = L.map('map').setView(posisi, zoom);

        //Menambahkan tile layer OSM ke peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            id: 'mapbox.streets'
        }).addTo(mymap); //Menambahkan tile layer ke variabel 'peta'

        //Semua marker akan ditampung divariabel ini
        var semua_marker = [];

        //WILAYAH DESA
        <?php if($layer_desa==1 AND !empty($desa['path'])): ?>
            //daerah_desa berupa kumpulan array berisi lat dan long
            //array polygon memiliki kedalaman 2 array [[latlong]]
            var daerah_desa = <?=$desa['path']?>;
            var jml = daerah_desa[0].length;
        
            //Titik awal dan titik akhir poligon harus sama
            daerah_desa[0].push(daerah_desa[0][0]);
            
            //TurfJS menangkap nilai lat dan long secara terbalik (long, lat)
            //Maka perlu dilakukan proses membalikan array agar menjadi (long, lat)
            for(var x = 0; x < jml; x++){
                daerah_desa[0][x].reverse();
            }
        
            //Style polygon
            var style_polygon = {
                stroke: true,
                color: '#555555',
                opacity: 0.5,
                weight: 2,
                fillColor: '#8888dd',
                fillOpacity: 0.05
            };
            //Menambahkan poligon ke marker
            semua_marker.push(turf.polygon(daerah_desa, {content: "Wilayah Desa", style: style_polygon}))
            //Menambahkan point kantor desa
            semua_marker.push(turf.point([<?=$desa['lng'].",".$desa['lat']?>], {content: "Kantor Desa"}))
        <?php endif; ?>

        //WILAYAH ADMINISTRATIF - DUSUN RW RT
        <?php if($layer_wilayah==1 AND !empty($wilayah)): ?>
            var path_wilayah_adm = <?=json_encode($wilayah)?>;
            var jml = path_wilayah_adm[0].length;
            var wil = {
                paths: path_<?=$wil['id']?>,
                map: map,
                strokeColor: '#00ff00',
                strokeOpacity: 0.5,
                strokeWeight: 2,
                <?php if($wil['rw']==0 AND $wil['rw']==0): ?>
                    fillColor: '#00ff00',
                <?php elseif($wil['rw'] != 0 AND $wil['rt']==0): ?>
                    fillColor: '#ffff00',
                <?php else: ?>
                    fillColor: '#00ffff',
                <?php endif; ?>
                fillOpacity: 0.22
            };
        <?php endif; ?>

        //LOKASI DAN PROPERTI
        <?php if($layer_lokasi == 1 AND !empty($lokasi)): ?>
            var daftar_lokasi = JSON.parse('<?=json_encode($lokasi)?>');
            var jml = daftar_lokasi.length;
            var content;
            var foto;
            var path_foto = '<?=base_url()."assets/images/gis/point/"?>';
            var point_style = {
                iconSize: [32, 37],
                iconAnchor: [16, 37],
                popupAnchor: [0, -28],
            };
            for(var x = 0; x < jml; x++){
                if(daftar_lokasi[x].lat){
                    point_style.iconUrl = path_foto+daftar_lokasi[x].simbol;
                    if(daftar_lokasi[x].foto){
                        foto = '<td><img src="'+AmbilFotoLokasi(daftar_lokasi[x].foto)+'" class="foto_pend"/></td>';
                    }else foto = '';
                    content = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h1 id="firstHeading" class="firstHeading">'+daftar_lokasi[x].nama+'</h1>'+
                        '<div id="bodyContent">'+ foto +
                        '<p>'+daftar_lokasi[x].desk+'</p>'+
                        '</div>'+
                        '</div>';
                    semua_marker.push(turf.point([daftar_lokasi[x].lng, daftar_lokasi[x].lat], {content: content,style: L.icon(point_style)}));
                }
            }
        <?php endif; ?>
        
        //AREA
        <?php if($layer_area==1 AND !empty($area)): ?>
        
            //Style polygon
            var area_style = {
                stroke: true,
                color: '#555555',
                opacity: 0.5,
                weight: 1,
                fillColor: '#8888dd',
                fillOpacity: 0.22
            }
            
            var daftar_area = JSON.parse('<?=json_encode($area)?>');
            var jml = daftar_area.length;
            var jml_path;
            var foto;
            var content_area;
            var lokasi_gambar = "<?=base_url().LOKASI_FOTO_AREA?>";
            for(var x = 0; x < jml;x++){
                if(daftar_area[x].path){
                    daftar_area[x].path = JSON.parse(daftar_area[x].path)
                    jml_path = daftar_area[x].path[0].length;
                    for(var y = 0; y < jml_path; y++){
                        daftar_area[x].path[0][y].reverse()
                    }
                    if(daftar_area[x].foto){
                        foto = '<img src="'+lokasi_gambar+'sedang_'+daftar_area[x].foto+'" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>';
                    }else foto = "";
                    content_area = '<div id="content">'+
                        '<div id="siteNotice">'+
                        '</div>'+
                        '<h1 id="firstHeading" class="firstHeading">'+daftar_area[x].nama+'</h1>'+
                        '<div id="bodyContent">'+ foto +
                        '<p>'+daftar_area[x].desk+'</p>'+
                        '</div>'+
                        '</div>';
                    
                    daftar_area[x].path[0].push(daftar_area[x].path[0][0])
                    //Menambahkan point ke marker
                    semua_marker.push(turf.polygon(daftar_area[x].path, {content: content_area, style: area_style}));
                }
            }
        <?php endif; ?>

        //PENDUDUK
        <?php if($layer_penduduk==1 OR $layer_keluarga==1 AND !empty($penduduk)): ?>
            //Data penduduk
            var penduduk = <?=json_encode($penduduk)?>;
            var jml = penduduk.length;
            var foto;
            var content;
            var point_style = L.icon({
                iconUrl: '<?=base_url()."assets/images/gis/point/pend.png"?>',
                iconSize: [32, 37],
                iconAnchor: [16, 37],
                popupAnchor: [0, -28],
            });
            for(var x = 0; x < jml;x++){
                if(penduduk[x].lat || penduduk[x].lng){
                    //Jika penduduk ada foto, maka pakai foto tersebut
                    //Jika tidak, pakai foto default
                    if(penduduk[x].foto){
                        foto = '<td><img src="'+AmbilFoto(penduduk[x].foto)+'" class="foto_pend"/></td>';
                    }else foto = '<td><img src="<?=base_url()?>assets/files/user_pict/kuser.png" class="foto_pend"/></td>';
                    
                    //Konten yang akan ditampilkan saat marker diklik
                    content = '<table border=0><tr>' + foto +
                        '<td style="padding-left:2px"><font size="2.5" style="bold">'+penduduk[x].nama+'</font> - '+penduduk[x].sex+
                        '<p>'+penduduk[x].umur+' Tahun '+penduduk[x].agama+'</p>'+
                        '<p>'+penduduk[x].alamat+'</p>'+
                        '<p><a href="<?=site_url("penduduk/detail/1/0/")?>'+penduduk[x].id+'" target="ajax-modalx" rel="content" header="Rincian Data '+penduduk[x].nama+'" >Data Rincian</a></p></td>'+
                        '</tr></table>';
                    //Menambahkan point ke marker
                    semua_marker.push(turf.point([Number(penduduk[x].lng), Number(penduduk[x].lat)], {content: content, style: point_style}));
                }
            }
        <?php endif; ?>


        //Jika tidak ada centang yang dipilih, maka tidak perlu memproses geojson
        if (semua_marker.length != 0) {
            //Menjalankan geojson menggunakan leaflet
            var geojson = L.geoJSON(turf.featureCollection(semua_marker), {
                //Method yang dijalankan ketika marker diklik
                onEachFeature: function (feature, layer) {
                    //Menampilkan pesan berisi content pada saat diklik
                    layer.bindPopup(feature.properties.content);
                    layer.bindTooltip(feature.properties.content);
                },
                //Method untuk menambahkan style ke polygon dan line
                style: function(feature){
                    if(feature.properties.style){
                        return feature.properties.style;
                    }
                },
                //Method untuk menambahkan style ke point (titik marker)
                pointToLayer: function (feature, latlng) {
                    if(feature.properties.style){
                        return L.marker(latlng, {icon: feature.properties.style});
                    }else return L.marker(latlng);
                }
            }).addTo(mymap);
    
            //Mempusatkan tampilan map agar semua marker terlihat
            mymap.fitBounds(geojson.getBounds());
        }
}; //EOF window.onload

	})();
</script>


<style>
#map{
	width:100%;
	height:94%;
}

.foto{
	width:200px;
	height:140px;
	border-radius:3px;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border:2px solid #555555;
}

.foto_pend{
 width:70px;height:70px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;
}
</style>

<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-west" id="sidebar" style="width:200px;">
    <div class="module">
		<table border="0" >
		<h3>Legenda</h3>
		<tr><td>
			<input type="checkbox" name="layer_penduduk" value="1" onchange="handle_pend(this);" <?php if($layer_penduduk==1){echo "checked";}?>> Penduduk
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_keluarga" value="1" onchange="handle_kel(this);" <?php if($layer_keluarga==1){echo "checked";}?>> Keluarga
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_desa" value="1"onchange="handle_desa(this);" <?php if($layer_desa==1){echo "checked";}?>> <?=ucwords($this->setting->sebutan_desa)?>
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_wilayah" value="1"onchange="handle_wil(this);" <?php if($layer_wilayah==1){echo "checked";}?>> Wilayah Administratif
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_area" value="1"onchange="handle_area(this);" <?php if($layer_area==1){echo "checked";}?>> Area
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_lokasi" value="1"onchange="handle_lokasi(this);" <?php if($layer_lokasi==1){echo "checked";}?>> Lokasi/Properti Desa
		</td></tr>
		</table>


    <script>
function handle_pend(cb) {
  formAction('mainform','<?=site_url('gis')?>/layer_penduduk');
}
function handle_kel(cb) {
  formAction('mainform','<?=site_url('gis')?>/layer_keluarga');
}
function handle_desa(cb) {
  formAction('mainform','<?=site_url('gis')?>/layer_desa');
}
function handle_wil(cb) {
  formAction('mainform','<?=site_url('gis')?>/layer_wilayah');
}
function handle_area(cb) {
  formAction('mainform','<?=site_url('gis')?>/layer_area');
}
function handle_lokasi(cb) {
  formAction('mainform','<?=site_url('gis')?>/layer_lokasi');
}
function AmbilFoto(foto, ukuran = "kecil_")
{
  ukuran_foto = ukuran || null
  file_foto = '<?=base_url().LOKASI_USER_PICT;?>'+ukuran_foto+foto;
  return file_foto;
}
function AmbilFotoLokasi(foto, ukuran = "kecil_")
{
  ukuran_foto = ukuran || null
  file_foto = '<?=base_url().LOKASI_FOTO_LOKASI;?>'+ukuran_foto+foto;
  return file_foto;
}
</script>

     </div>
</div>

<div class="ui-layout-center" id="wrapper">
<div class="table-panel top">
	<div class="left">

<select name="filter" onchange="formAction('mainform','<?=site_url('gis/filter')?>')" title="Cari Data">
	<option value="">Status</option>
	<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Tetap</option>
	<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Pasif</option>
	<option value="3" <?php if($filter==3) :?>selected<?php endif?>>Pendatang</option>
</select>

<select name="sex" onchange="formAction('mainform','<?=site_url('gis/sex')?>')">
	<option value="">J. Kelamin</option>
	<option value="1" <?php if($sex==1) :?>selected<?php endif?>>Laki-laki</option>
	<option value="2" <?php if($sex==2) :?>selected<?php endif?>>Perempuan</option>
</select>

<select name="dusun" onchange="formAction('mainform','<?=site_url('gis/dusun')?>')">
	<option value=""><?=ucwords($this->setting->sebutan_dusun)?></option>
	<?php foreach($list_dusun AS $data){?>
	<option <?php if($dusun==$data['dusun']) :?>selected<?php endif?> value="<?=$data['dusun']?>"><?=$data['dusun']?></option>
	<?php }?>
</select>

<?php if($dusun){?>
<select name="rw" onchange="formAction('mainform','<?=site_url('gis/rw')?>')">
	<option value="">RW</option>
	<?php foreach($list_rw AS $data){?>
	<option <?php if($rw==$data['rw']) :?>selected<?php endif?>><?=$data['rw']?></option>
	<?php }?>
</select>

<?php if($rw){?>
	<select name="rt" onchange="formAction('mainform','<?=site_url('gis/rt')?>')">
		<option value="">RT</option>
		<?php foreach($list_rt AS $data){?>
		<option <?php if($rt==$data['rt']) :?>selected<?php endif?>><?=$data['rt']?></option>
		<?php }?>
	</select>
	<?php }
}?>

<select name="agama" onchange="formAction('mainform','<?=site_url('gis/agama')?>')">
	<option value="">Agama</option>
	<?php foreach($list_agama AS $data){?>
	<option value="<?=$data['id']?>" <?php if($agama==$data['id']){?>selected<?php }?>><?=$data['nama']?></option>
	<?php }?>
</select>

<input name="cari" id="cari" type="text" class="inputbox2 help tipped" size="20" value="<?=$cari?>" title="Search.."/>
<button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('gis/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
<button href="<?=site_url("gis/ajax_adv_search")?>"  target="ajax-modalx" rel="window" header="Pencarian Spesifik"  class="uibutton tipsy south"  title="Pencarian Spesifik"><span class="ui-icon ui-icon-search">&nbsp;</span>Pencarian Spesifik</button>

<a href="<?=site_url("gis/clear")?>"  class="uibutton tipsy south"  title="Clear Pencarian" style=""><span class="fa fa-refresh">&nbsp;</span>Bersihkan</a>
		</form>
	</div>
</div>
<div id="map"></div>
