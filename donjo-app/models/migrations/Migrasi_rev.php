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

use App\Models\Modul;
use App\Models\ProfilDesa;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->hapusPengaturanTampilkanLapak();
        $this->ubahNamaModulLogPenduduk();
        $this->alterTableKelompokMaster();
        $this->perbaikiProfilStatusDesa();

        // Bersihkan cache agar perubahan menu catatan peristiwa langsung terlihat
        cache()->flush();
    }

    public function hapusPengaturanTampilkanLapak()
    {
        SettingAplikasi::withoutGlobalScopes()
            ->where('key', 'tampilkan_lapak_web')
            ->delete();
    }

    public function ubahNamaModulLogPenduduk()
    {
        Modul::where('slug', 'peristiwa')
            ->where('modul', 'Catatan Peristiwa')
            ->update(['modul' => 'Riwayat Mutasi Penduduk']);
    }

    public function alterTableKelompokMaster()
    {
        Schema::table('kelompok_master', static function (Blueprint $table) {
            $table->text('deskripsi')->change();
        });
    }

    public function perbaikiProfilStatusDesa()
    {
        ProfilDesa::where('key', 'status_desa')
            ->where('value', 'adat')
            ->update(['value' => 'Adat']);

        ProfilDesa::where('key', 'status_desa')
            ->where('value', 'non_adat')
            ->update(['value' => 'Bukan Adat']);

        ProfilDesa::where('key', 'regulasi_penetapan_kampung_adat')
            ->update(['judul' => 'Regulasi Penetapan [Desa] Adat']);

        ProfilDesa::where('key', 'dokumen_regulasi_penetapan_kampung_adat')
            ->update(['judul' => 'Dokumen Regulasi Penetapan [Desa] Adat']);
    }
}
