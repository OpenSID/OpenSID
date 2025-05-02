<div class="content-wrapper">
	<section class="content-header">
		<h1>Sertifikat Vaksin Covid 19</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url($this->controller) ?>"><i class="fa fa-medkit"></i> Daftar Penduduk Penerima Vaksin Covid 19</a></li>
			<li class="active">Sertifikat Vaksin</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-sm-3 col-lg-3 col-xs-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Penerima Vaksin</strong></h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label>Nama</label>
							<input type="text" class="form-control input-sm" value="<?= $penduduk->nama ?>" readonly>
						</div>
						<div class="form-group">
							<label>NIK</label>
							<input type="text" class="form-control input-sm" value="<?= $penduduk->nik ?>" readonly>
						</div>
						<div class="form-group">
							<label>No KK</label>
							<input type="text" class="form-control input-sm" value="<?= $penduduk->no_kk ?>" readonly>
						</div>
						<div class="form-group">
							<label>Umur</label>
							<input type="text" class="form-control input-sm" value="<?= $penduduk->umur ?> Tahun" readonly>
						</div>
						<div class="form-group">
							<label>Dusun</label>
							<input type="text" class="form-control input-sm" value="<?= $penduduk->dusun ?>" readonly>
						</div>
						<div class="form-group">
							<label>Alamat</label>
							<textarea class="form-control input-sm" rows="5" readonly><?= $penduduk->alamat ?></textarea>
						</div>
						<?php
                            if ($penduduk->tunda == 1) {
                                $ket = "Tunda -  {$penduduk->keterangan}";
                            } elseif ($penduduk->vaksin_3) {
                                $ket = 'Vaksin Dosis ke 3';
                            } elseif ($penduduk->vaksin_2) {
                                $ket = 'Vaksin Dosis ke 2';
                            } elseif ($penduduk->vaksin_1) {
                                $ket = 'Vaksin Dosis ke 1';
                            } else {
                                $ket = 'Belum Vaksin';
                            }
            ?>
						<div class="form-group">
							<label>Keterangan Vaksin</label>
							<textarea class="form-control input-sm" rows="5" readonly><?= $ket ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-lg-9 col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs" id="nav-tab" role="tablist">
						<li><a href="#surat-dokter" data-toggle="tab" id="nav-surat-dokter" style="display: none;"><strong>Dokter</strong></a></li>
						<li><a href="#dokumen-vaksin-1" data-toggle="tab" id="nav-dokumen-vaksin-1" style="display: none;"><strong>Vaksin Dosis 1</strong></a></li>
						<li><a href="#dokumen-vaksin-2" data-toggle="tab" id="nav-dokumen-vaksin-2" style="display: none;"><strong>Vaksin Dosis 2</strong></a></li>
						<li><a href="#dokumen-vaksin-3" data-toggle="tab" id="nav-dokumen-vaksin-3" style="display: none;"><strong>Vaksin Dosis 3</strong></a></li>
					</ul>
					<div class="tab-content">
						<?php if ($penduduk->tunda == 1) : ?>
							<?php if ($penduduk->surat_dokter != null && $penduduk->surat_dokter != '') : ?>
								<div class="tab-pane" id="surat-dokter">
									<div class="row">
										<div class="col-sm-12">
											<div class="thumbnail">
												<?php if (get_extension($penduduk->surat_dokter) == '.pdf') : ?>
													<object data="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/surat_dokter/false/true#toolbar=1") ?>" style="width: 100%;min-height: 600px" type="application/pdf"></object>
												<?php else : ?>
													<img src="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/surat_dokter/false/true") ?>" width="100%">
												<?php endif ?>
											</div>
										</div>
									</div>
								</div>
							<?php endif ?>
						<?php elseif ($penduduk->tunda == 0) : ?>
							<?php if ($penduduk->dokumen_vaksin_1 != null && $penduduk->dokumen_vaksin_1 != '0') : ?>
								<div class="tab-pane" id="dokumen-vaksin-1">
									<div class="row">
										<div class="col-sm-12">
											<?php if (get_extension($penduduk->dokumen_vaksin_1) == '.pdf') : ?>
												<object data="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/dokumen_vaksin_1/false/true#toolbar=1") ?>" style="width: 100%;min-height: 600px;" type="application/pdf"></object>
											<?php else : ?>
												<img src="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/dokumen_vaksin_1/false/true") ?>" width="100%">
											<?php endif ?>
										</div>
									</div>
								</div>
							<?php endif ?>
							<?php if ($penduduk->dokumen_vaksin_2 != null && $penduduk->dokumen_vaksin_2 != '') : ?>
								<div class="tab-pane" id="dokumen-vaksin-2">
									<div class="row">
										<div class="col-sm-12">
											<?php if (get_extension($penduduk->dokumen_vaksin_2) == '.pdf') : ?>
												<object data="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/dokumen_vaksin_2/false/true#toolbar=1") ?>" style="width: 100%;min-height: 600px" type="application/pdf"></object>
											<?php else : ?>
												<img src="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/dokumen_vaksin_2/false/true") ?>" width="100%">
											<?php endif ?>
										</div>
									</div>
								</div>
							<?php endif ?>
							<?php if ($penduduk->dokumen_vaksin_3 != null && $penduduk->dokumen_vaksin_3 != '') : ?>
								<div class="tab-pane" id="dokumen-vaksin-3">
									<div class="row">
										<div class="col-sm-12">
											<?php if (get_extension($penduduk->dokumen_vaksin_3) == '.pdf') : ?>
												<object data="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/dokumen_vaksin_3/false/true#toolbar=1") ?>" style="width: 100%;min-height: 600px" type="application/pdf"></object>
											<?php else : ?>
												<img src="<?= site_url("vaksin_covid/berkas/{$penduduk->id}/dokumen_vaksin_3/false/true") ?>" width="100%">
											<?php endif ?>
										</div>
									</div>
								</div>
							<?php endif ?>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$.each($(".tab-content .tab-pane"), function(index, val) {
			var id_sertifikat = $(val).attr('id');
			$(`#nav-${id_sertifikat}`).show();
			if (index == 0) {
				$(`#nav-${id_sertifikat}`).trigger("click")
			}
		});
	});
</script>