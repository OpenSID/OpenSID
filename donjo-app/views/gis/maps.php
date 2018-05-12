<script>
(function() {
	var infoWindow;
	window.onload = function(){
		<?php
			//Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
			if(!empty($desa['lat'] && !empty($desa['lng']))){
		?>
			var posisi = [<?php echo $desa['lat'].",".$desa['lng']; ?>];
			var zoom = <?php echo $desa['zoom'] ?: 10; ?>;
		<?
			}else{
				if(!empty($desa['path'])){
		?>
				var wilayah_desa = <?php echo $desa['path']; ?>;
				var posisi = wilayah_desa[0][0];
				var zoom = <?php echo $desa['zoom'] ?: 10; ?>;
		<?php
				}else{
		?>
				var posisi = [-1.0546279422758742,116.71875000000001];
				var zoom = 10;
		<?php
				}
		?>
		<?php
			}
		?>

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
	<?php if($layer_desa==1 && !empty($desa['path'])){?>
		//daerah_desa berupa kumpulan array berisi lat dan long
		//array polygon memiliki kedalaman 2 array [[latlong]]
		var daerah_desa = <?php echo $desa['path']; ?>;
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
		}
		//Menambahkan poligon ke marker
		semua_marker.push(turf.polygon(daerah_desa, {content: "Wilayah Desa", style: style_polygon}))
	<?php }?>

//WILAYAH ADMINISTRATIF - DUSUN RW RT
	<?php if($layer_wilayah==1){?>
	var path_lokasi = <?php echo json_encode($wilayah); ?>;
	<?php foreach($wilayah AS $wil){?>
		<?php $path = preg_split("/\;/", $wil['path']);
		echo "var path_$wil[id] = [";foreach($path AS $p){if($p!=""){echo"new google.maps.LatLng".$p.",";}}echo"];";?>
		var wil = new google.maps.Polygon({
			paths: path_<?php echo $wil['id']?>,
			map: map,
			strokeColor: '#00ff00',
			strokeOpacity: 0.5,
			strokeWeight: 2,
		<?php if($wil['rw']==0 AND $wil['rw']==0){?>
			fillColor: '#00ff00',
		<?php }elseif($wil['rw'] != 0 AND $wil['rt']==0){?>
			fillColor: '#ffff00',
		<?php }else{?>
			fillColor: '#00ffff',
		<?php }?>
			fillOpacity: 0.22
		});
	<?php }}?>

//AREA POLIGON
	<?php if($layer_area==1 && !empty($area)){?>
	//Style polygon
	var area_style = {
			stroke: true,
			color: '#555555',
			opacity: 0.5,
			weight: 1,
			fillColor: '#8888dd',
			fillOpacity: 0.22
		}

	//Data penduduk
	var daftar_area = JSON.parse('<?php echo json_encode($area); ?>');
	var jml = daftar_area.length;
	var jml_path;
	var foto;
	var lokasi_gambar = "<?php echo base_url().LOKASI_FOTO_AREA; ?>";
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
			var content_area = '<div id="content">'+
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
	<?php }?>

//GARIS POLILINE
	<?php if($layer_line==1){?>
		<?php foreach($garis AS $garis){?>
			<?php $path = preg_split("/\;/", $garis['path']);
			echo "var line_$garis[id] = [";foreach($path AS $p){if($p!=""){echo"new google.maps.LatLng".$p.",";}}echo"];";?>

			var garis_<?php echo $garis['id']?> = new google.maps.Polyline({
			  path: line_<?php echo $garis['id']?>,
			  map: map,
			  strokeColor: '#00bb00',
			  strokeOpacity: 0.5,
			  strokeWeight: 5
			});

			google.maps.event.addListener(garis_<?php echo $garis['id']?>, 'click', showArrays_line_<?php echo $garis['id']?>);
			if(!infoWindow){
				infoWindow = new google.maps.InfoWindow();
			}

			function showArrays_line_<?php echo $garis['id']?>(event) {
				var vertices = this.getPath();
				var contentString = '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h1 id="firstHeading" class="firstHeading"><?php echo $garis['nama']?></h1>'+
        '<div id="bodyContent">'+
        '<img src="<?php echo base_url().LOKASI_FOTO_GARIS?>sedang_<?php echo $garis['foto']?>" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>'+
        '<p><?php echo $garis['desk']?></p>'+
        '</div>'+
        '</div>';

				//for (var i =0; i < vertices.getLength(); i++) {
					//var xy = vertices.getAt(i);
					//contentString += '<br>' + 'Coordinate: ' + i + '<br>' + xy.lat() +',' + xy.lng();
				//}
				infoWindow.setContent(contentString);
				infoWindow.setPosition(event.latLng);
				infoWindow.open(map);
			}


		<?php }?>
	<?php }?>

//PROPERTI DESA
	<?php if($layer_point==1){?>
	var shadow = new google.maps.MarkerImage(
		'<?php echo base_url()?>assets/images/gis/point/shadow.png',
		null,
		null,
		new google.maps.Point(16, 35)
	);

	<?php foreach($lokasi AS $data){if($data['lat'] != ""){?>

		<?php $simbol = base_url()."assets/images/gis/point/".$data['simbol'];?>
		var cusicon_<?php echo $data['id']?> = new google.maps.MarkerImage("<?php echo $simbol?>");

		var prop_<?php echo $data['id']?> = new google.maps.Marker({
			position: new google.maps.LatLng(<?php echo $data['lat']?>,<?php echo $data['lng']?>),
			map: map,
			icon: cusicon_<?php echo $data['id']?>,
			shadow: shadow,
			title:"<?php echo $data['nama']?>"
		});

		google.maps.event.addListener(prop_<?php echo $data['id']?>, 'click', function(){
			if(!infoWindow){
				infoWindow = new google.maps.InfoWindow();
			}

			var content = '<table border=0 style="width:400px"><tr>' +
		<?php if($data['foto']!=""){?>
			'<td><img src="<?php echo base_url().LOKASI_FOTO_LOKASI?>sedang_<?php echo $data['foto']?>" class="foto"/></td>' +
		<?php }?>
			'<td style="padding-left:3px"><font size="2.5" style="font-weight:bold;"><?php echo $data['nama']?></font>' +
			'<p><?php echo $data['desk']?></p>'+
			'</tr><tr><td></td></tr></table>';
			infoWindow.setContent(content);
			infoWindow.open(map, prop_<?php echo $data['id']?>);
		});

	<?php }}}?>

//PENDUDUK
	<?php if($layer_penduduk==1 OR $layer_keluarga==1 AND !empty($penduduk)){?>
	//Data penduduk
	var penduduk = <?php echo json_encode($penduduk); ?>;
	var jml = penduduk.length;
	var poto;
	var content;
	var point_style = L.icon({
		iconUrl: '<?php echo base_url()."assets/images/gis/point/pend.png";?>',
		iconSize: [32, 37],
		iconAnchor: [16, 37],
		popupAnchor: [0, -28],
	});
	for(var x = 0; x < jml;x++){
		if(penduduk[x].lat || penduduk[x].lng){
			//Jika penduduk ada foto, maka pakai foto tersebut
			//Jika tidak, pakai foto default
			if(penduduk[x].foto){
				'<td><img src="'+AmbilFoto(penduduk[x].foto)+'" class="foto_pend"/></td>';
			}else poto = '<td><img src="<?php echo base_url()?>assets/files/user_pict/kuser.png" class="foto_pend"/></td>';
			
			//Konten yang akan ditampilkan saat marker diklik
			var content = '<table border=0><tr>' + poto +
				'<td style="padding-left:2px"><font size="2.5" style="bold">'+penduduk[x].nama+'</font> - '+penduduk[x].sex+
				'<p>'+penduduk[x].umur+' Tahun '+penduduk[x].agama+'</p>'+
				'<p>'+penduduk[x].alamat+'</p>'+
				'<p><a href="<?php echo site_url("penduduk/detail/1/0/")?>'+penduduk[x].id+'" target="ajax-modalx" rel="content" header="Rincian Data '+penduduk[x].nama+'" >Data Rincian</a></p></td>'+
				'</tr></table>';
			//Menambahkan point ke marker
			semua_marker.push(turf.point([Number(penduduk[x].lng), Number(penduduk[x].lat)], {content: content, style: point_style}));
		}
	}
	<?php }?>
//EOF PENDUDUK

	//Jika tidak ada centang yang dipilih, maka tidak perlu memproses geojson
	if (semua_marker.length != 0) {
		//Menjalankan geojson menggunakan leaflet
		var geojson = L.geoJSON(turf.featureCollection(semua_marker), {
			//Method yang dijalankan ketika marker diklik
			onEachFeature: function (feature, layer) {
				//Menampilkan pesan berisi content pada saat diklik
				layer.bindPopup(feature.properties.content);
			},
			//Method untuk menambahkan style ke polygon dan line
			style: function(feature){
				if(feature.properties.style){
					return feature.properties.style;
				}
			},
			//Method untuk menambahkan style ke point (titik marker)
			pointToLayer: function (feature, latlng) {
				return L.marker(latlng, {icon: feature.properties.style});
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
			<input type="checkbox" name="layer_desa" value="1"onchange="handle_desa(this);" <?php if($layer_desa==1){echo "checked";}?>> <?php echo ucwords($this->setting->sebutan_desa)?>
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_wilayah" value="1"onchange="handle_wil(this);" <?php if($layer_wilayah==1){echo "checked";}?>> Wilayah Administratif
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_area" value="1"onchange="handle_area(this);" <?php if($layer_area==1){echo "checked";}?>> Area
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_line" value="1"onchange="handle_line(this);" <?php if($layer_line==1){echo "checked";}?>> Line
		</td></tr>
		<tr><td>
			<input type="checkbox" name="layer_point" value="1"onchange="handle_point(this);" <?php if($layer_point==1){echo "checked";}?>> Point
		</td></tr>
		</table>


    <script>
function handle_pend(cb) {
  formAction('mainform','<?php echo site_url('gis')?>/layer_penduduk');
}
function handle_kel(cb) {
  formAction('mainform','<?php echo site_url('gis')?>/layer_keluarga');
}
function handle_desa(cb) {
  formAction('mainform','<?php echo site_url('gis')?>/layer_desa');
}
function handle_wil(cb) {
  formAction('mainform','<?php echo site_url('gis')?>/layer_wilayah');
}
function handle_area(cb) {
  formAction('mainform','<?php echo site_url('gis')?>/layer_area');
}
function handle_line(cb) {
  formAction('mainform','<?php echo site_url('gis')?>/layer_line');
}
function handle_point(cb) {
  formAction('mainform','<?php echo site_url('gis')?>/layer_point');
}
function AmbilFoto(foto, ukuran = "kecil_")
{
  ukuran_foto = ukuran || null
  file_foto = '<?php echo base_url().LOKASI_USER_PICT;?>'+ukuran_foto+foto;
  return file_foto;
}
</script>

     </div>
</div>

<div class="ui-layout-center" id="wrapper">
<div class="table-panel top">
	<div class="left">

<select name="filter" onchange="formAction('mainform','<?php echo site_url('gis/filter')?>')" title="Cari Data">
	<option value="">Status</option>
	<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Tetap</option>
	<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Pasif</option>
	<option value="3" <?php if($filter==3) :?>selected<?php endif?>>Pendatang</option>
</select>

<select name="sex" onchange="formAction('mainform','<?php echo site_url('gis/sex')?>')">
	<option value="">J. Kelamin</option>
	<option value="1" <?php if($sex==1) :?>selected<?php endif?>>Laki-laki</option>
	<option value="2" <?php if($sex==2) :?>selected<?php endif?>>Perempuan</option>
</select>

<select name="dusun" onchange="formAction('mainform','<?php echo site_url('gis/dusun')?>')">
	<option value=""><?php echo ucwords($this->setting->sebutan_dusun)?></option>
	<?php foreach($list_dusun AS $data){?>
	<option <?php if($dusun==$data['dusun']) :?>selected<?php endif?> value="<?php echo $data['dusun']?>"><?php echo $data['dusun']?></option>
	<?php }?>
</select>

<?php if($dusun){?>
<select name="rw" onchange="formAction('mainform','<?php echo site_url('gis/rw')?>')">
	<option value="">RW</option>
	<?php foreach($list_rw AS $data){?>
	<option <?php if($rw==$data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
	<?php }?>
</select>

<?php if($rw){?>
	<select name="rt" onchange="formAction('mainform','<?php echo site_url('gis/rt')?>')">
		<option value="">RT</option>
		<?php foreach($list_rt AS $data){?>
		<option <?php if($rt==$data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
		<?php }?>
	</select>
	<?php }
}?>

<select name="agama" onchange="formAction('mainform','<?php echo site_url('gis/agama')?>')">
	<option value="">Agama</option>
	<?php foreach($list_agama AS $data){?>
	<option value="<?php echo $data['id']?>" <?php if($agama==$data['id']){?>selected<?php }?>><?php echo $data['nama']?></option>
	<?php }?>
</select>

<input name="cari" id="cari" type="text" class="inputbox2 help tipped" size="20" value="<?php echo $cari?>" title="Search.."/>
<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('gis/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
<button href="<?php echo site_url("gis/ajax_adv_search")?>"  target="ajax-modalx" rel="window" header="Pencarian Spesifik"  class="uibutton tipsy south"  title="Pencarian Spesifik"><span class="ui-icon ui-icon-search">&nbsp;</span>Pencarian Spesifik</button>

<a href="<?php echo site_url("gis/clear")?>"  class="uibutton tipsy south"  title="Clear Pencarian" style=""><span class="fa fa-refresh">&nbsp;</span>Bersihkan</a>
		</form>
	</div>
</div>
<div id="map"></div>
