<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View Edit Hari Merah
 *
 * donjo-app/views/kehadiran/hari_edit_view.php
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

//hari_edit
$options = array(
        '0' 	=> 'Hari Biasa',
        '1'		=> 'Hari Libur',
      //  '2'         => 'Libur bersama',
        '9'		=> 'Lain-lain',
);

?>
<section class="content" id="maincontent">
	<div class="box box-info">
		<form id="frm_tgl" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<input type='hidden' name='action' value='update_tgl' />
			<div class="box-body">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="year">Tanggal</label>
					<div class="col-sm-9">
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input name='tgl_merah' value='<?=@$hari['tgl_merah'];?>'  class="  input-sm tgl"
							<?=isset($hari['tgl_merah']) && $hari['tgl_merah'] != '0000-00-00'?'readonly' : NULL;?>   />
						</div>
					</div> 
				</div>
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="year">Status </label>
						<div class="col-sm-9">
							<!--select name="tipe" class="form-control input-sm" ></select-->
							<?=form_dropdown('status', $options, @$hari['status']);?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="year">Keterangan </label> 
						<div class="col-sm-9">
							<input name='detail' value='<?=@$hari['detail'];?>' class="form-control input-sm " />
						</div>
					</div>
				</div>
			</div>
			<div class='box-footer'>
				<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Reset</button>
				<button id='showTanggalMerah' type='button' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm' onclick='hari_edit()'><i class='fa fa-check'></i> Simpan</button>
			</div>
		</form>
	</div>
</section>

<script>

$(document).ready(function()
{
	$('.tgl').datetimepicker(
	{
		format: 'YYYY-MM-DD',
		useCurrent: false,
		locale:'id'
	});
	
});
</script>