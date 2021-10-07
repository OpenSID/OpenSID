<style>
.info-red{background:red;color:white;text-align:center}
#divTblTgl{width:99%;margin:auto}
#divTblTgl tbody tr td:nth-child(1){text-align:right}
#divTblTgl tbody tr td:nth-child(2),#divTblTgl tbody tr td:nth-child(4){text-align:center}
#divTblTgl thead tr th{text-align:center;font-size:medium}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Tanggal Merah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="#"> Kehadiran</a></li>
			<li class="active">Pengaturan Tanggal Merah</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info hidden">
			
			<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal ">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="year">Pencarian</label>
						<div class="col-sm-4">
							<select style='display:none' id="month" name="bulan" class="form-control input-sm inp-bulan" ></select>
							<select id="date_type" name="type" class="form-control input-sm" >
								<option>Silahkan pilih</option>
								<option value='date'>Tanggal</option>
							</select>
							<select style='display:none' id="year" name="tahun" class="form-control input-sm inp-tahun"  placeholder="Tahun"></select>
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id='date_start' name='date_start' value='<?=date("Y-m-d");?>'  class="form-control input-sm tgl"  />
							</div>
							
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id='date_end' name='date_end' value='<?=date("Y-m-d",strtotime("+1 month"));?>'  class="form-control input-sm tgl"  />
							</div>
							
						</div>
					</div>
				</div>
				<div class='box-footer'>
					<!--button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Reset</button-->
					<button id='showTanggalMerah' type='button' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Lihat</button>
				</div>
			</form>
		</div>
		<div class="box box-info">
			<div class='box-header with-border'>
				<a href="<?= site_url("set_hari/edit_tgl")?>?tgl=0" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  data-title="Tambah Tanggal Merah"  data-remote="false" data-toggle="modal" data-target="#modalBox" >
					<i class="fa fa-plus"></i>Tambah Hari Baru
				</a>
			</div>
			<div class='box-body'>
				<div id='showTanggal' style='padding:30px;text-align:middle'></div>
				<div id='divTblTgl'>
				<table id='tblTgl'  class="table table-bordered table-striped dataTable table-hover">
					<thead>
						<tr class='bg-gray color-palette'>
							<th width='10%'>NO</th>
							<th width='20%'>Tanggal</th>
							<th>Keterangan</th>							
							<th width='15%'>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr style='display:none'>
							<td>1</td>
							<td><a><i class="fa fa-edit"></i></a></a></td>
							<td><?=date("Y-m-d");?></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
				</div>
			</div> 
		</div>
	</section>
</div>


<!-- Untuk menampilkan modal bootstrap umum -->
<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'> Pengaturan Pengguna</h4>
			</div>
			<div class="fetched-data"></div>
		</div>
	</div>
</div>

<!-- Untuk menampilkan pengaturan -->
<?php 
$this->load->view('kehadiran/js/tgl_merah_js');