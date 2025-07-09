<div class="content-wrapper">
	<section class="content-header">
		<h1>Pendataan Penerima Vaksin Covid-19</h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a>
			</li>
			<li class="active">Data Penerima Vaksin</li>
		</ol>
	</section>
    <?php if ($pesan_vaksin = session('pesan_vaksin')): ?>
        <section class="content" id="maincontent">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Vaksin">
                        <i class="fa fa-arrow-circle-left "></i>Kembali
                    </a>
                </div>
                <div class="box-body">
                    <h4>Hasil Impor Data Penerima Vaksin</h4>
                    <p><?= $pesan_vaksin ?></p>
                </div>
            </div>
        </section>
    <?php else: ?>
		<section class="content" id="maincontent">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<div class="box box-info">
						<div class="box-body no-padding">
							<?php $this->load->view('covid19/vaksin/side') ?>
						</div>
					</div>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if (can('u')): ?>
								<a href="<?= site_url("{$this->controller}/form"); ?>" title="Tambah Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Data</a>
								<a href="<?= site_url("{$this->controller}/impor"); ?>" title="Impor Data" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-import" data-target="#impor" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false"><i class="fa fa-upload"></i>Impor</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="row">
										<div class="col-sm-8">
											<select class="form-control input-sm" name="vaksin" onchange="formAction('mainform', '<?= site_url($this->controller) . '/filter/vaksin' ?>')" style="margin-bottom: 5px;">
												<option value="">-- Status Vaksin --</option>
												<option value="1" <?= selected($vaksin, '1'); ?>>Vaksin Dosis 1</option>
												<option value="2" <?= selected($vaksin, '2'); ?>>Vaksin Dosis 2</option>
												<option value="3" <?= selected($vaksin, '3'); ?>>Vaksin Dosis 3</option>
												<option value="4" <?= selected($vaksin, '4'); ?>>Belum</option>
												<option value="5" <?= selected($vaksin, '5'); ?>>Tunda</option>
											</select>
											<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url("{$this->controller}/filter/dusun"); ?>')" style="margin-bottom: 5px;">
												<option value="">-- Pilih <?= ucwords(setting('sebutan_dusun')); ?> --</option>
												<?php foreach ($list_dusun as $data) : ?>
													<option value="<?= $data['dusun']; ?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun']); ?></option>
												<?php endforeach; ?>
											</select>
											<select class="form-control input-sm " name="jenis_vaksin" onchange="formAction('mainform','<?= site_url("{$this->controller}/filter/jenis_vaksin"); ?>')" style="margin-bottom: 5px;">
												<option value="">-- Pilih Jenis Vaksin --</option>
												<?php foreach ($list_vaksin as $data) : ?>
													<option value="<?= $data; ?>" <?= selected($jenis_vaksin, $data); ?>><?= set_ucwords($data); ?></option>
												<?php endforeach; ?>
											</select>
											<div class="input-group input-group-sm" style="margin-bottom: 5px;">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control input-sm tgl-datepicker"  name="tanggal_vaksin" value="<?= ($tanggal_vaksin) ?>">
											</div>
											<div class="input-group input-group-sm" style="margin-bottom: 5px;">
												<div class="input-group-addon">
													<i class="fa fa-filter"></i>
												</div>
												<input name="umur" id="umur" class="form-control ui-autocomplete-input" placeholder="Rentang Umur" title="Contoh : 20-30" type="text" value="<?= ($umur == 0) ? '' : $umur ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url($this->controller . '/filter/umur'); ?>');$('#mainform').submit();}" autocomplete="off">
											</div>
										</div>
										<div class="col-sm-4">
											<div class="input-group input-group-sm pull-right">
												<input name="cari" id="cari" class="form-control ui-autocomplete-input" placeholder="Cari..." type="text" value="<?= html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url($this->controller . '/search'); ?>');$('#'+'mainform').submit();}" autocomplete="off">
												<div class="input-group-btn">
													<button type="button" class="btn btn-default" onclick="$('#mainform').attr('action', '<?= site_url($this->controller) ?>/search');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
												</div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="table-responsive">
												<table id="tabel-data" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
													<thead class="bg-gray color-palette">
														<tr>
															<th>No</th>
															<th>Aksi</th>
															<th>Nik</th>
															<th>Nama</th>
															<th>No KK</th>
															<th>Umur</th>
															<th><?= ucwords(setting('sebutan_dusun')) ?></th>
															<th>Alamat</th>
															<th>Keterangan</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($main as $key => $data) : ?>
															<tr>
																<td class="padat"><?= ($key + $paging->offset + 1) ?></td>
																<td class="aksi">
																	<?php if (can('u')) : ?>
																		<a href="<?= site_url("{$this->controller}/form?terdata={$data->id}") ?>" class="btn bg-orange btn-flat btn-sm" title="Update Vaksin"><i class="fa fa-edit"></i></a>
																	<?php endif; ?>
																	<div class="btn-group">
																		<button type="button" class="btn btn-social btn-flat btn-info btn-sm unduh" data-toggle="dropdown" aria-expanded="false">
																			<i class="fa fa-arrow-circle-down"></i> Unduh
																		</button>
																		<ul class="dropdown-menu" role="menu">
																			<?php if ($data->tunda): ?>
																				<?php if ($data->surat_dokter != null): ?>
																					<li>
																						<a href="<?= site_url($this->controller . "/berkas/{$data->id_penduduk}/surat_dokter/true"); ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-file"></i> Unduh Surat Dokter</a>
																					</li>
																				<?php endif ?>
																			<?php else: ?>
																				<?php for ($i = 1; $i <= 3; $i++): ?>
																					<?php if ($data->{"dokumen_vaksin_{$i}"} != null || $data->{"dokumen_vaksin_{$i}"} != ''): ?>
																						<li>
																							<a href="<?= site_url($this->controller . "/berkas/{$data->id_penduduk}/dokumen_vaksin_{$i}/true"); ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-file"></i> Unduh Sertifikat Vaksin <?= $i ?></a>
																						</li>
																					<?php endif ?>
																				<?php endfor; ?>
																			<?php endif ?>
																		</ul>
																	</div>
																	<a href="<?= site_url($this->controller . "/tampil_sertifikat/{$data->id_penduduk}"); ?>" class="btn bg-blue btn-flat btn-sm tampil" title="Tampilkan"><i class="fa fa-eye"></i></a>
																</td>
																<td class="padat"><?= $data->nik ?></td>
																<td><?= $data->nama ?></td>
																<td class="padat"><?= $data->no_kk ?></td>
																<td class="padat"><?= $data->umur ?></td>
																<td><?= $data->dusun ?></td>
																<td><?= $data->alamat ?></td>
																<td>
																	<?php if ($vaksin == 1 || $vaksin == 2 || $vaksin == 3) : ?>
																		Vaksin Dosis ke <?= $vaksin ?>
																	<?php else : ?>
																		<?php if ($data->tunda == 1) : ?>
																			Tunda - <?= $data->keterangan ?>
																		<?php elseif ($data->vaksin_3) : ?>
																			Vaksin Dosis ke 3
																		<?php elseif ($data->vaksin_2) : ?>
																			Vaksin Dosis ke 2
																		<?php elseif ($data->vaksin_1) : ?>
																			Vaksin Dosis ke 1
																		<?php else : ?>
																			Belum Vaksin
																		<?php endif ?>
																<?php endif ?>
																</td>
															</tr>
														<?php endforeach ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</form>
								<?php $this->load->view('global/paging'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
    <?php endif; ?>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		$('.tgl-datepicker').datetimepicker({
			format: 'DD-MM-YYYY',
			useCurrent: false
		});

		$('.tgl-datepicker').on('dp.change', function(e) {
			formAction('mainform', '<?= site_url("{$this->controller}/filter/tanggal_vaksin"); ?>')
		});

		$("#tabel-data tbody tr").each(function(index, el) {
			if ($(el).find('button.unduh').parent().find('li').length == 0) {
				$(el).find('button.unduh').addClass('disabled');
				$(el).find('a.tampil').addClass('disabled');
			}
		});

		$( "#cari" ).autocomplete({
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: `${SITE_URL}vaksin_covid/autocomplete`,
					dataType: "json",
					data: {
						cari: request.term
					},
					success: function( data ) {
						response( JSON.parse( data ));
						csrf_semua_form();
					}
				} );
			},
			minLength: 2,
		});
	});
</script>
<?php $this->load->view('covid19/vaksin/impor'); ?>