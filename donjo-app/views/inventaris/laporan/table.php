<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Keseluruhan Asset Desa</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Laporan Keseluruhan Asset Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainformexcel" name="mainformexcel" action="" method="post">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('inventaris/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="#" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#cetakBox" data-title="Cetak Inventaris">
								<i class="fa fa-print"></i>Cetak
            	</a>
							<a href="#" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#unduhBox" data-title="Unduh Inventaris">
								<i class="fa fa-download"></i>Unduh
            	</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table id="tabel4-laporan" class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray">
																<tr>
																	<th class="text-center" rowspan="3">No</th>
																	<th class="text-center" rowspan="3">Jenis Barang</th>
																	<th class="text-center" width="340%" rowspan="3">Keterangan</th>
																	<th class="text-center" colspan="5">Asal barang</th>
																	<th class="text-center" rowspan="3" >Aksi</th>
																</tr>
																<tr>
																	<th class="text-center" rowspan="2">Dibeli Sendiri</th>
																	<th class="text-center" colspan="3">Bantuan</th>
																	<th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
																</tr>
																<tr>
																	<th class="text-center" >Pemerintah</th>
																	<th class="text-center" >Provinsi</th>
																	<th class="text-center" >Kabupaten</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td></td>
																	<td nowrap>Tanah Kas Desa</td>
																	<td>Informasi mengenai segala yang menyangkut dengan tanah (dalam hal ini tanah yang digunakan dalam instansi tersebut).</td>
																	<td>
																		<?=$inventaris_tanah_pribadi->total?>
																	</td>
																	<td>
																		<?=$inventaris_tanah_pemerintah->total?>
																	</td>
																	<td>
																		<?=$inventaris_tanah_provinsi->total?>
																	</td>
																	<td>
																		<?=$inventaris_tanah_kabupaten->total?>
																	</td>
																	<td>
																		<?=$inventaris_tanah_sumbangan->total?>
																	</td>
																	<td>
																		<div class="btn-group" role="group" aria-label="...">
																			<a href="<?= site_url('inventaris_tanah'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td nowrap>Peralatan dan Mesin</td>
																	<td>Informasi mengenai peralatan dan mesin</td>
																	<td>
																		<?=$inventaris_peralatan_pribadi->total?>
																	</td>
																	<td>
																		<?=$inventaris_peralatan_pemerintah->total?>
																	</td>
																	<td>
																		<?=$inventaris_peralatan_provinsi->total?>
																	</td>
																	<td>
																		<?=$inventaris_peralatan_kabupaten->total?>
																	</td>
																	<td>
																		<?=$inventaris_peralatan_sumbangan->total?>
																	</td>
																	<td>
																		<div class="btn-group" role="group" aria-label="...">
																			<a href="<?= site_url('inventaris_peralatan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td nowrap>Gedung dan Bangunan</td>
																	<td>Informasi mengenai gedung dan bangunan yang dimiliki.</td>
																	<td>
																		<?=$inventaris_gedung_pribadi->total?>
																	</td>
																	<td>
																		<?=$inventaris_gedung_pemerintah->total?>
																	</td>
																	<td>
																		<?=$inventaris_gedung_provinsi->total?>
																	</td>
																	<td>
																		<?=$inventaris_gedung_kabupaten->total?>
																	</td>
																	<td>
																		<?=$inventaris_gedung_sumbangan->total?>
																	</td>
																	<td>
																		<div class="btn-group" role="group" aria-label="...">
																			<a href="<?= site_url('inventaris_gedung'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td nowrap> Jalan Irigasi dan Jaringan</td>
																	<td>Informasi mengenai jaringan, seperti listrik atau Internet.</td>
																	<td>
																		<?=$inventaris_jalan_pribadi->total?>
																	</td>
																	<td>
																		<?=$inventaris_jalan_pemerintah->total?>
																	</td>
																	<td>
																		<?=$inventaris_jalan_provinsi->total?>
																	</td>
																	<td>
																		<?=$inventaris_jalan_kabupaten->total?>
																	</td>
																	<td>
																		<?=$inventaris_jalan_sumbangan->total?>
																	</td>
																	<td>
																		<div class="btn-group" role="group" aria-label="...">
																			<a href="<?= site_url('inventaris_jalan/'.$data->id); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td nowrap> Asset Tetap Lainnya</td>
																	<td>Informasi mengenai aset tetap seperti barang habis pakai contohnya buku-buku.</td>
																	<td>
																		<?=$inventaris_asset_pribadi->total?>
																	</td>
																	<td>
																		<?=$inventaris_asset_pemerintah->total?>
																	</td>
																	<td>
																		<?=$inventaris_asset_provinsi->total?>
																	</td>
																	<td>
																		<?=$inventaris_asset_kabupaten->total?>
																	</td>
																	<td>
																		<?=$inventaris_asset_sumbangan->total?>
																	</td>
																	<td>
																		<div class="btn-group" role="group" aria-label="...">
																			<a href="<?= site_url('inventaris_asset'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td nowrap>Kontruksi Dalam Pengerjaan</td>
																	<td>Informasi mengenai bangunan yang masih dalam pengerjaan.</td>
																	<td>
																		<?=$inventaris_kontruksi_pribadi->total?>
																	</td>
																	<td>
																		<?=$inventaris_kontruksi_pemerintah->total?>
																	</td>
																	<td>
																		<?=$inventaris_kontruksi_provinsi->total?>
																	</td>
																	<td>
																		<?=$inventaris_kontruksi_kabupaten->total?>
																	</td>
																	<td>
																		<?=$inventaris_kontruksi_sumbangan->total?>
																	</td>
																	<td>
																		<div class="btn-group" role="group" aria-label="...">
																			<a href="<?= site_url('inventaris_kontruksi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
																		</div>
																	</td>
																</tr>
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="3" class="text-center"></th>
																	<th></th>
																	<th></th>
																	<th></th>
																	<th></th>
																	<th></th>
																	<th></th>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div id="unduhBox" class="modal fade" role="dialog" style="padding-top:30px;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Unduh Inventaris</h4>
										</div>
										<form action="" target="_blank" class="form-horizontal" method="get" >
											<div class="modal-body">
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="nama_barang">Tahun</label>
													<div class="col-sm-9">
														<select name="tahun" id="tahun" class="form-control select2 input-sm" style="width:100%;">
															<option value="1">Semua Tahun</option>
															<?php for ($i=date("Y"); $i>=date("Y")-30; $i--): ?>
																<option value="<?= $i ?>"><?= $i ?></option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan">Penandatangan</label>
													<div class="col-sm-9">
														<select name="penandatangan" id="penandatangan" class="form-control input-sm">
															<?php foreach ($pamong AS $data): ?>
																<option value="<?= $data['pamong_id']?>" data-jabatan="<?= trim($data['jabatan'])?>"
																	<?= (strpos(strtolower($data['jabatan']),'Kepala Desa') !== false) ? 'selected' : '' ?>>
																	<?= $data['pamong_nama']?>(<?= $data['jabatan']?>)
																</option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
												<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_download" name="form_download" data-dismiss="modal"><i class='fa fa-check'></i> Unduh</button>
											</div>

										</form>
									</div>
								</div>
							</div>
							<div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Cetak Inventaris</h4>
										</div>
										<form action="" target="_blank" class="form-horizontal" method="get">
											<div class="modal-body">
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="tahun_pdf">Tahun</label>
													<div class="col-sm-9">
														<select name="tahun_pdf" id="tahun_pdf" class="form-control select2 input-sm" style="width:100%;">
															<option value="1">Semua Tahun</option>
															<?php for ($i = date("Y"); $i >= date("Y")-30; $i--): ?>
																<option value="<?= $i ?>"><?= $i ?></option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan_pdf">Penandatangan</label>
													<div class="col-sm-9">
														<select name="penandatangan_pdf" id="penandatangan_pdf" class="form-control input-sm">
															<?php foreach ($pamong AS $data): ?>
																<option value="<?= $data['pamong_id']?>" data-jabatan="<?= trim($data['jabatan'])?>"
																	<?= (strpos(strtolower($data['jabatan']),'Kepala Desa') !== false) ? 'selected' : '' ?>>
																	<?= $data['pamong_nama']?>(<?= $data['jabatan']?>)
																</option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
												<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_cetak" name="form_cetak"  data-dismiss="modal"><i class='fa fa-check'></i> Cetak</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	$(document).ready(function(){
		var t = $('#tabel4-laporan').DataTable( {
			'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'info'        : false,
      'autoWidth'   : false,
      "columnDefs"	: [
      	{
          "searchable": false,
          "orderable": false,
          "targets": 0
      	}],
      "order": [[ 1, 'asc' ]],
			"footerCallback": function ( row, data, start, end, display )
			{
				var api = this.api(), data;

				// converting to interger to find total
				var intVal = function ( i )
				{
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};

				// Total over all pages
				var pembelian_sendiri = api
				.column( 3 )
				.data()
				.reduce( function (a, b)
				{
					return intVal(a) + intVal(b);
				}, 0 );

				var pemerintah = api
				.column( 4 )
				.data()
				.reduce( function (a, b)
				{
					return intVal(a) + intVal(b);
				}, 0 );

				var provinsi = api
				.column( 5 )
				.data()
				.reduce( function (a, b)
				{
					return intVal(a) + intVal(b);
				}, 0 );

				var kabupaten = api
				.column( 6 )
				.data()
				.reduce( function (a, b)
				{
					return intVal(a) + intVal(b);
				}, 0 );

				var sumbangan = api
				.column( 7 )
				.data()
				.reduce( function (a, b)
				{
					return intVal(a) + intVal(b);
				}, 0 );

				// Update footer
				$( api.column( 1 ).footer() ).html('Total');
				$( api.column( 3 ).footer() ).html(pembelian_sendiri);
				$( api.column( 4 ).footer() ).html(pemerintah);
				$( api.column( 5 ).footer() ).html(provinsi);
				$( api.column( 6 ).footer() ).html(kabupaten);
				$( api.column( 7 ).footer() ).html(sumbangan);

			}
  	} );
		t.on( 'order.dt search.dt', function ()
		{
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i)
			{
				cell.innerHTML = i+1;
			} );
		} ).draw();

	} );


	$("#form_cetak").click(function( event )
	{
		var link = '<?= site_url("laporan_inventaris/cetak"); ?>'+ '/' + $('#tahun_pdf').val() + '/' + $('#penandatangan_pdf').val();
		window.open(link, '_blank');
		// alert('fell');
  });

	$("#form_download").click(function( event )
	{
		var link = '<?= site_url("laporan_inventaris/download"); ?>'+ '/' + $('#tahun').val() + '/' + $('#penandatangan').val();
		window.open(link, '_blank');
		// alert('fell');
  });
</script>