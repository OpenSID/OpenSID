<div id="myModalExcel" class="modal fade" role="dialog" style="padding-top:30px;">
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
						<select name="tahun" id="tahun" class="form-control">
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
						<select name="penandatangan" id="penandatangan" class="form-control">
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
				<button type="submit" class="btn btn-primary pull-right"  id="form_download" name="form_download"  data-dismiss="modal">Unduh</button>
			</div>
		</form>
    </div>

  </div>
</div>
<div id="myModal" class="modal fade" role="dialog" style="padding-top:30px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cetak Inventaris</h4>
      </div>
	  	<form action="" target="_blank" class="form-horizontal" method="get" >
			<div class="modal-body">
				<div class="form-group">
					<label class="col-sm-2 control-label required" style="text-align:left;" for="tahun_pdf">Tahun</label>
					<div class="col-sm-9">
						<select name="tahun_pdf" id="tahun_pdf" class="form-control">
							<option value="1">Semua Tahun</option>
							<?php for ($i=date("Y"); $i>=date("Y")-30; $i--): ?>
								<option value="<?= $i ?>"><?= $i ?></option>
							<?php endfor; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan_pdf">Penandatangan</label>
					<div class="col-sm-9">
						<select name="penandatangan_pdf" id="penandatangan_pdf" class="form-control">
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
				<button type="submit" class="btn btn-primary pull-right"  id="form_cetak" name="form_cetak"  data-dismiss="modal">Cetak</button>
			</div>
		</form>
    </div>
  </div>
</div>

<div id="row">
<div class="col-lg-2">
	<div class="panel panel-default">
		<div class="panel-heading">Menu</div>
		<div class="panel-body">
			<?php
				$data['data'] = 2;
				$this->load->view('inventaris/laporan/menu_kiri.php',$data);
			?>
		</div>
	</div>
</div>
<div class="col-lg-10">
	<div id="container">
		<form id="mainform" name="mainform" action="" method="post">
		  <div class="ui-layout-north panel">
				<div class="panel panel-default">
					<div class="panel-heading">
						Daftar Asset Yang Dihapus
					</div>
					<div class="panel-body">
						<div class="pull-right">
		        		</div>
						<div class="pull-left">
							<a type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">
								<i class="fa fa-file-pdf-o"></i> Cetak
							</a>
							<a type="button" class="btn btn-success" data-toggle="modal" data-target="#myModalExcel">
								<i class="fa fa-file-excel-o"></i> Unduh Excel
							</a>
						</div>
					</div>
					<div class="panel-body">
					<table id="example" class="stripe cell-border table" class="grid">
						<thead style="background-color:#f9f9f9;" >
							<tr>
								<th class="text-center" rowspan="3">No</th>
								<th class="text-center" rowspan="3">Jenis Barang</th>
								<th class="text-center" width="40%" rowspan="3">Keterangan</th>
								<th class="text-center" colspan="5">Asal barang</th>
								<th class="text-center" rowspan="3" >Aksi</th>
							</tr>
							<tr>
								<th class="text-center" style="text-align:center;" rowspan="2">Dibeli Sendiri</th>
								<th class="text-center" style="text-align:center;" colspan="3">Bantuan</th>
								<th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
							</tr>
							<tr>
								<th class="text-center" style="text-align:center;">Pemerintah</th>
								<th class="text-center" style="text-align:center;">Provinsi</th>
								<th class="text-center" style="text-align:center;">Kabupaten</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td>Tanah Kas Desa</td>
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
										<a href="<?= base_url('index.php/inventaris/mutasi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Peralatan dan Mesin</td>
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
										<a href="<?= base_url('index.php/inventaris_peralatan/mutasi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Gedung dan Bangunan</td>
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
										<a href="<?= base_url('index.php/inventaris_gedung/mutasi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Jalan Irigasi dan Jaringan</td>
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
										<a href="<?= base_url('index.php/inventaris_jalan/mutasi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Asset Tetap Lainnya</td>
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
										<a href="<?= base_url('index.php/inventaris_asset/mutasi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Kontruksi Dalam Pengerjaan</td>
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
										<a href="<?= base_url('index.php/inventaris_kontruksi/mutasi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>

						</tbody>
						<tfoot>
							<tr>
								<th colspan="3" style="text-align:right"></th>
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
			</form>
	</div>
</div>

<script  TYPE='text/javascript'>
	function deleteItem($id)
	{
		swal(
		{
			title: "Apakah Anda Yakin?",
			text: "Setelah dihapus, Data hanya dapat dipulihkan di database!!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) =>
		{
			if (willDelete)
			{
				swal("Data berhasil dihapus!",
				{
					icon: "success",
				});

				window.location = "api_inventaris_laporan/delete/" + $id;
			}
			else
			{
				swal("Data tidak berhasil dihapus!");
			}
		});
	}

	$(document).ready(function() {
		var t = $('#example').DataTable( {
			scrollY					: '100vh',
			scrollCollapse			: true,
			autoWidth				: true,
			"searching"				: false,
			"paging"				: false,
      	"columnDefs": [
      	{
          	"searchable": false,
          	"orderable": false,
          	"targets": 0
      	} ],
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
		var link = '<?= site_url("laporan_inventaris/cetak_mutasi"); ?>'+ '/' + $('#tahun_pdf').val() + '/' + $('#penandatangan_pdf').val();
		window.open(link, '_blank');
  });

	$("#form_download").click(function( event )
	{
		var link = '<?= site_url("laporan_inventaris/download_mutasi"); ?>'+ '/' + $('#tahun').val() + '/' + $('#penandatangan').val();
		window.open(link, '_blank');
  });

</script>