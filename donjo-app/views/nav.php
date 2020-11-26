<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk menu navigasi utama di komponen Admin
 *
 * donjo-app/views/nav.php
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

<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<a href="<?=site_url()?>first" class="brand-link logo-switch">
		<span class="brand-image-xl logo-xs"><b>SID</b></span>
		<span class="brand-image-xs logo-xl" style="left: 80px"><b>OpenSID</b></span>
	</a>

	<div class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?= gambar_desa($desa['logo']); ?>" class="rounded-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<strong><?= ucwords($this->setting->sebutan_desa . " " . $desa['nama_desa']); ?></strong>
				</br>
				<?php
					$seb_kec = $this->setting->sebutan_kecamatan;
					$nam_kec = $desa['nama_kecamatan'];
					$seb_kab = $this->setting->sebutan_kabupaten;
					$nam_kab = $desa['nama_kabupaten'];
				?>
				<?php	if (strlen($nam_kec)<=12 AND strlen($nam_kab)<=12): ?>
					<?= ucwords($seb_kec . " ".$nam_kec); ?>
					</br>
					<?= ucwords($seb_kab." ".$nam_kab); ?>
				<?php	else: ?>
					<?= ucwords(substr($seb_kec, 0, 3) . ". " . $nam_kec); ?>
					</br>
					<?= ucwords(substr($seb_kab, 0, 3).". " . $nam_kab); ?>
				<?php	endif; ?>
			</div>
		</div>
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent sidebar-menu text-sm" data-widget="treeview" role="menu" data-accordion="false">
				<?php foreach ($modul AS $mod): ?>
					<?php if ($this->CI->cek_hak_akses('b', $mod['url'])): ?>
						<?php if (count($mod['submodul'])==0): ?>
							<li class="nav-item <?= jecho($this->modul_ini, $mod['id'], 'active'); ?>">
								<a href="<?= site_url("$mod[url]"); ?>" class="nav-link">
									<i class="nav-icon text-sm fa <?= $mod['ikon']; ?> <?= jecho($this->modul_ini, $mod['id'], 'text-aqua'); ?>"></i>
									<p>
										<span class="pull-right-container"><?= $mod['modul']; ?></span>
									</p>
								</a>
							</li>
						<?php else : ?>
							<li class="nav-item has-treeview <?= jecho($this->modul_ini, $mod['id'], 'active'); ?>">
								<a href="<?= site_url("$mod[url]"); ?>" class="nav-link">
									<i class="nav-icon text-sm fa <?= $mod['ikon']; ?> <?= jecho($this->modul_ini, $mod['id'], 'text-aqua'); ?>"></i>
									<p>
										<span class="pull-right-container"><?= $mod['modul']; ?></span>
										<i class="right fas fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview <?= jecho($this->modul_ini, $mod['id'], 'active'); ?>">
									<?php foreach ($mod['submodul'] as $submod): ?>
										<li class="nav-item <?= jecho($this->sub_modul_ini, $submod['id'], 'active'); ?>">
											<a href="<?= site_url("$submod[url]"); ?>" class="nav-link">
												<i class="nav-icon text-sm fa <?= ($submod['ikon'] != NULL) ? $submod['ikon'] : 'fa-circle-o'; ?> <?= jecho($this->sub_modul_ini, $submod['id'], 'text-red'); ?>"></i>
												<p><?= $submod['modul']; ?></p>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</nav>
	</div>
</aside>
