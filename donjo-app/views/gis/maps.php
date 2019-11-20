<script>
(function()
{
  var infoWindow;
  window.onload = function()
  {
		<?php if (!empty($desa['lat']) AND !empty($desa['lng'])): ?>
			var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
			var zoom = <?=$desa['zoom'] ?: 10?>;
		<?php elseif (!empty($desa['path'])): ?>
			var wilayah_desa = <?=$desa['path']?>;
			var posisi = wilayah_desa[0][0];
			var zoom = <?=$desa['zoom'] ?: 10?>;
		<?php else: ?>
			var posisi = [-1.0546279422758742,116.71875000000001];
			var zoom   = 10;
		<?php endif; ?>

		//Membuat peta, dan menyimpannya ke variabel 'peta' secara global
		var mymap = L.map('map').setView(posisi, zoom);

		//Menambahkan tile layer OSM ke peta
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',	{
		  maxZoom: 19,
		  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
		  id: 'mapbox.streets'
		}).addTo(mymap); //Menambahkan tile layer ke variabel 'peta'

		//Semua marker akan ditampung divariabel ini
		var semua_marker = [];

		//WILAYAH DESA
		<?php if ($layer_desa==1 AND !empty($desa['path'])): ?>

			var daerah_desa = <?=$desa['path']?>;
			var daftar_desa = JSON.parse('<?=addslashes(json_encode($desa))?>');
			var jml = daerah_desa[0].length;
			var content_desa;

			daerah_desa[0].push(daerah_desa[0][0]);

			for (var x = 0; x < jml; x++)
			{
			  daerah_desa[0][x].reverse();
			}

			//Style polygon
			var style_polygon = {
			  stroke: true,
			  color: '#FF0000',
			  opacity: 1,
			  weight: 2,
			  fillColor: '#8888dd',
			  fillOpacity: 0.5
			};

			content_desa =
			'<div id="content">'+
			'<center><h5 id="firstHeading" class="firstHeading">Wilayah <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'</h5></center>'+
			'<div id="bodyContent">'+
			'<p><center><a href="#collapseStat" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-bar-chart"></i>Statistik Penduduk</center></p>'+
			'<div class="collapse box-body no-padding" id="collapseStat">'+
			'<div class="card card-body">'+
			'<li class="<?php ($lap==13) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/13/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Umur</a></li>'+
			'<li class="<?php ($lap==0) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/0/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Pendidikan dalam KK</a></li>'+
			'<li class="<?php ($lap==14) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/14/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Pendidikan sedang Ditempuh</a></li>'+
			'<li class="<?php ($lap==1) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/1/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Pekerjaan</a></li>'+
			'<li class="<?php ($lap==2) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/2/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Status Perkawinan</a></li>'+
			'<li class="<?php ($lap==3) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/3/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Agama</a></li>'+
			'<li class="<?php ($lap==4) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/4/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Jenis Kelamin</a></li>'+
			'<li class="<?php ($lap==5) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/5/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Warga Negara</a></li>'+
			'<li class="<?php ($lap==6) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/6/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Status Penduduk</a></li>'+
			'<li class="<?php ($lap==7) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/7/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'"> Golongan Darah</a></li>'+
			'<li class="<?php ($lap==9) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/9/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Penyandang Cacat</a></li>'+
			'<li class="<?php ($lap==10) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/10/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Sakit Menahun</a></li>'+
			'<li class="<?php ($lap==16) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/16/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?>' +daftar_desa.nama_desa+'">Akseptor KB</a></li>'+
			'<li class="<?php ($lap==17) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/17/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Akte Kelahiran</a></li>'+
			'<li class="<?php ($lap==18) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/18/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Kepemilikan KTP</a></li>'+
			'<li class="<?php ($lap==19) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_desa/19/'+daftar_desa.nama_desa+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_desa)?> '+daftar_desa.nama_desa+'">Jenis Asuransi</a></li>'+
			'</div>'+
			'</div>'+
			'</div>'+
			'</div>';

			//Menambahkan poligon ke marker
			semua_marker.push(turf.polygon(daerah_desa, {content: content_desa, style: style_polygon}))
			//Menambahkan point kantor desa
			semua_marker.push(turf.point([<?=$desa['lng'].",".$desa['lat']?>], {content: "Kantor Desa"}))
		<?php endif; ?>


		//WILAYAH DUSUN
		<?php if ($layer_dusun==1 AND !empty($dusun_gis)): ?>
			//Style polygon
			var dusun_style = {
			  stroke: true,
			  color: '#FF0000',
			  opacity: 1,
			  weight: 2,
			  fillColor: '#FFFF00',
			  fillOpacity: 0.5
			}
			var daftar_dusun = JSON.parse('<?=addslashes(json_encode($dusun_gis))?>');
			var jml = daftar_dusun.length;
			var jml_path;
			var content_dusun;

			for (var x = 0; x < jml;x++)
			{
			  if (daftar_dusun[x].path)
			  {
					daftar_dusun[x].path = JSON.parse(daftar_dusun[x].path)
					jml_path = daftar_dusun[x].path[0].length;
					for (var y = 0; y < jml_path; y++)
					{
					  daftar_dusun[x].path[0][y].reverse()
					}

					content_dusun =
					'<div id="content">'+
					'<center><h5 id="firstHeading" class="firstHeading">Wilayah <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'</center></h5>'+
					'<div id="bodyContent">'+
					'<p><center><a href="#collapseStat" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-bar-chart"></i>Statistik Penduduk</center></p>'+
					'<div class="collapse box-body no-padding" id="collapseStat">'+
					'<div class="card card-body">'+
					'<li class="<?php ($lap==13) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/13/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+' ">Umur</a></li>'+
					'<li class="<?php ($lap==0) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/0/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Pendidikan dalam KK</a></li>'+
					'<li class="<?php ($lap==14) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/14/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Pendidikan sedang Ditempuh</a></li>'+
					'<li class="<?php ($lap==1) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/1/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Pekerjaan</a></li>'+
					'<li class="<?php ($lap==2) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/2/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Status Perkawinan</a></li>'+
					'<li class="<?php ($lap==3) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/3/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Agama</a></li>'+
					'<li class="<?php ($lap==4) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/4/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Jenis Kelamin</a></li>'+
					'<li class="<?php ($lap==5) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/5/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Warga Negara</a></li>'+
					'<li class="<?php ($lap==6) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/6/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Status Penduduk</a></li>'+
					'<li class="<?php ($lap==7) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/7/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'"> Golongan Darah</a></li>'+
					'<li class="<?php ($lap==9) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/9/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Penyandang Cacat</a></li>'+
					'<li class="<?php ($lap==10) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/10/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Sakit Menahun</a></li>'+
					'<li class="<?php ($lap==16) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/16/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?>' +daftar_dusun[x].dusun+'">Akseptor KB</a></li>'+
					'<li class="<?php ($lap==17) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/17/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Akte Kelahiran</a></li>'+
					'<li class="<?php ($lap==18) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/18/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Kepemilikan KTP</a></li>'+
					'<li class="<?php ($lap==19) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_dusun/1/19/'+daftar_dusun[x].dusun+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_dusun[x].dusun+'">Jenis Asuransi</a></li>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>';

					daftar_dusun[x].path[0].push(daftar_dusun[x].path[0][0])
					//Menambahkan poligon ke marker
					semua_marker.push(turf.polygon(daftar_dusun[x].path, {content: content_dusun, style: dusun_style}));
					//Menambahkan point kantor desa
					semua_marker.push(turf.point([daftar_dusun[x].lng,daftar_dusun[x].lat], {content: "Kantor Dusun " +daftar_dusun[x].dusun}))
			  }
			}
		<?php endif; ?>


		//WILAYAH RW
		<?php if ($layer_rw==1 AND !empty($rw_gis)): ?>
			//Style polygon
			var rw_style = {
			  stroke: true,
			  color: '#FF0000',
			  opacity: 1,
			  weight: 2,
			  fillColor: '#8888dd',
			  fillOpacity: 0.5
			}
			var daftar_rw = JSON.parse('<?=addslashes(json_encode($rw_gis))?>');
			var jml = daftar_rw.length;
			var jml_path;
			var content_rw;

			for (var x = 0; x < jml;x++)
			{
			  if (daftar_rw[x].path)
			  {
					daftar_rw[x].path = JSON.parse(daftar_rw[x].path)
					jml_path = daftar_rw[x].path[0].length;
					for (var y = 0; y < jml_path; y++)
					{
					  daftar_rw[x].path[0][y].reverse()
					}

					content_rw =
					'<div id="content">'+
					'<center><h5 id="firstHeading" class="firstHeading">Wilayah RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'</center></h5>'+
					'<div id="bodyContent">'+
					'<p><center><a href="#collapseStat" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-bar-chart"></i>Statistik Penduduk</center></p>'+
					'<div class="collapse box-body no-padding" id="collapseStat">'+
					'<div class="card card-body">'+
					'<li class="<?php ($lap==13) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/13/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Umur</a></li>'+
					'<li class="<?php ($lap==0) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/0/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Pendidikan dalam KK</a></li>'+
					'<li class="<?php ($lap==14) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/14/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Pendidikan sedang Ditempuh</a></li>'+
					'<li class="<?php ($lap==1) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/1/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Pekerjaan</a></li>'+
					'<li class="<?php ($lap==2) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/2/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Status Perkawinan</a></li>'+
					'<li class="<?php ($lap==3) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/3/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Agama</a></li>'+
					'<li class="<?php ($lap==4) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/4/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Jenis Kelamin</a></li>'+
					'<li class="<?php ($lap==5) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/5/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Warga Negara</a></li>'+
					'<li class="<?php ($lap==6) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/6/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Status Penduduk</a></li>'+
					'<li class="<?php ($lap==7) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/7/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'"> Golongan Darah</a></li>'+
					'<li class="<?php ($lap==9) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/9/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Penyandang Cacat</a></li>'+
					'<li class="<?php ($lap==10) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/10/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Sakit Menahun</a></li>'+
					'<li class="<?php ($lap==16) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/16/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk ' +daftar_rw[x].rw+'">Akseptor KB</a></li>'+
					'<li class="<?php ($lap==17) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/17/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Akte Kelahiran</a></li>'+
					'<li class="<?php ($lap==18) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/18/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Kepemilikan KTP</a></li>'+
					'<li class="<?php ($lap==19) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rw/1/19/'+daftar_rw[x].dusun+'/'+daftar_rw[x].rw+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RW '+daftar_rw[x].rw+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rw[x].dusun+'">Jenis Asuransi</a></li>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>';

					daftar_rw[x].path[0].push(daftar_rw[x].path[0][0])
					//Menambahkan poligon ke marker
					semua_marker.push(turf.polygon(daftar_rw[x].path, {content: content_rw, style: rw_style}));
					//Menambahkan point kantor desa
					semua_marker.push(turf.point([daftar_rw[x].lng,daftar_rw[x].lat], {content: "Kantor RW " +daftar_rw[x].rw}))
			  }
			}
		<?php endif; ?>

		//WILAYAH RT
		<?php if ($layer_rt==1 AND !empty($rt_gis)): ?>
			//Style polygon
			var rt_style = {
			  stroke: true,
			  color: '#FF0000',
			  opacity: 1,
			  weight: 2,
			  fillColor: '#008000',
			  fillOpacity: 0.5
			}
			var daftar_rt = JSON.parse('<?=addslashes(json_encode($rt_gis))?>');
			var jml = daftar_rt.length;
			var jml_path;
			var content_rt;

			for (var x = 0; x < jml;x++)
			{
			  if (daftar_rt[x].path)
			  {
					daftar_rt[x].path = JSON.parse(daftar_rt[x].path)
					jml_path = daftar_rt[x].path[0].length;
					for (var y = 0; y < jml_path; y++)
					{
					  daftar_rt[x].path[0][y].reverse()
					}

					content_rt =
					'<div id="content">'+
					'<center><h5 id="firstHeading" class="firstHeading">Wilayah RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'</center></h5>'+
					'<div id="bodyContent">'+
					'<p><center><a href="#collapseStat" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Statistik Penduduk" data-toggle="collapse" data-target="#collapseStat" aria-expanded="false" aria-controls="collapseStat"><i class="fa  fa-bar-chart"></i>Statistik Penduduk</center></p>'+
					'<div class="collapse box-body no-padding" id="collapseStat">'+
					'<div class="card card-body">'+
					'<li class="<?php ($lap==13) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/13/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Umur</a></li>'+
					'<li class="<?php ($lap==0) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/0/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Pendidikan dalam KK</a></li>'+
					'<li class="<?php ($lap==14) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/14/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Pendidikan sedang Ditempuh</a></li>'+
					'<li class="<?php ($lap==1) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/1/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Pekerjaan</a></li>'+
					'<li class="<?php ($lap==2) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/2/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Status Perkawinan</a></li>'+
					'<li class="<?php ($lap==3) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/3/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Agama</a></li>'+
					'<li class="<?php ($lap==4) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/4/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Jenis Kelamin</a></li>'+
					'<li class="<?php ($lap==5) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/5/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Warga Negara</a></li>'+
					'<li class="<?php ($lap==6) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/6/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Status Penduduk</a></li>'+
					'<li class="<?php ($lap==7) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/7/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'"> Golongan Darah</a></li>'+
					'<li class="<?php ($lap==9) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/9/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Penyandang Cacat</a></li>'+
					'<li class="<?php ($lap==10) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/10/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Sakit Menahun</a></li>'+
					'<li class="<?php ($lap==16) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/16/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk' +daftar_rt[x].rt+'">Akseptor KB</a></li>'+
					'<li class="<?php ($lap==17) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/17/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Akte Kelahiran</a></li>'+
					'<li class="<?php ($lap==18) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/18/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Kepemilikan KTP</a></li>'+
					'<li class="<?php ($lap==19) and print("active") ?>"><a href="<?=site_url("statistik/graph_gis_rt/1/19/'+daftar_rt[x].dusun+'/'+daftar_rt[x].rw+'/'+daftar_rt[x].rt+'")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Statistik Penduduk RT '+daftar_rt[x].rt+' <?=ucwords($this->setting->sebutan_dusun)?> '+daftar_rt[x].dusun+'">Jenis Asuransi</a></li>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>';

					daftar_rt[x].path[0].push(daftar_rt[x].path[0][0])
					//Menambahkan poligon ke marker
					semua_marker.push(turf.polygon(daftar_rt[x].path, {content: content_rt, style: rt_style}));
					//Menambahkan point kantor desa
					semua_marker.push(turf.point([daftar_rt[x].lng,daftar_rt[x].lat], {content: "Kantor RT " +daftar_rt[x].rt}))
			  }
			}
		<?php endif; ?>

		//LOKASI DAN PROPERTI
		<?php if ($layer_lokasi == 1 AND !empty($lokasi)): ?>
			var daftar_lokasi = JSON.parse('<?=addslashes(json_encode($lokasi))?>');
			var jml = daftar_lokasi.length;
			var content;
			var foto;
			var path_foto = '<?= base_url()."assets/images/gis/point/"?>';
			var point_style = {
			  iconSize: [32, 37],
			  iconAnchor: [16, 37],
			  popupAnchor: [0, -28],
			};

			for (var x = 0; x < jml; x++)
			{
			  if (daftar_lokasi[x].lat)
			  {
					point_style.iconUrl = path_foto+daftar_lokasi[x].simbol;
					if (daftar_lokasi[x].foto)
					{
					  foto = '<td><img src="'+AmbilFotoLokasi(daftar_lokasi[x].foto)+'" class="foto_pend"/></td>';
					}
					else
					foto = '';
					content = '<div id="content">'+
					'<div id="siteNotice">'+
					'</div>'+
					'<h4 id="firstHeading" class="firstHeading">'+daftar_lokasi[x].nama+'</h4>'+
					'<div id="bodyContent">'+ foto +
					'<p>'+daftar_lokasi[x].desk+'</p>'+
					'</div>'+
					'</div>';
					semua_marker.push(turf.point([daftar_lokasi[x].lng, daftar_lokasi[x].lat], {content: content,style: L.icon(point_style)}));
			  }
			}
			<?php endif; ?>

			//AREA
			<?php if ($layer_area==1 AND !empty($area)): ?>
			var daftar_area = JSON.parse('<?=addslashes(json_encode($area))?>');
			var jml = daftar_area.length;
			var jml_path;
			var foto;
			var content_area;
			var lokasi_gambar = "<?= base_url().LOKASI_FOTO_AREA?>";

			for (var x = 0; x < jml;x++)
			{
        if (daftar_area[x].path)
			  {
          daftar_area[x].path = JSON.parse(daftar_area[x].path)
  				jml_path = daftar_area[x].path[0].length;
  				for (var y = 0; y < jml_path; y++)
  				{
  				  daftar_area[x].path[0][y].reverse()
  				}
  				if (daftar_area[x].foto)
  				{
  				  foto = '<img src="'+lokasi_gambar+'sedang_'+daftar_area[x].foto+'" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>';
  				}
  				else
  				foto = "";

          //Style polygon
    			var area_style = {
    			  stroke: true,
    			  opacity: 1,
    			  weight: 2,
            fillColor: daftar_area[x].color,
    			  fillOpacity: 0.5
    			}
  				content_area =
  				'<div id="content">'+
  				'<div id="siteNotice">'+
  				'</div>'+
  				'<h4 id="firstHeading" class="firstHeading">'+daftar_area[x].nama+'</h4>'+
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

    //GARIS
    <?php if ($layer_garis==1 AND !empty($garis)): ?>
    var daftar_garis = JSON.parse('<?=addslashes(json_encode($garis))?>');
    var jml = daftar_garis.length;
    var coords;
    var lengthOfCoords;
    var foto;
    var content_garis;
    var lokasi_gambar = "<?= base_url().LOKASI_FOTO_garis?>";

    for (var x = 0; x < jml;x++)
    {
      if (daftar_garis[x].path)
      {
        daftar_garis[x].path = JSON.parse(daftar_garis[x].path)
        coords = daftar_garis[x].path;
        lengthOfCoords = coords.length;

        for (i = 0; i < lengthOfCoords; i++) {
          holdLon = coords[i][0];
          coords[i][0] = coords[i][1];
          coords[i][1] = holdLon;
        }

        if (daftar_garis[x].foto)
        {
          foto = '<img src="'+lokasi_gambar+'sedang_'+daftar_garis[x].foto+'" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>';
        }
        else
        foto = "";
        //Style polyline
        var garis_style = {
          stroke: true,
          opacity: 1,
          weight: 3,
          color: daftar_garis[x].color
        }
        content_garis =
        '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h4 id="firstHeading" class="firstHeading">'+daftar_garis[x].nama+'</h4>'+
        '<div id="bodyContent">'+ foto +
        '</div>'+
        '</div>';

        semua_marker.push(turf.lineString(coords, {content: content_garis, style: garis_style}));
      }
    }
    <?php endif; ?>

		//PENDUDUK
		<?php if ($layer_penduduk==1 OR $layer_keluarga==1 AND !empty($penduduk)): ?>
			//Data penduduk
			var penduduk = JSON.parse('<?=addslashes(json_encode($penduduk))?>');
			var jml = penduduk.length;
			var foto;
			var content;
			var point_style = L.icon({
			  iconUrl: '<?= base_url()."assets/images/gis/point/pend.png"?>',
			  iconSize: [32, 37],
			  iconAnchor: [16, 37],
			  popupAnchor: [0, -28],
			});
			for (var x = 0; x < jml;x++)
			{
			  if (penduduk[x].lat || penduduk[x].lng)
			  {
					//Jika penduduk ada foto, maka pakai foto tersebut
					//Jika tidak, pakai foto default
					if (penduduk[x].foto)
					{
					  foto = '<td><img src="'+AmbilFoto(penduduk[x].foto)+'" class="foto_pend"/></td>';
					}
					else
					foto = '<td><img src="<?= base_url()?>assets/files/user_pict/kuser.png" class="foto_pend"/></td>';

					//Konten yang akan ditampilkan saat marker diklik
					content =
					'<table border=0><tr>' + foto +
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
		if (semua_marker.length != 0)
		{
		  //Menjalankan geojson menggunakan leaflet
		  var geojson = L.geoJSON(turf.featureCollection(semua_marker), {
			//Method yang dijalankan ketika marker diklik
			onEachFeature: function (feature, layer) {
			  //Menampilkan pesan berisi content pada saat diklik
			  layer.bindPopup(feature.properties.content);
			  layer.bindTooltip(feature.properties.content);
			},
			//Method untuk menambahkan style ke polygon dan line
			style: function(feature)
			{
			  if (feature.properties.style)
			  {
					return feature.properties.style;
			  }
			},
			//Method untuk menambahkan style ke point (titik marker)
			pointToLayer: function (feature, latlng)
			{
			  if (feature.properties.style)
			  {
					return L.marker(latlng, {icon: feature.properties.style});
			  }
			  else
				  return L.marker(latlng);
			}
		  }).addTo(mymap);

		  //Mempusatkan tampilan map agar semua marker terlihat
		  mymap.fitBounds(geojson.getBounds());
		}
  }; //EOF window.onload

})();
</script>
<style>
#map
{
  width:100%;
  height:86vh
}
.form-group a
{
  color: #FEFFFF;
}
.foto
{
  width:200px;
  height:140px;
  border-radius:3px;
  -moz-border-radius:3px;
  -webkit-border-radius:3px;
  border:2px solid #555555;
}
.icos
{
  padding-top: 9px;
}
.foto_pend
{
  width:70px;height:70px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;
}

</style>
<div class="content-wrapper">
  <form id="mainform" name="mainform" action="" method="post">
		<div class="row">
		  <div class="col-md-12">
				<div id="map">
				  <div class="leaflet-top leaflet-right">
					<div class="leaflet-control-layers leaflet-bar leaflet-control">
					  <a class="leaflet-control-control icos" href="#" title="Control Panel" role="button" aria-label="Control Panel" onclick="$('#target1').toggle();$('#target1').removeClass('hidden');$('#target2').hide();"><i class="fa fa-gears"></i></a>
					  <a class="leaflet-control-control icos" href="#" title="Legenda" role="button" aria-label="Legenda" onclick="$('#target2').toggle();$('#target2').removeClass('hidden');$('#target1').hide();"><i class="fa fa-list"></i></a>
					</div>
					<div id="target1" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control hidden" aria-haspopup="true" style="max-width: 250px;">
					  <div class="leaflet-control-layers-overlays">
							<div class="leaflet-control-layers-group" id="leaflet-control-layers-group-2">
							  <span class="leaflet-control-layers-group-name">CARI PENDUDUK</span>
							  <div class="leaflet-control-layers-separator"></div>
							  <div class="form-group">
									<label>Status Penduduk</label>
									<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url('gis/filter')?>')">
									  <option value="">Status</option>
									  <option value="1" <?php if ($filter==1): ?>selected<?php endif ?>>Aktif</option>
									  <option value="2" <?php if ($filter==2): ?>selected<?php endif ?>>Pasif</option>
									  <option value="2" <?php if ($filter==3): ?>selected<?php endif ?>>Pendatang</option>
									</select>
							  </div>
							  <div class="form-group">
									<label>Jenis Kelamin</label>
									<select class="form-control input-sm " name="sex" onchange="formAction('mainform','<?= site_url('gis/sex')?>')">
									  <option value="">Jenis Kelamin</option>
									  <option value="1" <?php if ($sex==1): ?>selected<?php endif ?>>Laki-laki</option>
									  <option value="2" <?php if ($sex==2): ?>selected<?php endif ?>>Perempuan</option>
									</select>
							  </div>
							  <div class="form-group">
									<label>Dusun</label>
									<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('gis/dusun')?>')">
									  <option value=""><?=ucwords($this->setting->sebutan_dusun)?></option>
									  <?php foreach ($list_dusun AS $data): ?>
											<option <?php if ($dusun==$data['dusun']): ?>selected<?php endif ?> value="<?=$data['dusun']?>"><?=$data['dusun']?></option>
									  <?php endforeach; ?>
									</select>
							  </div>
							  <?php if ($dusun): ?>
									<div class="form-group">
									  <label>RW</label>
									  <select class="form-control input-sm " name="rw" onchange="formAction('mainform','<?= site_url('gis/rw')?>')">
											<option value="">RW</option>
											<?php foreach ($list_rw AS $data): ?>
											  <option <?php if ($rw==$data['rw']): ?>selected<?php endif ?>><?=$data['rw']?></option>
											<?php endforeach; ?>
									  </select>
									</div>
									<?php if ($rw): ?>
									  <div class="form-group">
											<label>RT</label>
											<select class="form-control input-sm " name="rt" onchange="formAction('mainform','<?= site_url('gis/rt')?>')">
											  <option value="">RT</option>
											  <?php foreach ($list_rt AS $data): ?>
													<option <?php if ($rt==$data['rt']): ?>selected<?php endif ?>><?=$data['rt']?></option>
											  <?php endforeach; ?>
											</select>
									  </div>
									<?php endif; ?>
							  <?php endif; ?>
							  <div class="col-sm-12">
									<div class="form-group row">
									  <label>Cari</label>
									  <div class="box-tools">
											<div class="input-group input-group-sm pull-right">
											  <input name="cari" id="cari" class="form-control" placeholder="cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?=site_url("gis/search")?>');$('#'+'mainform').submit();endif">
											  <div class="input-group-btn">
													<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("gis/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
											  </div>
											</div>
									  </div>
									</div>
							  </div>
							  <div class="form-group">
									<a  href="<?=site_url("gis/ajax_adv_search")?>" class="btn btn-block btn-social bg-olive btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik" title="Pencarian Spesifik">
									  <i class="fa fa-search"></i> Pencarian Spesifik
									</a>
									<a href="<?=site_url("gis/clear")?>" class="btn btn-block btn-social bg-orange btn-sm">
									  <i class="fa fa-refresh"></i> Bersihkan
									</a>
							  </div>
							</div>
					  </div>
					</div>
					<div id="target2" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control hidden" aria-haspopup="true" style="max-height: 315px;">
					  <div class="leaflet-control-layers-overlays">
							<div class="leaflet-control-layers-group" id="leaflet-control-layers-group-3">
							  <span class="leaflet-control-layers-group-name">LEGENDA</span>
							  <div class="leaflet-control-layers-separator"></div>
							  <label>
									<input class="leaflet-control-layers-selector" type="checkbox" name="layer_penduduk" value="1" onchange="handle_pend(this);" <?php if ($layer_penduduk==1): ?>checked<?php endif; ?>>
									<span> Penduduk	</span>
							  </label>
							  <label>
									<input class="leaflet-control-layers-selector" type="checkbox" name="layer_keluarga" value="1" onchange="handle_kel(this);" <?php if ($layer_keluarga==1): ?>checked<?php endif; ?>>
									<span> Keluarga</span>
							  </label>
							  <label>
									<input type="checkbox" name="layer_desa" value="1"onchange="handle_desa(this);" <?php if ($layer_desa==1): ?>checked<?php endif; ?>>
									<span> <?= ucwords($this->setting->sebutan_desa)?></span>
							  </label>
							  <label>
									<input type="checkbox" name="layer_dusun" value="1"onchange="handle_dusun(this);" <?php if ($layer_dusun==1): ?>checked<?php endif; ?>>
									<span> <?= ucwords($this->setting->sebutan_dusun)?></span>
							  </label>
							  <label>
									<input type="checkbox" name="layer_rw" value="1"onchange="handle_rw(this);" <?php if ($layer_rw==1): ?>checked<?php endif; ?>>
									<span> RW</span>
							  </label>
							  <label>
									<input type="checkbox" name="layer_rt" value="1"onchange="handle_rt(this);" <?php if ($layer_rt==1): ?>checked<?php endif; ?>>
									<span> RT</span>
							  </label>
							  <label>
									<input type="checkbox" name="layer_area" value="1"onchange="handle_area(this);" <?php if ($layer_area==1): ?>checked<?php endif; ?>>
									<span> Area</span>
							  </label>
							  <label>
									<input type="checkbox" name="layer_lokasi" value="1"onchange="handle_lokasi(this);" <?php if ($layer_lokasi==1): ?>checked<?php endif; ?>>
									<span> Lokasi/Properti </span>
							  </label>
                <label>
									<input type="checkbox" name="layer_garis" value="1"onchange="handle_garis(this);" <?php if ($layer_garis==1): ?>checked<?php endif; ?>>
									<span> Garis </span>
							  </label>
							</div>
					  </div>
					</div>
				  </div>
				</div>
		  </div>
		</div>
  </form>
</div>
<script>
	function handle_pend(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_penduduk');
	}
	function handle_kel(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_keluarga');
	}
	function handle_desa(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_desa');
	}
	function handle_dusun(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_dusun');
	}
	function handle_rw(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_rw');
	}
	function handle_rt(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_rt');
	}
	function handle_area(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_area');
	}
	function handle_lokasi(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_lokasi');
	}
  function handle_garis(cb)
	{
	  formAction('mainform', '<?=site_url('gis')?>/layer_garis');
	}
	function AmbilFoto(foto, ukuran = "kecil_")
	{
	  ukuran_foto = ukuran || null
	  file_foto = '<?= base_url().LOKASI_USER_PICT;?>'+ukuran_foto+foto;
	  return file_foto;
	}
	function AmbilFotoLokasi(foto, ukuran = "kecil_")
	{
	  ukuran_foto = ukuran || null
	  file_foto = '<?= base_url().LOKASI_FOTO_LOKASI;?>'+ukuran_foto+foto;
	  return file_foto;
	}
</script>
