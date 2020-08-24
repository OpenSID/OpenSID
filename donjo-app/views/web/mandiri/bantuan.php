<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Layanan Mandiri Web > Program Bantuan
 *
 * donjo-app/views/mandiri/bantuan.php,
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

<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/jquery-ui.min.css">
<script src="<?= base_url()?>assets/bootstrap/js/jquery-ui.min.js"></script>

<script>
	function show_kartu_peserta(elem){
		var id = elem.attr('target');
		var title = elem.attr('title');
		var url = elem.attr('href');
		$('#'+id+'').remove();

		$('body').append('<div id="'+id+'" title="'+title+'" style="display:none;position:relative;overflow:scroll;"></div>');

		$('#'+id+'').dialog({
			resizable: true,
			draggable: true,
			width: 500,
			height: 'auto',
			open: function(event, ui) {
				$('#'+id+'').load(url);
			}
		});
		$('#'+id+'').dialog('open');
	}
</script>
<div class="box-header with-border">
	<h3 class="box-title"><b>DAFTAR BANTUAN YANG DITERIMA</b></h3>
</div>
<div class="box-body">
	<?php if ($bantuan_penduduk) : ?>
		<i class="fa fa-caret-right"></i> <b>SASARAN PENDUDUK</b>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center" width="1">No.</th>
						<th class="text-center" width="1">Aksi</th>
						<th>Masa Program</th>
						<th>Nama Program</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($bantuan_penduduk as $no => $bantuan) : ?>
					<tr>
						<td class="text-center"><?= $no + 1; ?></td>
						<td nowrap>
							<?php if($bantuan['no_id_kartu']) : ?>
								<button type="button" target="data_peserta" title="Data Peserta" href="<?= site_url("mandiri_web/kartu_peserta/tampil/$bantuan[id]")?>" onclick="show_kartu_peserta($(this));" class="btn btn-success btn-flat btn-sm" ><i class="fa fa-eye"></i></button>
								<a href="<?= site_url("mandiri_web/kartu_peserta/unduh/$bantuan[id]")?>" class="btn bg-black btn-flat btn-sm" title="Kartu Peserta" <?php empty($bantuan['kartu_peserta']) and print('disabled')?>><i class="fa fa-download"></i></a>
							<?php endif; ?>
						</td>
						<td nowrap><?= fTampilTgl($bantuan["sdate"], $bantuan["edate"]);?></td>
						<td><?= $bantuan['nama']?></td>
						<td width="60%"><?= $bantuan["ndesc"];?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<span>Anda tidak terdaftar dalam program bantuan apapun</span>
	<?php endif; ?>
</div>

