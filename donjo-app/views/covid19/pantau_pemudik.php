<div class="content-wrapper">
	<section class="content-header">
		<h1>Pemantauan Isolasi Mandiri Saat Pandemi Covid-19</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Pemudik</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Form Pemantauan</strong></h3>
					</div>
					<div class="box-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
							<input type="hidden" id="this_url" value="<?= $this_url ?>" >
							<input type="hidden" name="status_covid" id="status_covid" >
							<input type="hidden" id="page" name="page" value="<?= $page ?>" >

							<div class="form-group">
								<label for="nama">NIK/Nama</label>
								<select class="form-control select2" id="terdata" name="terdata">
									<option value="">-- Silakan Masukan NIK / Nama--</option>
									<?php foreach ($pemudik_array as $item): ?>
									<option value="<?= $item['id']?>" data-statuscovid="<?= $item['covid_id']?>" data-tgltiba="<?= $item['tanggal_datang']?>" > <?= $item['terdata_id'] . ' - ' . $item['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group">
								<label for="tgl_jam">Tanggal/Jam</label>
								<input type="text" class="form-control input-sm" name="tgl_jam" id="tgl_jam" value="<?= $datetime_now; ?>">
								</div>

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="tgl_jam">Tanggal Tiba</label>
											<input type="text" class="form-control input-sm" name="tgl_tiba" id="tgl_tiba" value="<?= $datetime_now; ?>" disabled>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="tgl_jam">Data H+</label>
											<input type="text" class="form-control input-sm" name="h_plus" id="h_plus" value="3" disabled>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="suhu">Suhu Tubuh</label>
									<input type="text" class="form-control input-sm" name="suhu" id="suhu" placeholder="36.75">
								</div>

								<div class="table-responsive-sm">
									<table class="table table-borderless table-sm">
										<thead>
											<tr>
												<th colspan="2" class="text-left">Centang jika mengalami kondisi berikut</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td width="20%" class="text-center">
													<input type="checkbox" class="form-check-input" name="batuk">
													</td>
													<td>Batuk</td>
												</tr>
												<tr>
													<td width="20%" class="text-center">
														<input type="checkbox" class="form-check-input" name="flu">
													</td>
													<td>Flu</td>
												</tr>
												<tr>
													<td width="20%" class="text-center">
														<input type="checkbox" class="form-check-input" name="sesak">
													</td>
													<td>Sesak nafas</td>
												</tr>
									</tbody>
								</table>
							</div>

							<div class="form-group">
									<label for="keluhan">Keluhan Lain</label>
									<textarea name="keluhan" class="form-control input-sm" placeholder="Keluhan Lain" rows="5"></textarea>
								</div>
						</form>
					</div>
					<?php if ($this->CI->cek_hak_akses('u', '', 'covid19/pantau')): ?>
						<div class="box-footer">
							<div class="box-tools pull-right">
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("covid19/daftar/cetak/{$filter_tgl}/{$filter_nik}")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak
						</a>
						<a href="<?= site_url("covid19/daftar/unduh/{$filter_tgl}/{$filter_nik}")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Unduh
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<form id="filterform" name="filterform"method="post">
								<div class="col-sm-3">
									<div class="form-group">
										<input type="hidden" id="hidden_unique_date_select" value="<?= $filter_tgl ?>" >
										<select class="form-control select2 input-sm" name="unique_date_select" id="unique_date_select" style="width: 100%;">
											<option value="0">-- Pilih Tanggal --</option>
											<?php foreach ($unique_date as $row): ?>
												<option value="<?= $row[tanggal] ?>" > <?= $row[tanggal] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="col-sm-5">
									<div class="form-group">
										<input type="hidden" id="hidden_unique_nik_select" value="<?= $filter_nik ?>" >
										<select class="form-control select2 input-sm" name="unique_nik_select" id="unique_nik_select">
											<option value="0">-- Pilih NIK/Nama --</option>
											<?php foreach ($unique_nik as $row): ?>
											<option value="<?= $row[id_pemudik] ?>" > <?= $row[nik] . ' - ' . $row[nama] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

							</form>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" method="post">
										<div class="table-responsive">
											<table class="table table-bordered dataTable table-striped table-hover">
												<thead class="bg-gray disabled color-palette">
													<tr>
														<th>No</th>
														<?php if ($this->CI->cek_hak_akses('h', '', 'covid19/pantau')): ?>
															<th>Aksi</th>
														<?php endif; ?>
														<th>Data H+</th>
														<th>Tanggal Tiba</th>
														<th>Waktu Pantau</th>
														<th>NIK</th>
														<th>Nama</th>
														<th>Usia</th>
														<th>JK</th>
														<th>Suhu</th>
														<th>Batuk</th>
														<th>Flu</th>
														<th>Sesak</th>
														<th>Keluhan</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<?php
                                                    $nomer = $paging->offset;

			foreach ($pantau_pemudik_array as $key => $item):
			    $nomer++;
			    ?>
													<tr>
														<td align="center" width="2"><?= $nomer; ?></td>
														<?php if ($this->CI->cek_hak_akses('h', '', 'covid19/pantau')): ?>
															<td nowrap>
																<a href="#" data-href="<?= site_url("{$url_delete_front}/{$item['id']}/{$url_delete_rare}")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
															</td>
														<?php endif; ?>
														<td><?= 'H+' . $item['date_diff'] ?></td>
														<td><?= $item['tanggal_datang'] ?></td>
														<td><?= $item['tanggal_jam'] ?></td>
														<td><?= $item['nik'] ?></td>
														<td><?= $item['nama'] ?></td>
														<td><?= $item['umur'] ?></td>
														<td><?= ($item['sex'] === '1' ? 'Lk' : 'Pr'); ?></td>
														<td><?= $item['suhu_tubuh']; ?></td>
														<td><?= ($item['batuk'] === '1' ? 'Ya' : 'Tidak'); ?></td>
														<td><?= ($item['flu'] === '1' ? 'Ya' : 'Tidak'); ?></td>
														<td><?= ($item['sesak_nafas'] === '1' ? 'Ya' : 'Tidak'); ?></td>
														<td><?= $item['keluhan_lain']; ?></td>
														<td><?= $item['status_covid']; ?></td>
													</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-6">
											<div class="dataTables_length">
												<form id="paging"method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="10" <?php selected($per_page, 10); ?> >10</option>
															<option value="100" <?php selected($per_page, 100); ?> >100</option>
															<option value="200" <?php selected($per_page, 200); ?> >200</option>
														</select>
														Dari
														<strong><?= $paging->num_rows?></strong>
														Total Data
													</label>
												</form>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<ul class="pagination">
													<?php if ($paging->start_link): ?>
														<li>
															<a href="<?=site_url('covid19/pantau/' . $paging->start_link)?>" aria-label="First"><span aria-hidden="true">Awal</span></a>
														</li>
													<?php endif; ?>

													<?php if ($paging->prev): ?>
														<li>
															<a href="<?=site_url('covid19/pantau/' . $paging->prev)?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
														</li>
													<?php endif; ?>

													<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
														<li <?=jecho($p, $i, "class='active'")?>>
															<a href="<?= site_url('covid19/pantau/' . $i)?>"><?= $i?></a>
														</li>
													<?php endfor; ?>

													<?php if ($paging->next): ?>
														<li>
															<a href="<?=site_url('covid19/pantau/' . $paging->next)?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
														</li>
													<?php endif; ?>

													<?php if ($paging->end_link): ?>
														<li>
															<a href="<?=site_url('covid19/pantau/' . $paging->end_link)?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a>
														</li>
													<?php endif; ?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('global/confirm_delete'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#unique_date_select").val($("#hidden_unique_date_select").val());
		$("#unique_nik_select").val($("#hidden_unique_nik_select").val());

		//https://momentjs.com/docs/#/parsing/string-format/
		$('#tgl_jam').datetimepicker({
			format: 'YYYY-MM-DD HH:mm:ss',
		});

		function change_arrival_date() {
			var retval = 0;
			if ($("#terdata").val() != "") {
				$("#status_covid").val($("#terdata").find(':selected').data('statuscovid'));
				var temp1 = new Date($("#terdata").find(':selected').data('tgltiba'));
				var tgl_tiba = new Date(temp1.getFullYear()+"-"+(temp1.getMonth()+1)+"-"+temp1.getDate());

				var temp2 = new Date($('#tgl_jam').val());
				var tgl_catat = new Date(temp2.getFullYear()+"-"+(temp2.getMonth()+1)+"-"+temp2.getDate());

				var timediff = tgl_catat - tgl_tiba;
				var diffdays = Math.floor(timediff / 86400000);

				$("#tgl_tiba").val($("#terdata").find(':selected').data('tgltiba'));
				$("#h_plus").val(diffdays);

				retval = diffdays;
			} else {
				$("#tgl_tiba").val("");
				$("#h_plus").val("");
			}

			return retval;
		}

		$("#tgl_tiba").val("");
		$("#h_plus").val("");
		$("#terdata").change(function() {
			var diff_day = change_arrival_date();

			var tgl_tiba = moment().subtract(diff_day, 'days').millisecond(0).second(0).minute(0).hour(0);
			var date_now = moment();

			$('#tgl_jam').data("DateTimePicker").options({minDate: tgl_tiba, maxDate:date_now});
		});

		$('#tgl_jam').on('dp.change', function(e){
			//var formatedValue = e.date.format(e.date._f);
			change_arrival_date();
		});

		$("#unique_date_select").change(function() {
			url = $("#this_url").val();
			url += "/"+$("#page").val();
			url += "/"+$("#unique_date_select").val();
			url += "/"+$("#unique_nik_select").val();
			$(location).attr('href',url);
		});

		$("#unique_nik_select").change(function() {
			url = $("#this_url").val();
			url += "/"+$("#page").val();
			url += "/"+$("#unique_date_select").val();
			url += "/"+$("#unique_nik_select").val();
			$(location).attr('href',url);
		});

		$("#validasi").validate({
			rules: {
				terdata: "required",
				tgl_jam: "required",
				suhu: {
					required: true,
					number: true,
					min: 10,
					max: 50,
				},
			},

			// Specify validation error messages
			messages: {
				terdata: "Harus memilih NIK/Nama",
				tgl_jam: "Tanggal/Jam harus diisi",
				suhu: {
					required: "Suhu harus tercatat",
					number: "Harus diisi angka",
					min: "Suhu minimal 10 derajat celcius",
					max: "Suhu maksimal 50 derajat celcius",
				},
			},

			submitHandler: function(form) {
				form.submit();
			}
		});
	});
</script>
