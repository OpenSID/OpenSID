<script src="<?= base_url('assets/js/select2/select2.js') ?>"></script>
<link href="<?= base_url('assets/js/select2/select2.css') ?>"rel="stylesheet" />
<script src="<?= base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery-validation-1.17.0/dist/jquery.validate.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery-validation-1.17.0/dist/jquery.validate.min.js') ?>"></script>
<style>
	#footer
	{
		color: #f83535;
		text-shadow: 1px 1px 0.5px #444;
		padding: 8px;
		text-align: center;
		position: relative;
		bottom: 0px;
		width: 100%;
		background: #eaa852;
		height: 34px;
		position: fixed;
	}
</style>
<div id="myModalExcel" class="modal fade" role="dialog" style="padding-top:30px;">
  <div class="modal-dialog">

    <!-- Modal content-->
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

    <!-- Modal content-->
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
				$data['data'] = 1;
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
						Daftar Keseluruhan Asset Desa
					</div>
					<div class="panel-body">
						<div class="pull-right">
              				<!-- <a class="btn btn-primary" href="<?= site_url('inventaris_laporan/form'); ?>" style="color:white;">
								<i class="fa fa-plus"></i> Tambah
							</a> -->
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
								<td>
								Informasi mengenai segala yang menyangkut dengan tanah
								(dalam hal ini tanah yang digunakan dalam instansi tersebut).
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_tanah.asal) as total');
										$this->db->where('inventaris_tanah.visible',1);
										$this->db->where('inventaris_tanah.status',0);
										$this->db->where('inventaris_tanah.asal','Pembelian Sendiri');
										$result = $this->db->get('inventaris_tanah')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_tanah.asal) as total');
										$this->db->where('inventaris_tanah.visible',1);
										$this->db->where('inventaris_tanah.status',0);
										$this->db->where('inventaris_tanah.asal','Bantuan Pemerintah');
										$result = $this->db->get('inventaris_tanah')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_tanah.asal) as total');
										$this->db->where('inventaris_tanah.visible',1);
										$this->db->where('inventaris_tanah.status',0);
										$this->db->where('inventaris_tanah.asal','Bantuan Provinsi');
										$result = $this->db->get('inventaris_tanah')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_tanah.asal) as total');
										$this->db->where('inventaris_tanah.visible',1);
										$this->db->where('inventaris_tanah.status',0);
										$this->db->where('inventaris_tanah.asal','Bantuan Kabupaten');
										$result = $this->db->get('inventaris_tanah')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_tanah.asal) as total');
										$this->db->where('inventaris_tanah.visible',1);
										$this->db->where('inventaris_tanah.status',0);
										$this->db->where('inventaris_tanah.asal','Sumbangan');
										$result = $this->db->get('inventaris_tanah')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<div class="btn-group" role="group" aria-label="...">
										<a href="<?= base_url('index.php/inventaris'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Peralatan dan Mesin</td>
								<td>Informasi mengenai peralatan dan mesin</td>
								<td>
									<?php
										$this->db->select('count(inventaris_peralatan.asal) as total');
										$this->db->where('inventaris_peralatan.visible',1);
										$this->db->where('inventaris_peralatan.status',0);
										$this->db->where('inventaris_peralatan.asal','Pembelian Sendiri');
										$result = $this->db->get('inventaris_peralatan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_peralatan.asal) as total');
										$this->db->where('inventaris_peralatan.visible',1);
										$this->db->where('inventaris_peralatan.status',0);
										$this->db->where('inventaris_peralatan.asal','Bantuan Pemerintah');
										$result = $this->db->get('inventaris_peralatan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_peralatan.asal) as total');
										$this->db->where('inventaris_peralatan.visible',1);
										$this->db->where('inventaris_peralatan.status',0);
										$this->db->where('inventaris_peralatan.asal','Bantuan Provinsi');
										$result = $this->db->get('inventaris_peralatan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_peralatan.asal) as total');
										$this->db->where('inventaris_peralatan.visible',1);
										$this->db->where('inventaris_peralatan.status',0);
										$this->db->where('inventaris_peralatan.asal','Bantuan Kabupaten');
										$result = $this->db->get('inventaris_peralatan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_peralatan.asal) as total');
										$this->db->where('inventaris_peralatan.visible',1);
										$this->db->where('inventaris_peralatan.status',0);
										$this->db->where('inventaris_peralatan.asal','Sumbangan');
										$result = $this->db->get('inventaris_peralatan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<div class="btn-group" role="group" aria-label="...">
										<a href="<?= base_url('index.php/inventaris_peralatan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Gedung dan Bangunan</td>
								<td>Informasi mengenai gedung dan bangunan yang dimiliki.</td>
								<td>
									<?php
										$this->db->select('count(inventaris_gedung.asal) as total');
										$this->db->where('inventaris_gedung.visible',1);
										$this->db->where('inventaris_gedung.status',0);
										$this->db->where('inventaris_gedung.asal','Pembelian Sendiri');
										$result = $this->db->get('inventaris_gedung')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_gedung.asal) as total');
										$this->db->where('inventaris_gedung.visible',1);
										$this->db->where('inventaris_gedung.status',0);
										$this->db->where('inventaris_gedung.asal','Bantuan Pemerintah');
										$result = $this->db->get('inventaris_gedung')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_gedung.asal) as total');
										$this->db->where('inventaris_gedung.visible',1);
										$this->db->where('inventaris_gedung.status',0);
										$this->db->where('inventaris_gedung.asal','Bantuan Provinsi');
										$result = $this->db->get('inventaris_gedung')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_gedung.asal) as total');
										$this->db->where('inventaris_gedung.visible',1);
										$this->db->where('inventaris_gedung.status',0);
										$this->db->where('inventaris_gedung.asal','Bantuan Kabupaten');
										$result = $this->db->get('inventaris_gedung')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_gedung.asal) as total');
										$this->db->where('inventaris_gedung.visible',1);
										$this->db->where('inventaris_gedung.status',0);
										$this->db->where('inventaris_gedung.asal','Sumbangan');
										$result = $this->db->get('inventaris_gedung')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<div class="btn-group" role="group" aria-label="...">
										<a href="<?= base_url('index.php/inventaris_gedung'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Jalan Irigasi dan Jaringan</td>
								<td>Informasi mengenai jaringan, seperti listrik atau Internet.</td>
								<td>
									<?php
										$this->db->select('count(inventaris_jalan.asal) as total');
										$this->db->where('inventaris_jalan.visible',1);
										$this->db->where('inventaris_jalan.status',0);
										$this->db->where('inventaris_jalan.asal','Pembelian Sendiri');
										$result = $this->db->get('inventaris_jalan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_jalan.asal) as total');
										$this->db->where('inventaris_jalan.visible',1);
										$this->db->where('inventaris_jalan.status',0);
										$this->db->where('inventaris_jalan.asal','Bantuan Pemerintah');
										$result = $this->db->get('inventaris_jalan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_jalan.asal) as total');
										$this->db->where('inventaris_jalan.visible',1);
										$this->db->where('inventaris_jalan.status',0);
										$this->db->where('inventaris_jalan.asal','Bantuan Provinsi');
										$result = $this->db->get('inventaris_jalan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_jalan.asal) as total');
										$this->db->where('inventaris_jalan.visible',1);
										$this->db->where('inventaris_jalan.status',0);
										$this->db->where('inventaris_jalan.asal','Bantuan Kabupaten');
										$result = $this->db->get('inventaris_jalan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_jalan.asal) as total');
										$this->db->where('inventaris_jalan.visible',1);
										$this->db->where('inventaris_jalan.status',0);
										$this->db->where('inventaris_jalan.asal','Sumbangan');
										$result = $this->db->get('inventaris_jalan')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<div class="btn-group" role="group" aria-label="...">
										<a href="<?= base_url('index.php/inventaris_gedung/view/'.$data->id); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Asset Tetap Lainnya</td>
								<td>Informasi mengenai aset tetap seperti barang habis pakai contohnya buku-buku.</td>
								<td>
									<?php
										$this->db->select('count(inventaris_asset.asal) as total');
										$this->db->where('inventaris_asset.visible',1);
										$this->db->where('inventaris_asset.status',0);
										$this->db->where('inventaris_asset.asal','Pembelian Sendiri');
										$result = $this->db->get('inventaris_asset')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_asset.asal) as total');
										$this->db->where('inventaris_asset.visible',1);
										$this->db->where('inventaris_asset.status',0);
										$this->db->where('inventaris_asset.asal','Bantuan Pemerintah');
										$result = $this->db->get('inventaris_asset')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_asset.asal) as total');
										$this->db->where('inventaris_asset.visible',1);
										$this->db->where('inventaris_asset.status',0);
										$this->db->where('inventaris_asset.asal','Bantuan Provinsi');
										$result = $this->db->get('inventaris_asset')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_asset.asal) as total');
										$this->db->where('inventaris_asset.visible',1);
										$this->db->where('inventaris_asset.status',0);
										$this->db->where('inventaris_asset.asal','Bantuan Kabupaten');
										$result = $this->db->get('inventaris_asset')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_asset.asal) as total');
										$this->db->where('inventaris_asset.visible',1);
										$this->db->where('inventaris_asset.status',0);
										$this->db->where('inventaris_asset.asal','Sumbangan');
										$result = $this->db->get('inventaris_asset')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<div class="btn-group" role="group" aria-label="...">
										<a href="<?= base_url('index.php/inventaris_asset'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
									</div>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>Kontruksi Dalam Pengerjaan</td>
								<td>Informasi mengenai bangunan yang masih dalam pengerjaan.</td>
								<td>
									<?php
										$this->db->select('count(inventaris_kontruksi.asal) as total');
										$this->db->where('inventaris_kontruksi.visible',1);
										$this->db->where('inventaris_kontruksi.status',0);
										$this->db->where('inventaris_kontruksi.asal','Pembelian Sendiri');
										$result = $this->db->get('inventaris_kontruksi')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_kontruksi.asal) as total');
										$this->db->where('inventaris_kontruksi.visible',1);
										$this->db->where('inventaris_kontruksi.status',0);
										$this->db->where('inventaris_kontruksi.asal','Bantuan Pemerintah');
										$result = $this->db->get('inventaris_kontruksi')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_kontruksi.asal) as total');
										$this->db->where('inventaris_kontruksi.visible',1);
										$this->db->where('inventaris_kontruksi.status',0);
										$this->db->where('inventaris_kontruksi.asal','Bantuan Provinsi');
										$result = $this->db->get('inventaris_kontruksi')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_kontruksi.asal) as total');
										$this->db->where('inventaris_kontruksi.visible',1);
										$this->db->where('inventaris_kontruksi.status',0);
										$this->db->where('inventaris_kontruksi.asal','Bantuan Kabupaten');
										$result = $this->db->get('inventaris_kontruksi')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<?php
										$this->db->select('count(inventaris_kontruksi.asal) as total');
										$this->db->where('inventaris_kontruksi.visible',1);
										$this->db->where('inventaris_kontruksi.status',0);
										$this->db->where('inventaris_kontruksi.asal','Sumbangan');
										$result = $this->db->get('inventaris_kontruksi')->row();
										echo (!empty($result->total) ? $result->total : '0');
									?>
								</td>
								<td>
									<div class="btn-group" role="group" aria-label="...">
										<a href="<?= base_url('index.php/inventaris_kontruksi'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
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

	$(document).ready(function()
	{
		$("#penandatangan").select2({ width: '100%' });
		var t = $('#example').DataTable(
		{
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