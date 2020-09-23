<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Bantuan
 *
 * donjo-app/views/statistik/peserta_bantuan.php
 *
 */
/*
 *  File ini bagian dari:
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
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>

<section class="content" id="maincontent">
	<div class="row">
		<div class="col-md-12">
			<input id="stat" type="hidden" value="<?=$lap?>">
			<div class="box box-info">
				<div class="box-header with-border" style="margin-bottom: 15px;">
					<h3 class="box-title">Daftar <?= $heading ?></h3>
				</div>
				<div style="margin-right: 1rem; margin-left: 1rem;">
					<div class="table-responsive">
						<table class="table table-striped table-bordered" id="peserta_program">
							<thead>
								<tr>
									<th>No</th>
									<th>Program</th>
									<th>Nama Peserta</th>
									<th>Alamat</th>
								</tr>
							</thead>
							<tfoot>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
		var url = "<?= site_url($this->controller.'/ajax_peserta_program_bantuan')?>";

		table = $('#peserta_program').DataTable({
			'processing': true,
			'serverSide': true,
			"pageLength": 10,
			'order': [],
			"ajax": {
				"url": url,
				"type": "POST",
				"data": {stat: $('#stat').val()}
			},
			//Set column definition initialisation properties.
			"columnDefs": [
			{
					"targets": [ 0, 3 ], //first column / numbering column
					"orderable": false, //set not orderable
			},
			],
			'language': {
				'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
			},
			'drawCallback': function (){
				$('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
			}
		});
	} );
</script>
