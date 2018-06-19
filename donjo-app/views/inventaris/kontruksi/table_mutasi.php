<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>

<style>
	#footer {
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

<div id="row">
<div class="col-lg-2">
	<div class="panel panel-default">
		<div class="panel-heading">Menu</div>
		<div class="panel-body">
			<?php
				$data['data'] = 2;
				$this->load->view('inventaris/kontruksi/menu_kiri.php',$data);
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
						Mutasi Inventaris kontruksi Dalam Pengerjaan
					</div>
					<div class="panel-body">
						<!-- <div class="pull-right">
              				<a class="btn btn-primary" href="#" style="color:white;">
								<i class="fa fa-plus"></i> Tambah
							</a>
		                </div> -->
						<div class="pull-left">
							<a ng-click="exportExcel()" class="btn btn-success">
									<i class="fa fa-file-excel-o"></i> Unduh Excel
							</a>
							<a ng-click="exportExcel()" class="btn btn-danger">
									<i class="fa fa-file-pdf-o"></i> Cetak
							</a>
                        </div>
					</div>
					<div class="panel-body">
					<table id="example" class="stripe cell-border table" class="grid">
						<thead style="background-color:#f9f9f9;" >
							<!-- <tr>
									<th rowspan="3">No</th>
									<th rowspan="3">Nama Barang</th>
									<th colspan="2">Nomor</th>
									<th rowspan="3">Luas (M<sup>2</sup>)</th>
									<th rowspan="3">Tahun Pengadaan</th>
									<th rowspan="3">Letak/Alamat</th>
									<th colspan="3">Status Tanah</th>
									<th rowspan="3">Pengguna</th>
									<th rowspan="3">Asal Usul</th>
									<th rowspan="3">Harga (Rp)</th>
									<th rowspan="3">Keterangan</th>
							</tr>
							<tr>
									<th rowspan="2">Kode Barang</th>
									<th rowspan="2">Register</th>
									<th rowspan="2">Hak</th>
									<th colspan="2">Sertifikat</th>
							</tr>
							<tr>
									<th>Tanggal</th>
									<th>Nomor</th>
							</tr> -->

							<tr>
									<th class="text-center">No</th>
									<th class="text-center">Nama Barang</th>
									<th class="text-center">Kode Barang</th>
									<th class="text-center">Tahun Pengadaan</th>
									<th class="text-center">Tanggal Mutasi</th>
									<th class="text-center">Jenis Mutasi</th>
									<th class="text-center" width="300px">Keterangan</th>
									<th class="text-center" width="100px">Aksi</th>
							</tr>
							<!-- <tr>

									<th rowspan="1">Register</th> -->
									<!-- <th class="text-center" style="text-align:center;" rowspan="1">Hak</th>
									<th class="text-center" style="text-align:center;" rowspan="1">Nomor Sertifikat</th>
							</tr> -->
							<!-- <tr>
									<th>Tanggal</th>
									<th>Nomor</th>
							</tr> -->
						</thead>
						<tbody>
							<?php
								for($i=0; $i<20; $i++){
							?>
							<tr>
								<td>1</td>
								<td align="center">asd</td>
								<td align="center">dasd</td>
								<td align="center">asd</td>
								<td align="center">asd</td>
								<td align="center">dasd</td>
								<td align="center">asd</td>
								<td align="center">
									<div class="btn-group" role="group" aria-label="...">
										<a href="http://localhost/sidak//pemerintahan/data_warga/view/1" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
										<a href="http://localhost/sidak//pemerintahan/data_warga/update/1" title="Edit Data"  type="button" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> </a>
									</div>
								</td>
							</tr>
							<?php
								}
							?>
						</tbody>
					</table>
					</div>
					</div>
			</form>
	</div>
</div>

<script  TYPE='text/javascript'>
	$(document).ready(function() {
			$('#example').DataTable( {
					scrollY					: '100vh',
					scrollCollapse	: true,
					autoWidth				: true
			} );
	} );
</script>