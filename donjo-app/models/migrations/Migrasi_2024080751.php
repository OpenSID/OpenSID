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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024080751 extends MY_model
{
    public function up()
    {
        $hasil = $this->migrasi_2024080851(true);
        $hasil = $this->migrasi_2024080752($hasil);
        return $this->migrasi_2024080753($hasil);
    }

    protected function migrasi_2024080851($hasil)
    {
        // mutasi_inventaris_peralatan
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_peralatan', 'FK_mutasi_inventaris_peralatan', 'mutasi_inventaris_peralatan');
        $hasil = $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_peralatan', 'mutasi_inventaris_peralatan', 'id_inventaris_peralatan', 'inventaris_peralatan', 'id', true);
        // mutasi_inventaris_jalan
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_jalan', 'FK_mutasi_inventaris_jalan', 'mutasi_inventaris_jalan');
        $hasil = $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_jalan', 'mutasi_inventaris_jalan', 'id_inventaris_jalan', 'inventaris_jalan', 'id', true);
        // mutasi_inventaris_gedung
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_gedung', 'FK_mutasi_inventaris_gedung', 'mutasi_inventaris_gedung');
        $hasil = $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_gedung', 'mutasi_inventaris_gedung', 'id_inventaris_gedung', 'inventaris_gedung', 'id', true);
        // mutasi_inventaris_asset
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_asset', 'FK_mutasi_inventaris_asset', 'mutasi_inventaris_asset');

        return $hasil && $this->tambahForeignKey('FK_mutasi_inventaris_asset', 'mutasi_inventaris_asset', 'id_inventaris_asset', 'inventaris_asset', 'id', true);
    }

    protected function migrasi_2024080752($hasil)
    {
        // sebenarnya constraint ini sudah ada, barangkali ada db yang gagal membuat constraint ini.
        $hasil = $hasil && $this->hapus_foreign_key('suplemen', 'suplemen_terdata_suplemen_1', 'suplemen_terdata');
        $hasil = $hasil && $this->hapus_foreign_key('suplemen', 'suplemen_terdata_suplemen_fk', 'suplemen_terdata');

        return $hasil && $this->tambahForeignKey('suplemen_terdata_suplemen_fk', 'suplemen_terdata', 'id_suplemen', 'suplemen', 'id', true);
    }

    protected function migrasi_2024080753($hasil)
    {
        $cek = count(DB::select("SHOW INDEX FROM kelompok WHERE Key_name = 'slug_config'"));

        if ($cek) {
            Schema::table('kelompok', static function (Blueprint $table) {
                $table->dropIndex('slug_config');
                $table->unique(['slug', 'config_id'], 'slug_config_tipe');
            });
        }

        return $hasil;
    }
}
