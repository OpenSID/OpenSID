<div class="content-wrapper">
	<section class="content-header">
		<h1>Pendataan Penerima Vaksin Covid-19</h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a>
			</li>
			<li class="active">Data Penerima Vaksin</li>
		</ol>
	</section>
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
						<a href="<?= site_url($this->controller . '/form'); ?>" title="Tambah Kader Pembangunan" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Data</a>
						<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<form id="mainform" name="mainform" method="post">
								<div class="row">
									<div class="col-sm-8">
										<select class="form-control input-sm" name="vaksin" onchange="formAction('mainform', '<?= site_url($this->controller) . '/filter/vaksin' ?>')">
											<option value="">-- Status Vaksin --</option>
											<option value="1" <?= selected($vaksin, '1'); ?>>Vaksin Dosis 1</option>
											<option value="2" <?= selected($vaksin, '2'); ?>>Vaksin Dosis 2</option>
											<option value="3" <?= selected($vaksin, '3'); ?>>Vaksin Dosis 3</option>
											<option value="4" <?= selected($vaksin, '4'); ?>>Belum</option>
											<option value="5" <?= selected($vaksin, '5'); ?>>Tunda</option>
										</select>
										<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url("{$this->controller}/filter/dusun"); ?>')">
											<option value="">-- Pilih <?= ucwords($this->setting->sebutan_dusun); ?> --</option>
											<?php foreach ($list_dusun as $data) : ?>
												<option value="<?= $data['dusun']; ?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun']); ?></option>
											<?php endforeach; ?>
										</select>
										<select class="form-control input-sm " name="jenis_vaksin" onchange="formAction('mainform','<?= site_url("{$this->controller}/filter/jenis_vaksin"); ?>')">
											<option value="">-- Pilih Jenis Vaksin --</option>
											<?php foreach ($list_vaksin as $data) : ?>
												<option value="<?= $data; ?>" <?= selected($jenis_vaksin, $data); ?>><?= set_ucwords($data); ?></option>
											<?php endforeach; ?>
										</select>

										<div class="input-group input-group-sm">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control input-sm tgl-datepicker"  name="tanggal_vaksin" value="<?= ($tanggal_vaksin) ?>"  >
										</div>
									</div>
									<div class="col-sm-4">
										<div class="input-group input-group-sm pull-right">
											<input name="cari" id="cari" class="form-control ui-autocomplete-input" placeholder="Cari..." type="text" value="" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url($this->controller . '/search'); ?>');$('#mainform').submit();}" autocomplete="off">
											<div class="input-group-btn">
												<button type="submit" class="btn btn-default" onclick="$('#mainform').attr('action', '<?= site_url($this->controller) ?>/search');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
														<th>Dusun</th>
														<th>Alamat</th>
														<th>Keterangan</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($main as $key => $data) : ?>
														<tr>
															<td class="padat"><?= ($key + $paging->offset + 1) ?></td>
															<td class="aksi">
																<?php if ($this->CI->cek_hak_akses('u')) : ?>
																	<a href="<?= site_url("{$this->controller}/form?terdata={$data->id}") ?>" class="btn bg-orange btn-flat btn-sm" title="Update Vaksin"><i class="fa fa-edit"></i></a>
																<?php endif; ?>
															</td>
															<td class="padat"><?= $data->nik ?></td>
															<td><?= $data->nama ?></td>
															<td class="padat"><?= $data->no_kk ?></td>
															<td class="padat"><?= $data->umur ?></td>
															<td><?= $data->dusun ?></td>
															<td><?= $data->alamat ?></td>
															<td>
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
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.tgl-datepicker').datetimepicker({
			format: 'DD-MM-YYYY',
			useCurrent: false
		});

		$('.tgl-datepicker').on('dp.change', function(e){
			formAction('mainform','<?= site_url("{$this->controller}/filter/tanggal_vaksin"); ?>')
		});
	});
</script>