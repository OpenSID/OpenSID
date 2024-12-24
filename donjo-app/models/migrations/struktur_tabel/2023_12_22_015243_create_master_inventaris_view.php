<?php

/*
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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `master_inventaris` AS select 'inventaris_asset' AS `asset`,`inventaris_asset`.`config_id` AS `config_id`,`inventaris_asset`.`id` AS `id`,`inventaris_asset`.`nama_barang` AS `nama_barang`,`inventaris_asset`.`kode_barang` AS `kode_barang`,'Baik' AS `kondisi`,`inventaris_asset`.`keterangan` AS `keterangan`,`inventaris_asset`.`asal` AS `asal`,`inventaris_asset`.`tahun_pengadaan` AS `tahun_pengadaan` from `inventaris_asset` where `inventaris_asset`.`visible` = 1 union all select 'inventaris_gedung' AS `asset`,`inventaris_gedung`.`config_id` AS `config_id`,`inventaris_gedung`.`id` AS `id`,`inventaris_gedung`.`nama_barang` AS `nama_barang`,`inventaris_gedung`.`kode_barang` AS `kode_barang`,`inventaris_gedung`.`kondisi_bangunan` AS `kondisi_bangunan`,`inventaris_gedung`.`keterangan` AS `keterangan`,`inventaris_gedung`.`asal` AS `asal`,year(`inventaris_gedung`.`tanggal_dokument`) AS `tahun_pengadaan` from `inventaris_gedung` where `inventaris_gedung`.`visible` = 1 union all select 'inventaris_jalan' AS `asset`,`inventaris_jalan`.`config_id` AS `config_id`,`inventaris_jalan`.`id` AS `id`,`inventaris_jalan`.`nama_barang` AS `nama_barang`,`inventaris_jalan`.`kode_barang` AS `kode_barang`,`inventaris_jalan`.`kondisi` AS `kondisi`,`inventaris_jalan`.`keterangan` AS `keterangan`,`inventaris_jalan`.`asal` AS `asal`,year(`inventaris_jalan`.`tanggal_dokument`) AS `tahun_pengadaan` from `inventaris_jalan` where `inventaris_jalan`.`visible` = 1 union all select 'inventaris_peralatan' AS `asset`,`inventaris_peralatan`.`config_id` AS `config_id`,`inventaris_peralatan`.`id` AS `id`,`inventaris_peralatan`.`nama_barang` AS `nama_barang`,`inventaris_peralatan`.`kode_barang` AS `kode_barang`,'Baik' AS `Baik`,`inventaris_peralatan`.`keterangan` AS `keterangan`,`inventaris_peralatan`.`asal` AS `asal`,`inventaris_peralatan`.`tahun_pengadaan` AS `tahun_pengadaan` from `inventaris_peralatan` where `inventaris_peralatan`.`visible` = 1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS `master_inventaris`');
    }
};
