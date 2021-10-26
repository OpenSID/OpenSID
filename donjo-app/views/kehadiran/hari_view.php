<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View Rekap Hari
 *
 * donjo-app/views/kehadiran/hari_view.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>
<style>
.info-red{background:red;color:white;text-align:center}
#divTblTgl{width:99%;margin:auto}
#divTblTgl tbody tr td:nth-child(1){text-align:right}
#divTblTgl tbody tr td:nth-child(2),#divTblTgl tbody tr td:nth-child(3){text-align:center}
#divTblTgl thead tr th{text-align:center;}
<?=$this->load->view('kehadiran/datatable_css',[],1);?>
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
	<section class="content" id="maincontent" >
<?php if (ENVIRONMENT == 'development' && $_SERVER['SERVER_PORT']=='229') : 
echo "<!--pre>".print_r($_SERVER,1)."</pre-->";
	$form_upload=site_url('set_hari/upload');
?>
		<div class="box box-info  " style='display:none'>
			<form id="validasi" action="<?=$form_upload;?>" method="POST" enctype="multipart/form-data" class="form-horizontal ">
			<div class="box-body">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="year">FILE</label>
					<div class="col-sm-4">
						<input type='file' name='upload' />Masukkan file disini
					</div>
					<div class="col-sm-3">
						<a href='<?=site_url('set_hari/upload_contoh');?>' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Contoh</a>
					</div>
				</div>
			</div>
			<div class='box-footer'>
				<button id='showTanggalMerah' 
					class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'>
					<i class='fa fa-check'></i> upload
				</button>
			</div>
			</form>
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
<?php endif; ?>
		<div class="box box-info">
			<div class='box-header with-border'>
				<a href="<?= site_url("set_hari/edit_tgl")?>?tgl=0" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  data-title="Tambah Tanggal Merah"  data-remote="false" data-toggle="modal" data-target="#modalBox" >
					<i class="fa fa-plus"></i>Tambah Hari Baru
				</a>
			</div>
			<div class='box-body'>
				<div id='divTblTgl' >
				*) untuk pencarian, Gunakan kotak pencarian dan masukkan Minimal 4 huruf.
				<!-- class="table table-bordered table-striped dataTable table-hover" -->
				<table id='tblTgl' class='dataTable datatable-striped'>
					<thead>
						<tr class='bg-gray color-palette'>
							<th width='10%'>NO</th>							
							<th width='15%'>Aksi</th>
							<th width='20%'>Tanggal</th>
							<th>Keterangan</th>
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