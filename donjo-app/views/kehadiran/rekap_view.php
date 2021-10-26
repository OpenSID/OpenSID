<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View Rekap Kehadiran
 *
 * donjo-app/views/kehadiran/rekap_view.php
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
#divTblRekap{width:99%;margin:auto}
#divTblRekap tbody tr td:nth-child(1){text-align:right}
#divTblRekap tbody tr td:nth-child(2),#divTblRekap tbody tr td:nth-child(4),#divTblRekap tbody tr td:nth-child(5){text-align:center}
#divTblRekap thead tr th{text-align:center;}
<?=$this->load->view('kehadiran/datatable_css',[],1);?>
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
		<div class="box box-info  " style='display:none'>

			<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal ">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="year">Pencarian</label>
						<div class="col-sm-3">
							<select style='display:none' id="month" name="bulan" class="form-control input-sm inp-bulan" ></select>
							<select id="search_type" name="type" class="form-control input-sm" >
								<option>Silahkan pilih</option>
								<option value='pamong'>Pamong</option>
							</select> 
						</div>
						<div class="col-sm-5">
							<div class="input-group input-group-sm"> 
								<input id='search_value' name='search' value=''  class="form-control input-sm"  />
							</div>
							
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="year">
							Rentang Tanggal
						</label>
						<div class="col-sm-4">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id='date_start' name='date' value='<?=date("01-m-Y");?>'  class="form-control input-sm tgl"  />
							</div>
							
						</div>
						<div class="col-sm-4">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id='date_end' name='date' value='<?=date("t-m-Y");?>'  class="form-control input-sm tgl"  />
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
				<div id='divTblRekap'>
				*) untuk pencarian, Gunakan kotak pencarian dan masukkan Minimal 4 huruf.
				<table id='tblRekap'  class="dataTable datatable-striped">
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