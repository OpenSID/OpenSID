<?php

<<<<<<< HEAD:donjo-app/views/global/tampilkan.php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk tampilkan data
 *
 *
 * donjo-app/views/global/tampilkan.php
 *
 */

=======
>>>>>>> umum/umum:donjo-app/models/migrations/Migrasi_2112_ke_2201.php
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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */
<<<<<<< HEAD:donjo-app/views/global/tampilkan.php
?>

<div class="modal-body">
	<?php if ($tipe == '.pdf'): ?>
		<iframe src="<?= $berkas; ?>" type="application/pdf" style="width: 100%; height: 300px;"></iframe>
	<?php else: ?>
		<img src="<?= $berkas; ?>" style="width: 100%; height: auto;">
	<?php endif; ?>
</div>
<div class="modal-footer">
	<div class="text-center">
		<a href="<?= $link; ?>" class="btn btn-flat bg-navy btn-sm"><i class="fa fa-download"></i> Unduh Dokumen</a>
	</div>
</div>
=======
class Migrasi_2112_ke_2201 extends MY_model
{
	public function up()
	{
		$hasil = true;
    
    // Migrasi fitur premium
    // Jalankan migrasi fitur premium yg digabungkan sejak rilis sebelumnya
    $daftar_migrasi_premium = ['2012', '2101', '2102', '2103', '2104', '2105'];
    foreach ($daftar_migrasi_premium as $migrasi)
    {
      $migrasi_premium = 'migrasi_fitur_premium_'.$migrasi;
      $file_migrasi = 'migrations/'.$migrasi_premium;
      $this->load->model($file_migrasi);
      $hasil = $hasil && $this->$migrasi_premium->up();
    }

		status_sukses($hasil);
		return $hasil;
	}
}
>>>>>>> umum/umum:donjo-app/models/migrations/Migrasi_2112_ke_2201.php
