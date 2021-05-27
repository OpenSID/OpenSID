<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data Persil <?=ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('data_persil/clear')?>"> Daftar Persil</a></li>
			<li class="active">Pengelolaan Data Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-body">
						<div class="box-header with-border">
							<a href="<?= site_url('data_persil/clear')?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Persil"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Persil</a>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<form name='mainform' action="<?= site_url('data_persil/simpan_persil')?>" method="POST"  id="validasi" class="form-horizontal">
									<div class="box-body">
										<input type="hidden" name="id_persil" value="<?= $persil['id']?>">
										<div class="form-group">
											<label for="no_persil" class="col-sm-3 control-label">No. Persil</label>
											<div class="col-sm-8">
												<input name="no_persil" class="form-control input-sm angka required" type="text" placeholder="Nomor Surat Persil" name="nama" value="<?= $persil["nomor"] ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="no_persil" class="col-sm-3 control-label">No. Urut Bidang</label>
											<div class="col-sm-8">
												<input name="nomor_urut_bidang" class="form-control input-sm angka required" type="text" placeholder="Nomor urut untuk bidang tanah dengan nomor persil sama" value="<?= $persil["nomor_urut_bidang"] ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="kelas"  class="col-sm-3 control-label">Tipe Tanah</label>
											<div class="col-sm-4">
												<select class="form-control input-sm" id="tipe" name="tipe" type="text" placeholder="Tuliskan Kelas Tanah" >
													<option value>-- Pilih Tipe Tanah--</option>
													<option value="BASAH" <?php selected('BASAH', $persil_kelas[$persil['kelas']]["tipe"]) ?>>Tanah Basah</option>
													<option value="KERING" <?php selected('KERING', $persil_kelas[$persil['kelas']]["tipe"]) ?>>Tanah Kering</option>
													</select>
											</div>
										</div>
										<div class="form-group">
											<label for="kelas" class="col-sm-3 control-label">Kelas Tanah</label>
											<div class="col-sm-4">
												<select class="form-control input-sm required" id="kelas" name="kelas" type="text" placeholder="Tuliskan Kelas Tanah" >
													<option value="">-- Pilih Jenis Kelas--</option>
													<?php foreach ($persil_kelas  as $item): ?>
														<option value="<?= $item['id'] ?>" <?php selected($item['id'], $persil["kelas"]); ?>><?= $item['kode'].' '.$item['ndesc']?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="luas_persil" class="col-sm-3 control-label">Luas Persil Keseluruhan (M2)</label>
											<div class="col-sm-8">
												<input name="luas_persil" class="form-control input-sm angka required" type="text" placeholder="Luas persil secara keseluruhan (M2)" value="<?= $persil["luas_persil"] ?>">
											</div>
										</div>

										<div class="form-group">
											<label for="kelas" class="col-sm-3 control-label">Pemilik Awal</label>
											<div class="col-sm-4">
												<select class="form-control input-sm required" id="kelas" name="cdesa_awal" type="text" <?php $persil and print('disabled')?> placeholder="C-Desa pemilik awal persil ini" >
													<option value="">-- Pilih C-Desa Pemilik Awal --</option>
													<?php foreach ($list_cdesa  as $cdesa): ?>
														<option value="<?= $cdesa['id_cdesa'] ?>" <?php (($id_cdesa and $id_cdesa == $cdesa["id_cdesa"]) or ($cdesa['id_cdesa'] and $cdesa['id_cdesa'] == $persil["cdesa_awal"])) and print('selected'); ?>><?= $cdesa['nomor'].' - '.$cdesa['namapemilik']?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>

										<div class="form-group ">
											<label for="jenis_lokasi" class="col-sm-3 control-label">Lokasi Tanah</label>
											<div class="btn-group col-sm-8 kiri" data-toggle="buttons">
												<label  class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label <?= $persil["lokasi"] ? NULL : 'active' ?>">
													<input type="radio" name="jenis_lokasi" class="form-check-input" value="1" autocomplete="off" onchange="pilih_lokasi(this.value);"> Pilih Lokasi
												</label>
												<label  class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label <?= $persil["lokasi"] ? 'active' : NULL ?>">
													<input type="radio" name="jenis_lokasi" class="form-check-input" value="2" autocomplete="off" onchange="pilih_lokasi(this.value);"> Tulis Manual
												</label>
											</div>
										</div>

									
										<div class="form-group">
											<label class="col-sm-3 control-label"></label>
											<div id="pilih">
												<div class="col-sm-4" >
													<select class="form-control input-sm select2 required" id="id_wilayah" name="id_wilayah" style="width:100%">
														<option value='' >-- Pilih Lokasi Tanah--</option>
														<?php foreach ($persil_lokasi as $key=>$item): ?>
															<option value="<?= $item["id"] ?>" <?php selected($item["id"], $persil["id_wilayah"]) ?>><?= strtoupper($item["dusun"]) ?> <?= empty($item['rw']) ? "" : " - RW  {$item["rw"]}" ?> <?= empty($item['rt']) ? "" : " / RT  {$item["rt"]}" ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div id="manual">
												<div class="col-sm-8">
													<textarea id="lokasi" class="form-control input-sm required" type="text" placeholder="Lokasi" name="lokasi" ><?= $persil["lokasi"] ?></textarea>
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="area_tanah" class="col-sm-3 control-label">Peta</label>
											<div class="btn-group col-sm-8 kiri" data-toggle="buttons">
												<label  class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label active">
													<input type="radio" name="area_tanah" class="form-check-input" value="1" autocomplete="off"> Pilih Area
												</label>
												<label  class="btn btn-info btn-flat btn-sm col-sm-3 form-check-label">
													<input type="radio" name="area_tanah" class="form-check-input" value="2" autocomplete="off"> Buat Area
												</label>
											</div>
										</div>

										<div class="form-group" id="pilih-area">
											<label class="col-sm-3 control-label"></label>
											<div class="col-sm-4" >
												<select class="form-control input-sm select2" id="id_peta" name="id_peta" style="width:100%">
													<option value='' >-- Pilih Area--</option>
													<?php foreach ($peta as $key => $item): ?>
														<option value="<?= $item["id"] ?>" <?php selected($item["id"], $persil["id_peta"]) ?>><?= $item["nama"] ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>

										<div class="row">
											<div class="col-sm-12" >
												<input type="hidden" id="path" name="path" value="<?= $persil["path"] ?>">
												<input type="hidden" id="zoom" name="zoom" value="">
												<div id="map"></div>
											</div>
										</div>

										
									</div>
									<div class="box-footer">
										<div class="col-xs-12">
											<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
											<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<style type="text/css">
	#map
  {
	width:100%;
	height:50vh
  }
</style>
<script>
	function pilih_lokasi(pilih)
	{
		if (pilih == 1)
		{
			$('#lokasi').val('');
			$('#lokasi').removeClass('required');
			$("#manual").hide();
			$("#pilih").show();
			$('#id_wilayah').addClass('required');
		}
		else
		{
			$('#id_wilayah').val('');
			$('#id_wilayah').trigger('change', true);
			$('#id_wilayah').removeClass('required');
			$("#manual").show();
			$('#lokasi').addClass('required');
			$("#pilih").hide();
		}
	}


	$(document).ready(function(){
		// tampilkan map
		<?php if (!empty($desa['lat']) && !empty($desa['lng'])): ?>
			var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
			var zoom = <?=$desa['zoom'] ?: 18?>;
		<?php else: ?>
			var posisi = [-1.0546279422758742,116.71875000000001];
			var zoom = 4;
		<?php endif; ?>
		var peta_area = L.map('map').setView(posisi, zoom);

		//Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_area, '');


		if ($('input[name="path"]').val() !== '' )
		{
			var wilayah = JSON.parse($('input[name="path"]').val());
			showCurrentArea(wilayah, peta_area);
		}

		//Import Peta dari file SHP
		//eximShp(peta_area);

		//Geolocation IP Route/GPS
		geoLocation(peta_area);

		//Menambahkan Peta wilayah
		addPetaPoly(peta_area);

		// end tampilkan map

		if ($('select[name="id_peta"]').val() == '') 
		{
			$('input[name="area_tanah"][value="2"]').prop("checked",true).trigger('click').trigger('change')
			$('#pilih-area').hide();
			$('#pilih-area').val(null)
			peta_area.pm.addControls(editToolbarPoly());
		}

		$('#tipe').change(function(){
			var id=$(this).val();
			$.ajax({
				url : "<?=site_url('data_persil/kelasid')?>",
				method : "POST",
				data : {id: id},
				async : true,
				dataType : 'json',
				success: function(data){
					var html = '';
					var i;
					for(i=0; i<data.length; i++){
						html += '<option value='+data[i].id+'>'+data[i].kode+' '+data[i].ndesc+'</option>';
					}
					$('#kelas').html(html);
				}
			});
			return false;
		});
		pilih_lokasi(<?= empty($persil['lokasi']) ? 1 : 2?>);

		$('input[name="area_tanah"]').change(function() {
			var pilih = $(this).val();
			if (pilih == 1) 
			{
				$('#pilih-area').show();
				// tambahkan toolbar edit polyline
				peta_area.pm.removeControls(editToolbarPoly());
			}
			else
			{
				peta_area.pm.addControls(editToolbarPoly());
				$('#pilih-area').hide();
				$('#pilih-area').val(null)
				
			}			
		});

		$('select[name="id_peta"]').change(function() {
			var id_peta = $(this).val();
			$.ajax({
				url: '<?=site_url('data_persil/area_map')?>',
				type: 'GET',
				data: {id: id_peta},
			})
			.done(function(result) {
				if (result.status == true)
				{
					var wilayah = JSON.parse(result.data.path);
					clearMap(peta_area);
					showCurrentArea(wilayah, peta_area);
				}
			});
		});
	});

</script>

