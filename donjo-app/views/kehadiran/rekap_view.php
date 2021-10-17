<style>
.info-red{background:red;color:white;text-align:center}
#divTblTgl{width:99%;margin:auto}
#divTblTgl tbody tr td:nth-child(1){text-align:right}
#divTblTgl tbody tr td:nth-child(2),#divTblTgl tbody tr td:nth-child(4){text-align:center}
#divTblTgl thead tr th{text-align:center;font-size:medium}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Rekap Kehadiran</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="#"> Kehadiran</a></li>
			<li class="active">Rekap Kehadiran</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
<?php if ($_SERVER['SERVER_PORT']=='229') : 
echo "<!--pre>".print_r($_SERVER,1)."</pre-->";
	$form_upload=site_url('set_hari/upload');
?>
		<div class="box box-info  ">

			<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal ">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="year">Pencarian</label>
						<div class="col-sm-4">
							<select style='display:none' id="month" name="bulan" class="form-control input-sm inp-bulan" ></select>
							<select id="search_type" name="type" class="form-control input-sm" >
								<option>Silahkan pilih</option>
								<option value='pamong'>Pamong</option>
							</select> 
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm"> 
								<input id='search_value' name='search' value=''  class="form-control input-sm tgl"  />
							</div>
							
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id='date' name='date' value=''  class="form-control input-sm tgl"  />
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
<?php endif; ?>
		<div class="box box-info">
			<div class='box-header with-border'>
 
			</div>
			<div class='box-body'>
				<div id='showTanggal' style='padding:30px;text-align:middle'></div>
				<div id='divTblTgl'>
				<table id='tblTgl'  class="table table-bordered table-striped dataTable table-hover">
					<thead>
						<tr class='bg-gray color-palette'>
							<th width='10%'>NO</th>
							<th width='20%'>Tanggal</th>
							<th>Pamong</th>							
							<th width='15%'>Jam Masuk</th>			
							<th width='15%'>Jam Keluar</th>
						</tr>
					</thead>
					<tbody>
						 
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
$this->load->view('kehadiran/js/rekap_js');