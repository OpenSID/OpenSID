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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\FormatNoRtmEnum;
use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\FormatSurat;
use App\Models\Simbol;
use App\Scopes\RemoveRtfScope;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->hapusDuplikatSurat();
        $this->hapusSimbolNonGambar();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    public function hapusDuplikatSurat()
    {
        // Hapus surat TinyMCE lama dengan url_surat format 'surat-*'
        // yang dihasilkan oleh tambah_surat_tinymce() versi lama (sebelum fix).
        // Daftar url_surat legacy dibangun dari nama surat di JSON (getSuratBawaanTinyMCE),
        // sehingga hanya menghapus yang memang punya padanan bawaan, bukan semua 'surat-*'.
        $legacyUrls = getSuratBawaanTinyMCE()
            ->map(fn ($surat) => 'surat-' . url_title($surat['nama'], '-', true))
            ->values()
            ->all();

        if (! empty($legacyUrls)) {
            FormatSurat::withoutGlobalScope(RemoveRtfScope::class)
                ->whereIn('jenis', FormatSurat::RTF)
                ->whereIn('url_surat', $legacyUrls)
                ->delete();
        }
    }

    public function hapusSimbolNonGambar(): void
    {
        // Hapus entri di gis_simbol yang bukan file gambar (misalnya index.html)
        // yang terlanjur masuk akibat salin_simbol() tidak memfilter ekstensi file.
        // Gunakan get()->each->delete() agar model event 'deleting' terpanggil
        // dan file fisik ikut terhapus via event deleting di model Simbol.
        Simbol::notImageOnly()->get()->each->delete();
    }
    
};