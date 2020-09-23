<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Database
 *
 * donjo-app/views/export/exp.php
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<div class="tab-content">
	<div class="tab-pane <?= jecho($act_tab, 'export/exp', 'active'); ?>">
		<div class="box-header with-border">
			<h3 class="box-title"><strong>Ekspor Data <?= ucwords($this->setting->sebutan_desa); ?></strong></h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-8">
					<table class="table table-striped table-hover">
						<tr>
							<td class="col-sm-10">Ekspor Data Penduduk (Format .xlsx untuk di impor ke database SID melalui menu Impor Database)</td>
							<td class="col-sm-2">
								<a href="<?= site_url("database")?>/export_excel" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a>
							</td>
						</tr>
						<tr>
							<td class="col-sm-10">Ekspor Data Penduduk untuk diimpor ke database OpenDK (.xlsx)</td>
							<td class="col-sm-2">
								<a href="<?= site_url("database")?>/export_excel/opendk" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a>
							</td>
						</tr>
						<tr>
							<td>Ekspor Data Dasar Kependudukan (.sid)</td>
							<td>
								<a href="<?= site_url("database")?>/export_dasar" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a>
							</td>
						</tr>
						<tr>
							<td>Ekspor Data CSV (.csv)</td>
							<td>
								<a href="<?= site_url("database")?>/export_csv" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
