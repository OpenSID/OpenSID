<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script language="javascript" type="text/javascript">
	function ubah_saksi1(asal)
	{
		if (asal == 1)
		{
			$('.saksi1_desa').show();
			$('.saksi1_luar_desa').hide();
			$('input[name=anchor').val('a_saksi1');
		}
		else
		{
			$('.saksi1_desa').hide();
			$('.saksi1_luar_desa').show();
			$('#id_saksi1').val('*'); // Hapus $id_saksi1
			submit_form_ambil_data('a_saksi1');
		}
	}

	function ubah_saksi2(asal)
	{
		if (asal == 1)
		{
			$('.saksi2_desa').show();
			$('.saksi2_luar_desa').hide();
			$('input[name=anchor').val('a_saksi2');
		}
		else
		{
			$('.saksi2_desa').hide();
			$('.saksi2_luar_desa').show();
			$('#id_saksi2').val('*'); // Hapus $id_saksi2
			submit_form_ambil_data('a_saksi2');
		}
	}

	function ubah_pelapor(asal){
		if (asal == 1)
		{
			$('.pelapor_desa').show();
			$('.pelapor_luar_desa').hide();
			$('input[name=anchor').val('a_pelapor');
		}
		else
		{
			$('.pelapor_desa').hide();
			$('.pelapor_luar_desa').show();
			$('#id_pelapor').val('*'); // Hapus $id_pelapor
			submit_form_ambil_data('a_pelapor');
		}
	}

 	function submit_form_ambil_data(jenis)
	{
		$('input[name=anchor').val(jenis);
		$('input').removeClass('required');
		$('select').removeClass('required');
		$('#'+'validasi').attr('action','');
		$('#'+'validasi').attr('target','');
		$('#'+'validasi').submit();
	}

	$('document').ready(function()
	{
		/* set otomatis hari */
		$('input[name=tanggal_mati]').change(function(){
			var hari = {
				0 : 'Minggu', 1 : 'Senin', 2 : 'Selasa', 3 : 'Rabu', 4 : 'Kamis', 5 : 'Jumat', 6 : 'Sabtu'
			};
			var t = $(this).datepicker('getDate');
			var i = t.getDay();
			$(this).closest('.form-group').find('[name=hari]').val(hari[i]);
		});
		/* pergi ke bagian halaman sesudah mengisi warga desa */
		setTimeout(function() {location.hash = "#" + $('input[name=anchor]').val();}, 500);
	});
</script>
<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						 </a>
						 <a href="#" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Lihat Info Isian Surat"  data-toggle="modal" data-target="#infoBox" data-title="Lihat Info Isian Surat">
						 	<i class="fa fa-info-circle"></i> Info Isian Surat
						</a>
					</div>
					<div class="box-body">
						<form id="main" name="main" method="POST" class="form-horizontal">
							<div class="col-md-12">
								<div class="form-group">
									<label for="nik"  class="col-sm-3 control-label">NIK / Nama Yang Meninggal</label>
									<div class="col-sm-6 col-lg-4">
										<select class="form-control  input-sm select2-nik" id="nik" name="nik" style ="width:100%;" onchange="formAction('main')">
											<option value="">--  Cari NIK / Nama Penduduk Berstatus Dasar 'MATI' --</option>
											<?php foreach ($mati as $data): ?>
												<option value="<?= $data['id']?>" <?php selected($individu['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
						</form>
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<div class="col-md-12">
								<div class="row jar_form">
									<label for="nomor" class="col-sm-3"></label>
									<div class="col-sm-8">
										<input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
									</div>
								</div>
								<input name="anchor" type="hidden" value="<?= $anchor; ?>"/>
								<?php if ($individu): ?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif; ?>
								<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
								<div class="form-group">
									<label for="ttl"  class="col-sm-3 control-label">Hari / Tanggal / Jam Kematian</label>
									<div class="col-sm-3 col-lg-4">
										<input  id="hari" readonly="readonly" class="form-control input-sm" type="text" placeholder="Hari Kematian" name="hari" value="<?= $_SESSION['post']['hari']?>">
									</div>
									<div class="col-sm-3 col-lg-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm required datepicker" name="tanggal_mati" type="text" value="<?= $_SESSION['post']['tanggal_mati']?>"/>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
											<input class="form-control input-sm required" name="jam" id="jammenit_1" type="text" value="<?= $_SESSION['post']['jam']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="tempat_mati"  class="col-sm-3 control-label">Tempat Meninggal</label>
									<div class="col-sm-8">
										<input type="text" name="tempat_mati" class="form-control input-sm required" placeholder="Tempat Meninggal" value="<?= $_SESSION['post']['tempat_mati']?>"></input>
									</div>
								</div>
								<div class="form-group">
									<label for="sebab" class="col-sm-3 control-label" >Penyebab Kematian</label>
									<div class="col-sm-4">
										<input name="sebab_nama" type="hidden" value="<?= $sebab[$_SESSION['post']['sebab']] ?>">
										<select class="form-control input-sm required" name="sebab" onchange="$('input[name=sebab_nama]').val($(this).find(':selected').data('sebabnama'));">
											<option value="">Pilih Sebab</option>
											<?php foreach ($sebab as $id => $nama): ?>
												<option value="<?= $id?>" data-sebabnama="<?= $nama; ?>" <?php if ($id==$_SESSION['post']['sebab']): ?>selected<?php endif; ?>><?= $nama; ?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="sebab" class="col-sm-3 control-label"> Yang Menerangkan</label>
									<div class="col-sm-4">
										<select class="form-control input-sm required" name="penolong_mati">
											<option value="">Pilih Penolong mati</option>
											<?php foreach ($penolong_mati as $id => $nama): ?>
												<option value="<?= $id?>" <?php if ($id==$_SESSION['post']['penolong_mati']): ?>selected<?php endif; ?>><?= $nama; ?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="anakke"  class="col-sm-3 control-label">Anak Ke</label>
									<div class="col-sm-2">
										<input type="text" name="anakke" class="form-control input-sm data_lahir required" placeholder="Anak Ke" value="<?= $_SESSION['post']['anakke']?>"></input>
									</div>
								</div>
								<!-- AYAH -->
								<?php if ($ayah): ?>
									<div class="form-group ibu_desa">
										<label class="col-sm-3 control-label text-red" ><strong>DATA AYAH DARI DATABASE</strong></label>
									</div>
									<div class="form-group ibu_desa">
										<label class="col-sm-3 control-label">NIK</label>
										<div class="col-sm-8">
											<input type="text" class="form-control input-sm" value="<?= $ayah['nik']?>" disabled></input>
										</div>
									</div>
									<div class="form-group ibu_desa">
										<label class="col-sm-3 control-label">Nama</label>
										<div class="col-sm-8">
											<input type="text" class="form-control input-sm" value="<?= $ayah['nama']?>" disabled></input>
										</div>
									</div>
									<?php //bagian info setelah terpilih
										$individu = $ayah;
										include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
									?>
								<?php endif; ?>
								<?php if ($jenazah and empty($ayah)): ?>
									<div class="form-group ibu_luar_desa" >
										<label for="ayah_desa"  class="col-sm-3 control-label text-red" ><strong>DATA AYAH TIDAK TERDATA</strong></label>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="nama_ayah"  class="col-sm-3 control-label">Nama Ayah</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="Nama Ayah" name="nama_ayah" value="<?= $_SESSION['post']['nama_ayah']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="nik_ayah"  class="col-sm-3 control-label">NIK Ayah</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm" type="text" placeholder="NIK Ayah" name="nik_ayah" value="<?= $_SESSION['post']['nik_ayah']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="tempat_lahir_ayah"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir / Umur</label>
										<div class="col-sm-3 col-lg-4">
											<input class="form-control input-sm" type="text" name="tempat_lahir_ayah" id="tempat_lahir_ayah" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempat_lahir_ayah']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggal_lahir_ayah" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tempat_lahir_ayah']?>" onchange="$('input[name=umur_ayah]').val(_calculateAge($(this).val()));"/>
											</div>
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm" name="umur_ayah" readonly="readonly" placeholder="Umur (Tahun)"  type="text" value="<?= $_SESSION['post']['umur_ayah']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="pekerjaanayah" class="col-sm-3 control-label" ><strong>Pekerjaan</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm select2" name="pekerjaanayah" id="pekerjaanayah" style ="width:100%;"  onchange="$('input[name=pekerjaanid_ayah').val($(this).find(':selected').data('pekerjaanid'));">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" data-pekerjaanid="<?= $data['id']?>" <?php if ($data['nama']==$_SESSION['post']['pekerjaanayah']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="alamat_ayah"  class="col-sm-3 control-label">Alamat / RT / RW</label>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="alamat_ayah" id="alamat_ayah" placeholder="Alamat" value="<?= $_SESSION['post']['alamat_ayah']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm" type="text" name="rt_ayah" id="rt_ayah" placeholder="RT" value="<?= $_SESSION['post']['rt_ayah']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm" name="rw_ayah" id="rw_ayah"  type="text" placeholder="RW" value="<?= $_SESSION['post']['rw_ayah']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="alamat_ayah"  class="col-sm-3 control-label">Desa / Kecamatan</label>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="desaayah" id="desaayah" placeholder="Desa" value="<?= $_SESSION['post']['desaayah']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="kecayah" id="kecayah" placeholder="Kecamatan" value="<?= $_SESSION['post']['kecayah']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="alamat_ayah"  class="col-sm-3 control-label">Kabupaten / Provinsi</label>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="kabayah" id="kabayah" placeholder="Kabupaten" value="<?= $_SESSION['post']['kabayah']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="provinsiayah" id="provinsiayah" placeholder="Provinsi" value="<?= $_SESSION['post']['provinsiayah']?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ($ibu): ?>
									<div class="form-group ibu_desa">
										<label class="col-sm-3 control-label text-red" ><strong>DATA IBU DARI DATABASE</strong></label>
									</div>
									<div class="form-group ibu_desa">
										<label class="col-sm-3 control-label">NIK</label>
										<div class="col-sm-8">
											<input type="text" class="form-control input-sm" value="<?= $ibu['nik']?>" disabled></input>
										</div>
									</div>
									<div class="form-group ibu_desa">
										<label class="col-sm-3 control-label">Nama</label>
										<div class="col-sm-8">
											<input type="text" class="form-control input-sm" value="<?= $ibu['nama']?>" disabled></input>
										</div>
									</div>
									<?php //bagian info setelah terpilih
										$individu = $ibu;
										include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
									?>
								<?php endif; ?>
								<?php if ($jenazah and empty($ibu)): ?>
									<div class="form-group ibu_luar_desa" >
										<label class="col-sm-3 control-label text-red" ><strong>DATA IBU TIDAK TERDATA</strong></label>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="nama_ibu"  class="col-sm-3 control-label">Nama Ibu</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="Nama Ibu" name="nama_ibu" value="<?= $_SESSION['post']['nama_ibu']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="nik_ibu"  class="col-sm-3 control-label">NIK Ibu</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm" type="text" placeholder="NIK Ibu" name="nik_ibu" value="<?= $_SESSION['post']['nik_ibu']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="tempat_lahir_ibu"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir / Umur</label>
										<div class="col-sm-3 col-lg-4">
											<input class="form-control input-sm" type="text" name="tempat_lahir_ibu" id="tempat_lahir_ibu" placeholder="Tempat Lahir Ibu" value="<?= $_SESSION['post']['tempat_lahir_ibu']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggal_lahir_ibu" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggal_lahir_ibu']?>" onchange="$('input[name=umur_ibu]').val(_calculateAge($(this).val()));"/>
											</div>
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm" name="umur_ibu" readonly="readonly" placeholder="Umur (Tahun)" type="text" value="<?= $_SESSION['post']['umur_ibu']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="pekerjaanibu" class="col-sm-3 control-label" ><strong>Pekerjaan</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm" name="pekerjaanibu" id="pekerjaanibu" onchange="$('input[name=pekerjaanid_ibu]').val($(this).find(':selected').data('pekerjaanid'));">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" data-pekerjaanid="<?= $data['id']?>" <?php if ($data['nama']==$_SESSION['post']['pekerjaanibu']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="ttl"  class="col-sm-3 control-label">Tanggal Perkawinan</label>
										<div class="col-sm-3">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggalperkawinan_ibu" type="text" value="<?= $_SESSION['post']['tanggalperkawinan_ibu']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="alamat_ibu"  class="col-sm-3 control-label">Alamat / RT / RW</label>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="alamat_ibu" id="alamat_ibu" placeholder="Alamat" value="<?= $_SESSION['post']['alamat_ibu']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm" type="text" name="rt_ibu" id="rt_ibu" placeholder="RT" value="<?= $_SESSION['post']['rt_ibu']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm" name="rw_ibu" id="rw_ibu"  type="text" placeholder="RW" value="<?= $_SESSION['post']['rw_ibu']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="alamat_ibu"  class="col-sm-3 control-label">Desa / Kecamatan</label>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="desaibu" id="desaibu" placeholder="Desa" value="<?= $_SESSION['post']['desaibu']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="kecibu" id="kecibu" placeholder="Kecamatan" value="<?= $_SESSION['post']['kecibu']?>">
										</div>
									</div>
									<div class="form-group ibu_luar_desa">
										<label for="alamat_ibu"  class="col-sm-3 control-label">Kabupaten / Provinsi</label>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="kabibu" id="kabibu" placeholder="Kabupaten" value="<?= $_SESSION['post']['kabibu']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm" type="text" name="provinsiibu" id="provinsiibu" placeholder="Provinsi" value="<?= $_SESSION['post']['provinsiibu']?>">
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group subtitle_head" id="a_pelapor">
									<label class="col-sm-3 control-label" >PELAPOR</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($pelapor)): ?>active<?php endif ?>">
											<input id="pelapor_1" type="radio" name="pelapor" class="form-check-input" type="radio" value="1" <?php if (!empty($pelapor)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_pelapor(this.value);"> Warga Desa
										</label>
										<label id="label_pelapor_2" class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($pelapor)): ?>active<?php endif; ?>">
											<input id="pelapor_2" type="radio" name="pelapor" class="form-check-input" type="radio" value="2" <?php if (empty($pelapor)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_pelapor(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group pelapor_desa" <?php if (empty($pelapor)): ?>style="display: none;"<?php endif; ?>>
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA PELAPOR WARGA DESA</strong></label>
								</div>
								<div class="form-group pelapor_desa" <?php if (empty($pelapor)): ?>style="display: none;"<?php endif; ?>>
									<label for="id_pelapor" class="col-sm-3 control-label" ><strong>NIK / Nama</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2-nik-ajax" id="id_pelapor" name="id_pelapor" style="width:100%;" data-url="<?= site_url('surat/list_penduduk_ajax')?>" onchange="submit_form_ambil_data('a_pelapor');">
											<?php if ($pelapor): ?>
												<option value="<?= $pelapor['id']?>" selected><?= $pelapor['nik'].' - '.$pelapor['nama']?></option>
											<?php endif;?>
										</select>
									</div>
								</div>
								<?php if ($pelapor): ?>
									<?php $individu = $pelapor;?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif; ?>
								<?php if (empty($pelapor)): ?>
									<div class="form-group pelapor_luar_desa" >
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA PELAPOR LUAR DESA</strong></label>
									</div>
									<div class="form-group pelapor_luar_desa">
										<label for="nama_pelapor" class="col-sm-3 control-label">Nama Pelapor</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="Nama Pelapor" name="nama_pelapor" value="<?= $_SESSION['post']['nama_pelapor']?>">
										</div>
									</div>
									<div class="form-group pelapor_luar_desa">
										<label for="nik_pelapor"  class="col-sm-3 control-label">NIK Pelapor</label>
										<div class="col-sm-8">
											<input class="form-control input-sm required" type="text" placeholder="NIK Pelapor" name="nik_pelapor" value="<?= $_SESSION['post']['nik_pelapor']?>">
										</div>
									</div>
									<div class="form-group pelapor_luar_desa">
										<label for="tempat_lahir_pelapor"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir / Umur</label>
										<div class="col-sm-3 col-lg-4">
											<input class="form-control input-sm" type="text" name="tempat_lahir_pelapor" id="tempat_lahir_pelapor" placeholder="Tempat Lahir Pelapor" value="<?= $_SESSION['post']['tempat_lahir_pelapor']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm required datepicker" name="tanggal_lahir_pelapor" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggal_lahir_pelapor']?>" onchange="$('input[name=umur_pelapor]').val(_calculateAge($(this).val()));"/>
											</div>
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm" name="umur_pelapor" readonly="readonly" placeholder="Umur (Tahun)" type="text" value="<?= $_SESSION['post']['umur_pelapor']?>">
										</div>
									</div>
									<div class="form-group pelapor_luar_desa">
										<label for="pekerjaanpelapor" class="col-sm-3 control-label" ><strong>Jenis Kelamin / Pekerjaan</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm required" name="jkpelapor" id="jkpelapor">
												<option value="">-- Jenis Kelamin --</option>
												<?php foreach ($sex as $data): ?>
													<option value="<?= $data['id']?>" <?php if($data['id']==$_SESSION['post']['jkpelapor']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-4">
											<input type="hidden" name="pekerjaanid_pelapor">
											<select class="form-control input-sm required" name="pekerjaanpelapor" id="pekerjaanpelapor" onchange="$('input[name=pekerjaanid_pelapor]').val($(this).find(':selected').data('pekerjaanid'));">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" data-pekerjaanid="<?= $data['id']?>" <?php if ($data['nama']==$_SESSION['post']['pekerjaanpelapor']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group pelapor_luar_desa">
										<label for="alamat_pelapor"  class="col-sm-3 control-label">Alamat / RT / RW</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="alamat_pelapor" id="alamat_pelapor" placeholder="Alamat" value="<?= $_SESSION['post']['alamat_pelapor']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" type="text" name="rt_pelapor" id="rt_pelapor" placeholder="RT" value="<?= $_SESSION['post']['rt_pelapor']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" name="rw_pelapor" id="rw_pelapor"  type="text" placeholder="RW" value="<?= $_SESSION['post']['rw_pelapor']?>">
										</div>
									</div>
									<div class="form-group pelapor_luar_desa">
										<label for="alamat_pelapor"  class="col-sm-3 control-label">Desa / Kecamatan</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="desapelapor" id="desapelapor" placeholder="Desa" value="<?= $_SESSION['post']['desapelapor']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kecpelapor" id="kecpelapor" placeholder="Kecamatan" value="<?= $_SESSION['post']['kecpelapor']?>">
										</div>
									</div>
									<div class="form-group pelapor_luar_desa">
										<label for="alamat_pelapor"  class="col-sm-3 control-label">Kabupaten / Provinsi</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kabpelapor" id="kabpelapor" placeholder="Kabupaten" value="<?= $_SESSION['post']['kabpelapor']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="provinsipelapor" id="provinsipelapor" placeholder="Provinsi" value="<?= $_SESSION['post']['provinsipelapor']?>">
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group">
									<label for="hubungan_pelapor" class="col-sm-3 control-label">Hubungan pelapor dengan yang mati</label>
									<div class="col-sm-8">
										<input class="form-control input-sm required" type="text" placeholder="Hubungan pelapor dengan yang mati" name="hubungan_pelapor" value="<?= $_SESSION['post']['hubungan_pelapor']?>">
									</div>
								</div>
								<div class="form-group subtitle_head" id="a_saksi1">
									<label class="col-sm-3 control-label" for="status">SAKSI 1</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($saksi1)): ?>active<?php endif ?>">
											<input id="saksi1_1" type="radio" name="saksi1" class="form-check-input" type="radio" value="1" <?php if (!empty($saksi1)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_saksi1(this.value);"> Warga Desa
										</label>
										<label id="label_saksi1_2"  class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($saksi1)): ?>active<?php endif; ?>">
											<input id="saksi1_2" type="radio" name="saksi1" class="form-check-input" type="radio" value="2" <?php if (empty($saksi1)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_saksi1(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group saksi1_desa" <?php if (empty($saksi1)): ?>style="display: none;"<?php endif; ?>>
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 1 DARI DATABASE</strong></label>
								</div>
								<div class="form-group saksi1_desa" <?php if (empty($saksi1)): ?>style="display: none;"<?php endif; ?>>
									<label for="saksi1_desa" class="col-sm-3 control-label" ><strong>NIK / Nama</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2-nik-ajax" id="id_saksi1" name="id_saksi1" style="width:100%;" data-url="<?= site_url('surat/list_penduduk_ajax')?>" onchange="submit_form_ambil_data('a_saksi1');">
											<?php if ($saksi1): ?>
												<option value="<?= $saksi1['id']?>" selected><?= $saksi1['nik'].' - '.$saksi1['nama']?></option>
											<?php endif;?>
										</select>
									</div>
								</div>
								<?php if ($saksi1): ?>
									<?php $individu = $saksi1;?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif; ?>
								<?php if (empty($saksi1)): ?>
									<div class="form-group saksi1_luar_desa" >
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 1 LUAR DESA</strong></label>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="nama_saksi1" class="col-sm-3 control-label">Nama Saksi</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="Nama Saksi" name="nama_saksi1" value="<?= $_SESSION['post']['nama_saksi1']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="nik_saksi1"  class="col-sm-3 control-label">NIK Saksi</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="NIK Saksi" name="nik_saksi1" value="<?= $_SESSION['post']['nik_saksi1']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="tempat_lahir_saksi1"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir / Umur</label>
										<div class="col-sm-3 col-lg-4">
											<input class="form-control input-sm required" type="text" name="tempat_lahir_saksi1" id="tempat_lahir_saksi1" placeholder="Tempat Lahir Saksi" value="<?= $_SESSION['post']['tempat_lahir_saksi1']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm required datepicker" name="tanggal_lahir_saksi1" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggal_lahir_saksi1']?>" onchange="$('input[name=umur_saksi1]').val(_calculateAge($(this).val()));"/>
											</div>
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" name="umur_saksi1" readonly="readonly" placeholder="Umur (Tahun)" type="text" value="<?= $_SESSION['post']['umur_saksi1']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="pekerjaansaksi1" class="col-sm-3 control-label" ><strong>Jenis Kelamin / Pekerjaan</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm required" name="jksaksi1" id="jksaksi1">
												<option value="">-- Jenis Kelamin --</option>
												<?php foreach ($sex as $data): ?>
													<option value="<?= $data['id']?>" <?php if($data['id']==$_SESSION['post']['jksaksi1']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-4">
											<input type="hidden" name="pekerjaanid_saksi1">
											<select class="form-control input-sm required" name="pekerjaansaksi1" id="pekerjaansaksi1" onchange="$('input[name=pekerjaanid_saksi1]').val($(this).find(':selected').data('pekerjaanid'));">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" data-pekerjaanid="<?= $data['id']?>" <?php if ($data['nama']==$_SESSION['post']['pekerjaansaksi1']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="alamat_saksi1"  class="col-sm-3 control-label">Alamat / RT / RW</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="alamat_saksi1" id="alamat_saksi1" placeholder="Alamat" value="<?= $_SESSION['post']['alamat_saksi1']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" type="text" name="rt_saksi1" id="rt_saksi1" placeholder="RT" value="<?= $_SESSION['post']['rt_saksi1']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" name="rw_saksi1" id="rw_saksi1"  type="text" placeholder="RW" value="<?= $_SESSION['post']['rw_saksi1']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="alamat_saksi1"  class="col-sm-3 control-label">Desa / Kecamatan</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="desasaksi1" id="desasaksi1" placeholder="Desa" value="<?= $_SESSION['post']['desasaksi1']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kecsaksi1" id="kecsaksi1" placeholder="Kecamatan" value="<?= $_SESSION['post']['kecsaksi1']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="alamat_saksi1"  class="col-sm-3 control-label">Kabupaten / Provinsi</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kabsaksi1" id="kabsaksi1" placeholder="Kabupaten" value="<?= $_SESSION['post']['kabsaksi1']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="provinsisaksi1" id="provinsisaksi1" placeholder="Provinsi" value="<?= $_SESSION['post']['provinsisaksi1']?>">
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group subtitle_head" id="a_saksi2">
									<label class="col-sm-3 control-label" for="status">SAKSI 2</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($saksi2)): ?>active<?php endif ?>">
											<input id="saksi2_1" type="radio" name="saksi2" class="form-check-input" type="radio" value="1" <?php if (!empty($saksi2)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Desa
										</label>
										<label id="label_saksi2_2"  class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($saksi2)): ?>active<?php endif; ?>">
											<input id="saksi2_2" type="radio" name="saksi2" class="form-check-input" type="radio" value="2" <?php if (empty($saksi2)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group saksi2_desa" <?php if (empty($saksi2)): ?>style="display: none;"<?php endif; ?>>
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 2 DARI DATABASE</strong></label>
								</div>
								<div class="form-group saksi2_desa" <?php if (empty($saksi2)): ?>style="display: none;"<?php endif; ?>>
									<label for="saksi2_desa" class="col-sm-3 control-label" ><strong>NIK / Nama</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2-nik-ajax" id="id_saksi2" name="id_saksi2" style="width:100%;" data-url="<?= site_url('surat/list_penduduk_ajax')?>" onchange="submit_form_ambil_data('a_saksi2');">
											<?php if ($saksi2): ?>
												<option value="<?= $saksi2['id']?>" selected><?= $saksi2['nik'].' - '.$saksi2['nama']?></option>
											<?php endif;?>
										</select>
									</div>
								</div>
								<?php if ($saksi2): ?>
									<?php $individu = $saksi2;?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif; ?>
								<?php if (empty($saksi2)): ?>
									<div class="form-group saksi2_luar_desa" >
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 2 LUAR DESA</strong></label>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="nama_saksi2"  class="col-sm-3 control-label">Nama Saksi</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="Nama Saksi" name="nama_saksi2" value="<?= $_SESSION['post']['nama_saksi2']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="nik_saksi2"  class="col-sm-3 control-label">NIK Saksi</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="NIK Saksi" name="nik_saksi2" value="<?= $_SESSION['post']['nik_saksi2']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="tempat_lahir_saksi2"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir / Umur</label>
										<div class="col-sm-3 col-lg-4">
											<input class="form-control input-sm required" type="text" name="tempat_lahir_saksi2" id="tempat_lahir_saksi2" placeholder="Tempat Lahir Saksi" value="<?= $_SESSION['post']['tempat_lahir_saksi2']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm required datepicker" name="tanggal_lahir_saksi2" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggal_lahir_saksi2']?>" onchange="$('input[name=umur_saksi2]').val(_calculateAge($(this).val()));"/>
											</div>
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" name="umur_saksi2" readonly="readonly" placeholder="Umur (Tahun)" type="text" value="<?= $_SESSION['post']['umur_saksi2']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="pekerjaansaksi2" class="col-sm-3 control-label" ><strong>Jenis Kelamin / Pekerjaan</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm required" name="jksaksi2" id="jksaksi2">
												<option value="">-- Jenis Kelamin --</option>
												<?php foreach ($sex as $data): ?>
													<option value="<?= $data['id']?>" <?php if($data['id']==$_SESSION['post']['jksaksi2']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-4">
											<input type="hidden" name="pekerjaanid_saksi2">
											<select class="form-control input-sm required" name="pekerjaansaksi2" id="pekerjaansaksi2" onchange="$('input[name=pekerjaanid_saksi2]').val($(this).find(':selected').data('pekerjaanid'));">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" data-pekerjaanid="<?= $data['id']?>" <?php if ($data['nama']==$_SESSION['post']['pekerjaansaksi2']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="alamat_saksi2"  class="col-sm-3 control-label">Alamat / RT / RW</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="alamat_saksi2" id="alamat_saksi2" placeholder="Alamat" value="<?= $_SESSION['post']['alamat_saksi2']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" type="text" name="rt_saksi2" id="rt_saksi2" placeholder="RT" value="<?= $_SESSION['post']['rt_saksi2']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" name="rw_saksi2" id="rw_saksi2"  type="text" placeholder="RW" value="<?= $_SESSION['post']['rw_saksi2']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="alamat_saksi2"  class="col-sm-3 control-label">Desa / Kecamatan</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="desasaksi2" id="desasaksi2" placeholder="Desa" value="<?= $_SESSION['post']['desasaksi2']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kecsaksi2" id="kecsaksi2" placeholder="Kecamatan" value="<?= $_SESSION['post']['kecsaksi2']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="alamat_saksi2"  class="col-sm-3 control-label">Kabupaten / Provinsi</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kabsaksi2" id="kabsaksi2" placeholder="Kabupaten" value="<?= $_SESSION['post']['kabsaksi2']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="provinsisaksi2" id="provinsisaksi2" placeholder="Provinsi" value="<?= $_SESSION['post']['provinsisaksi2']?>">
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group">
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>PENANDA TANGAN</strong></label>
								</div>
								<div class="form-group">
										<label class="col-sm-3 control-label">Lokasi Disdukcapil <?= ucwords($this->setting->sebutan_kabupaten)?></label>
										<div class="col-sm-8">
											<input class="form-control input-sm required" type="text" name="lokasi_disdukcapil" id="lokasi_disdukcapil" placeholder="Lokasi Disdukcapil" value="<?= $_SESSION['post']['lokasi_disdukcapil']?>">
										</div>
									</div>
								<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
							</div>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
				<div class='modal fade' id='infoBox' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header btn-default'>
								<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
								<h4 class='modal-title' id='myModalLabel'><i class='fa fa-info-circle'></i>&nbsp;&nbsp;Info Isian Surat</h4>
							</div>
							<div class='modal-body small'>
								<h5><strong>Form ini menghasilkan:</strong></h5>
								<ol>
									<li>Surat Keterangan Kematian</li>
									<li>Lampiran F-2.29 SURAT KETERANGAN KEMATIAN bagi warga yang meninggal</li>
								</ol>
								<p>Sebelum membuat Surat Keterangan Kematian, ubah dulu status dasar warga yang meninggal di modul Penduduk.. </p>
								<p>Juga pastikan semua biodata orang tua warga yang meninggal, pelapor dan saksi-saksi sudah lengkap sebelum mencetak surat dan lampiran.</p>
								<p>Untuk melengkapi data itu, ubah data warga yang bersangkutan di form isian penduduk di modul Penduduk.</p>
							</div>
							<div class='modal-footer btn-default'>
								<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
