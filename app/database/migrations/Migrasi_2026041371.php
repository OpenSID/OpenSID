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

use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Theme;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->tambahPengaturanTahunApbdes();
        $this->hapusTemaNatra();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    public function tambahPengaturanTahunApbdes()
    {
        $this->createSetting([
            'judul'      => 'Tahun APBDes',
            'key'        => 'apbdes_tahun',
            'value'      => null,
            'urut'       => 3,
            'keterangan' => 'Tahun APBDes yang akan ditampilkan dihalaman depan',
            'jenis'      => 'select-array',
            'option'     => null,
            'kategori'   => 'Keuangan',
            'attribute'  => null,
        ]);
    }

    private function hapusTemaNatra(): void
    {
        if ($theme = Theme::where('slug', 'natra')->first()) {
            $theme->delete();
            
            // HIGH: Wrap theme_scan dengan error handling
            try {
                theme_scan();
            } catch (\Exception $e) {
                // Log error tapi jangan crash migration
                \Log::error('Theme scan failed after deleting natra: ' . $e->getMessage());
                // Atau re-throw jika critical
                // throw new \Exception('Gagal update theme cache: ' . $e->getMessage());
            }
        }
    }
};
