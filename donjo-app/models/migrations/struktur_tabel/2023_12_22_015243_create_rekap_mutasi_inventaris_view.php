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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
        DB::statement("CREATE VIEW `rekap_mutasi_inventaris` AS select 'inventaris_asset' AS `asset`,`mutasi_inventaris_asset`.`config_id` AS `config_id`,`mutasi_inventaris_asset`.`id_inventaris_asset` AS `id_inventaris_asset`,`mutasi_inventaris_asset`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_asset`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_asset`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_asset`.`keterangan` AS `keterangan` from `mutasi_inventaris_asset` where `mutasi_inventaris_asset`.`visible` = 1 union all select 'inventaris_gedung' AS `inventaris_gedung`,`mutasi_inventaris_gedung`.`config_id` AS `config_id`,`mutasi_inventaris_gedung`.`id_inventaris_gedung` AS `id_inventaris_gedung`,`mutasi_inventaris_gedung`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_gedung`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_gedung`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_gedung`.`keterangan` AS `keterangan` from `mutasi_inventaris_gedung` where `mutasi_inventaris_gedung`.`visible` = 1 union all select 'inventaris_jalan' AS `inventaris_jalan`,`mutasi_inventaris_jalan`.`config_id` AS `config_id`,`mutasi_inventaris_jalan`.`id_inventaris_jalan` AS `id_inventaris_jalan`,`mutasi_inventaris_jalan`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_jalan`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_jalan`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_jalan`.`keterangan` AS `keterangan` from `mutasi_inventaris_jalan` where `mutasi_inventaris_jalan`.`visible` = 1 union all select 'inventaris_peralatan' AS `inventaris_peralatan`,`mutasi_inventaris_peralatan`.`config_id` AS `config_id`,`mutasi_inventaris_peralatan`.`id_inventaris_peralatan` AS `id_inventaris_peralatan`,`mutasi_inventaris_peralatan`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_peralatan`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_peralatan`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_peralatan`.`keterangan` AS `keterangan` from `mutasi_inventaris_peralatan` where `mutasi_inventaris_peralatan`.`visible` = 1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS `rekap_mutasi_inventaris`');
    }
};
