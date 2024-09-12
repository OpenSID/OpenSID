<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Bantuan
 *
 * donjo-app/views/fmandiri/bantuan.php
 */

/*
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
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<link rel="stylesheet" href="<?= asset('bootstrap/css/jquery-ui.min.css') ?>">
<script src="<?= asset('bootstrap/js/jquery-ui.min.js') ?>"></script>
<script>
	function show_kartu_peserta(elem) {
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
<div class="box box-solid">
	<div class="box-header with-border bg-aqua">
		<h4 class="box-title">Bantuan</h4>
	</div>
	<div class="box-body box-line">
		<h4><b>BANTUAN PENDUDUK</b></h4>
	</div>
	<div class="box-body box-line">
		<div class="table-responsive">
		<table class="table table-bordered table-hover table-data datatable-polos">
				<thead>
					<tr class="judul">
						<th>No</th>
						<th>Aksi</th>
						<th width="20%">Waktu / Tanggal</th>
						<th width="20%">Nama Program</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($bantuan_penduduk):
                        foreach ($bantuan_penduduk as $key => $item): ?>
							<tr>
								<td class="padat"><?= ($key + 1); ?></td>
								<td class="padat">
									<?php if ($item['no_id_kartu']) : ?>
										<button type="button" target="data_peserta" title="Data Peserta" href="<?= site_url(MANDIRI . "/bantuan/kartu_peserta/tampil/{$item['id']}")?>" onclick="show_kartu_peserta($(this));" class="btn btn-success btn-sm" ><i class="fa fa-eye"></i></button>
										<a href="<?= site_url(MANDIRI . "/bantuan/kartu_peserta/unduh/{$item['id']}")?>" class="btn bg-black btn-sm" title="Kartu Peserta" <?php if (empty($item['kartu_peserta'])) {
                                            echo 'disabled';
                                        }?> ><i class="fa fa-download"></i></a>
									<?php endif; ?>
								</td>
								<td><?= fTampilTgl($item['sdate'], $item['edate']); ?></td>
								<td><?= $item['nama']; ?></td>
								<td><p align="justify"><?= $item['ndesc']; ?></p></td>
							</tr>
						<?php endforeach;
                    else: ?>
						<tr>
							<td class="text-center" colspan="5">Data tidak tersedia</td>
						</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
