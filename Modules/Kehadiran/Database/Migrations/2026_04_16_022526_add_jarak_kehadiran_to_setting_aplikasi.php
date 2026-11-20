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
use App\Models\SettingAplikasi;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan pengaturan jarak_kehadiran untuk absensi via aplikasi mobile
        $this->createSettings([
            [
                'judul'      => 'Jarak Kehadiran',
                'key'        => 'jarak_kehadiran',
                'value'      => SettingAplikasi::JARAK_KEHADIRAN,
                'keterangan' => 'Jarak maksimal (dalam meter) yang diperbolehkan untuk melakukan absensi kehadiran melalui aplikasi mobile.',
                'jenis'      => 'input-number',
                'attribute'  => json_encode([
                    'class'       => 'required',
                    'min'         => 1,
                    'max'         => 10000,
                    'step'        => 1,
                    'placeholder' => (string) SettingAplikasi::JARAK_KEHADIRAN,
                ]),
                'kategori' => 'Kehadiran',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->deleteSetting(['key' => 'jarak_kehadiran']);
    }
};
