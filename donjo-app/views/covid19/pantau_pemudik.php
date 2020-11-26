<style>
	.input-sm
	{
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Pemantauan Isolasi Mandiri
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Data Pemudik</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<h3 class="box-title"><strong>Form Pemantauan</strong></h3>
					</div>
					<div class="card-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">

							<input type="hidden" id="this_url" value="<?= $this_url ?>" >
							<input type="hidden" name="status_covid" id="status_covid" >
							<input type="hidden" id="page" name="page" value="<?= $page ?>" >

							<div class="form-group">
								<label for="nama">NIK/Nama</label>
								<select class="form-control select2" id="terdata" name="terdata" style="width: 100%;">
									<option value="">-- Silakan Masukan NIK / Nama--</option>
									<?php foreach ($pemudik_array as $item): ?>
									<option value="<?= $item['id']?>" data-statuscovid="<?= $item['status_covid']?>" data-tgltiba="<?= $item['tanggal_datang']?>" > <?= $item['terdata_id']." - ".$item['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group">
								<label for="tgl_jam">Tanggal/Jam</label>
								<input type="text" class="form-control form-control-sm" name="tgl_jam" id="tgl_jam" value="<?= $datetime_now; ?>">
								</div>

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="tgl_jam">Tanggal Tiba</label>
											<input type="text" class="form-control form-control-sm" name="tgl_tiba" id="tgl_tiba" value="<?= $datetime_now; ?>" disabled>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="tgl_jam">Data H+</label>
											<input type="text" class="form-control form-control-sm" name="h_plus" id="h_plus" value="3" disabled>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="suhu">Suhu Tubuh</label>
									<input type="text" class="form-control form-control-sm" name="suhu" id="suhu" placeholder="36.75">
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
									<textarea name="keluhan" class="form-control form-control-sm" placeholder="Keluhan Lain" rows="3" style="resize:none;"></textarea>
								</div>
						</form>
					</div>
					<div class="card-footer">
						<div class="card-tools pull-right">
							<button type="submit" class="btn btn-flat btn-info btn-xs pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="card card-outline card-info">
					<div class="card-header with-border">
						<a href="<?= site_url("covid19/daftar/cetak/$filter_tgl/$filter_nik")?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak
						</a>
						<a href="<?= site_url("covid19/daftar/unduh/$filter_tgl/$filter_nik")?>" class="btn btn-flat bg-navy btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Unduh" target="_blank"><i class="fa fa-download"></i> Unduh
						</a>
					</div>
					<div class="card-body">
						<div class="row">
							<form id="filterform" name="filterform" action="" method="post">

								<div class="container-fluid">
									<div class="row mb-2">

										<div class="col-sm-6">
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
										
										<div class="col-sm-6">
											<div class="form-group">
												<input type="hidden" id="hidden_unique_nik_select" value="<?= $filter_nik ?>" >
												<select class="form-control select2 input-sm" name="unique_nik_select" id="unique_nik_select" style="width: 100%;">
													<option value="0">-- Pilih NIK/Nama --</option>
													<?php foreach ($unique_nik as $row): ?>
														<option value="<?= $row[id_pemudik] ?>" > <?= $row[nik]." - ".$row[nama] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

									</div>
								</div>

							</form>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper dt-bootstrap no-footer">
									<form class="form-inline" id="mainform" name="mainform" action="" method="post">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-striped table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Aksi</th>
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
															foreach ($pantau_pemudik_array as $key=>$item):
																$nomer++;
															?>
															<tr>
																<td align="center" width="2"><?= $nomer; ?></td>
																<td nowrap>
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																	<a href="#" data-href="<?= site_url("$url_delete_front/$item[id]/$url_delete_rare")?>" class="btn bg-maroon btn-flat btn-xs" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>																	<?php endif; ?>
																</td>
																<td><?= "H+".$item["date_diff"] ?></td>
																<td><?= $item["tanggal_datang"] ?></td>
																<td><?= $item["tanggal_jam"] ?></td>
																<td><?= $item["nik"] ?></td>
																<td><?= $item["nama"] ?></td>
																<td><?= $item["umur"] ?></td>
																<td><?= ($item["sex"]==='1' ? 'Lk' : 'Pr'); ?></td>
																<td><?= $item["suhu_tubuh"];?></td>
																<td><?= ($item["batuk"]==='1' ? 'Ya' : 'Tidak'); ?></td>
																<td><?= ($item["flu"]==='1' ? 'Ya' : 'Tidak');?></td>
																<td><?= ($item["sesak_nafas"]==='1' ? 'Ya' : 'Tidak');?></td>
																<td><?= $item["keluhan_lain"];?></td>
																<td><?= $item["status_covid"];?></td>
															</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
									</form>
									<?php $this->load->view('global/paging'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('global/confirm_delete');?>

<script type="text/javascript">
	$(document).ready(function()
	{
		$("#unique_date_select").val($("#hidden_unique_date_select").val());
		$("#unique_nik_select").val($("#hidden_unique_nik_select").val());

		//https://momentjs.com/docs/#/parsing/string-format/
		$('#tgl_jam').datetimepicker(
		{
			format: 'YYYY-MM-DD HH:mm:ss',
		});

		function change_arrival_date() {
			var retval = 0;
			if ($("#terdata").val() != "")
			{
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
			}
			else
			{
				$("#tgl_tiba").val("");
				$("#h_plus").val("");
			}

			return retval;
		}

		$("#tgl_tiba").val("");
		$("#h_plus").val("");
		$("#terdata").change(function()
		{
			var diff_day = change_arrival_date();

			var tgl_tiba = moment().subtract(diff_day, 'days').millisecond(0).second(0).minute(0).hour(0);
			var date_now = moment();

			$('#tgl_jam').data("DateTimePicker").options({minDate: tgl_tiba, maxDate:date_now});
		});

		$('#tgl_jam').on('dp.change', function(e){
			//var formatedValue = e.date.format(e.date._f);
			change_arrival_date();
		});

		$("#unique_date_select").change(function()
		{
			url = $("#this_url").val();
			url += "/"+$("#page").val();
			url += "/"+$("#unique_date_select").val();
			url += "/"+$("#unique_nik_select").val();
			$(location).attr('href',url);
		});

		$("#unique_nik_select").change(function()
		{
			url = $("#this_url").val();
			url += "/"+$("#page").val();
			url += "/"+$("#unique_date_select").val();
			url += "/"+$("#unique_nik_select").val();
			$(location).attr('href',url);
		});

		$("#validasi").validate(
		{
				rules:
				{
				terdata: "required",
				tgl_jam: "required",
				suhu:
				{
					required: true,
					number: true,
					min: 10,
					max: 50,
				},
				},
				// Specify validation error messages
				messages:
				{
				terdata: "Harus memilih NIK/Nama",
				tgl_jam: "Tanggal/Jam harus diisi",
				suhu:
				{
					required: "Suhu harus tercatat",
					number: "Harus diisi angka",
					min: "Suhu minimal 10 derajat celcius",
					max: "Suhu maksimal 50 derajat celcius",
				},
				},
				submitHandler: function(form)
				{
					form.submit();
				}
			});
	});
</script>
