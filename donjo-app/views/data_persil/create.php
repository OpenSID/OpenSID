<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data C-Desa <?=ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?=site_url('data_persil/clear')?>"> Daftar C-Desa</a></li>
			<li class="active">Pengelolaan Data C-Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('data_persil/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('cdesa/clear')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar C-Desa"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar C-Desa</a>
					</div>
						<form id="main" name="main" method="POST" class="form-horizontal">
							<div class="box-body">
								<div class="form-group ">
									<label for="jenis_pemilik" class="col-sm-3 control-label">Jenis Pemilik</label>
									<div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
										<label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?php if (empty($cdesa) || $cdesa['jenis_pemilik'] == 1) {
                                            echo 'active';
                                        } ?>">
											<input type="radio" name="jenis_pemilik" class="form-check-input" value="1" autocomplete="off" <?php selected((empty($cdesa) || $cdesa['jenis_pemilik'] == 1), true, true)?> onchange="pilih_pemilik(this.value);">Warga Desa
										</label>
										<label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= ($cdesa['jenis_pemilik'] == 2) && print 'active' ?>">
											<input type="radio" name="jenis_pemilik" class="form-check-input" value="2" autocomplete="off" <?php selected(($cdesa['jenis_pemilik'] == 2), true, true)?> onchange="pilih_pemilik(this.value);">Warga Luar Desa
										</label>
									</div>
								</div>

								<div id="warga_desa">
									<div class="form-group">
										<label class="col-sm-3 control-label" >Cari Nama Pemilik</label>
										<div class="col-sm-8">
											<select class="form-control input-sm select2" id="nik" name="nik" onchange="ubah_pemilik($('#jenis_pemilik').val());">
												<option value="">-- Silakan Masukan NIK / Nama --</option>
												<?php foreach ($penduduk as $item): ?>
													<option value="<?= $item['id']?>" <?php selected($pemilik['nik'], $item['id'])?>>Nama : <?= $item['nama'] . ' Alamat : ' . $item['info']?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<?php if ($pemilik): ?>
										<div class="form-group">
											<label for="nama" class="col-sm-3 control-label">Pemilik</label>
											<div class="col-sm-8">
												<div class="form-group">
													<label class="col-sm-3 control-label">Nama Penduduk</label>
													<div class="col-sm-9">
														<input class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="<?= $pemilik['nama'] ?>" disabled >
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">NIK Pemilik</label>
													<div class="col-sm-9">
														<input class="form-control input-sm" type="text" placeholder="NIK Pemilik" value="<?= $pemilik['nik'] ?>" disabled >
													</div>
												</div>
												<div class="form-group">
													<label for="alamat" class="col-sm-3 control-label">Alamat Pemilik</label>
													<div class="col-sm-9">
														<textarea class="form-control input-sm" placeholder="Alamat Pemilik" rows="5" disabled><?= 'RT ' . $pemilik['rt'] . ' / RT ' . $pemilik['rw'] . ' - ' . strtoupper($pemilik['dusun']) ?></textarea>
													</div>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</form>
						<form name='mainform' action="<?= site_url('cdesa/simpan_cdesa')?>" method="POST" id="validasi" class="form-horizontal">
							<div class="box-body">
								<input id="jenis_pemilik" name="jenis_pemilik" type="hidden" value="1">
								<input type="hidden" name="nik_lama" value="<?= $pemilik['nik_lama'] ?>"/>
								<input type="hidden" name="nik" value="<?= $pemilik['nik'] ?>"/>
								<input type="hidden" name="id_pend" value="<?= $pemilik['id'] ?>"/>
								<?php if ($cdesa): ?>
									<input type="hidden" name="id" value="<?= $cdesa['id'] ?>"/>
								<?php endif; ?>
								<input type="hidden" name="c_desa" value="<?= $cdesa['c_desa'] ?>"/>

								<div id="warga_luar_desa">
									<div class="form-group">
										<label for="c_desa" class="col-sm-3 control-label">Nama Pemilik</label>
										<div class="col-sm-8">
											<input class="form-control input-sm required" type="text" placeholder="Nama Pemilik Luar" id="nama_pemilik_luar" name="nama_pemilik_luar" value="<?= ($cdesa['nama_pemilik_luar'])?>" <?php $pemilik && print 'disabled' ?>>
										</div>
									</div>
									<div class="form-group">
										<label for="c_desa" class="col-sm-3 control-label">Alamat Pemilik</label>
										<div class="col-sm-8">
											<input class="form-control input-sm required" type="text" placeholder="Alamat Pemilik Luar" id="alamat_pemilik_luar" name="alamat_pemilik_luar" value="<?= ($cdesa['alamat_pemilik_luar'])?>" <?php $pemilik && print 'disabled' ?>>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="c_desa" class="col-sm-3 control-label">Nomor C-DESA</label>
									<div class="col-sm-8">
										<input class="form-control input-sm angka required" type="text" placeholder="Nomor Surat C-DESA" name="c_desa" value="<?= ($cdesa['nomor'])?>" <?php if (! $pemilik && $cdesa['jenis_pemilik'] != 2) {
                                            echo 'disabled';
                                        }?> >
									</div>
								</div>
								<div class="form-group">
									<label for="nama_kepemilikan" class="col-sm-3 control-label">Nama Pemilik Tertulis di C-Desa</label>
									<div class="col-sm-8">
										<input class="form-control input-sm nama required" type="text" placeholder="Nama pemilik sebagaimana tertulis di Surat C-DESA" name="nama_kepemilikan" value="<?= ($cdesa['nama_kepemilikan']) ? sprintf('%04s', $cdesa['nama_kepemilikan']) : null ?>" <?php if (! $pemilik && $cdesa['jenis_pemilik'] != 2) {
                                            echo 'disabled';
                                        }?> >
									</div>
								</div>
							</div>
							<div class="box-footer">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function(){
		$('#tipe').change(function() {
			var id = $(this).val();
			$.ajax( {
				url : "<?= site_url('data_persil/kelasid'); ?>",
				method : "POST",
				data : {id: id},
				async : true,
				dataType : 'json',
				success: function(data) {
					var html = '';
					var i;
					for( i = 0; i < data.length; i++) {
						html += '<option value='+data[i].id+'>'+data[i].kode+' '+data[i].ndesc+'</option>';
					}
					$('#kelas').html(html);
				}
			});
			return false;
		});

		pilih_pemilik(<?= $cdesa['jenis_pemilik'] ?: 1?>);

	});

	function pilih_lokasi(pilih) {
		if (pilih == 1) {
			$("#manual").hide();
			$("#pilih").show();
		} else {
			$("#manual").removeClass('hidden');
			$("#manual").show();
			$("#pilih").hide();
		}
	}

	function pilih_pemilik(pilih) {
		$('#jenis_pemilik').val(pilih);
		if (pilih == 1) {
			if ($('#nik').val() == '') {
				$('input[name=c_desa]').attr('disabled','disabled');
				$('input[name=nama_kepemilikan]').attr('disabled','disabled');
			}
			$('#nama_pemilik_luar').val('');
			$('#nama_pemilik_luar').removeClass('required');
			$('#alamat_pemilik_luar').val('');
			$('#alamat_pemilik_luar').removeClass('required');
			$("#warga_luar_desa").hide();
			$('#nik').addClass('required');
			$("#warga_desa").show();
		} else {
			$('#nik').removeClass('required');
			$("#warga_desa").hide();
			$("#warga_luar_desa").show();
			$('#nama_pemilik_luar').addClass('required');
			$('#alamat_pemilik_luar').addClass('required');
			$('input[name=c_desa]').removeAttr('disabled');
			$('input[name=nama_kepemilikan]').removeAttr('disabled');
			if ($('#nik').val() != '') {
				$('#nik').val('');
				$('#nik').change();
			}
		}
	}

	function ubah_pemilik(jenis_pemilik) {
		$('#main').submit();
	}
</script>

