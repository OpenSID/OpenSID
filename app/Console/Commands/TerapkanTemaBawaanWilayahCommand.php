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

namespace App\Console\Commands;

use App\Services\Theme\PenyelesaiTemaBawaan;
use Illuminate\Console\Command;

/**
 * Pasang & aktifkan tema mitra kabupaten/kota sebagai tema default bila desa
 * berada di wilayah mitra (premium#6991). Dijalankan terjadwal (harian, lihat
 * App\Console\Kernel) agar desa yang baru masuk ke kab/kota mitra, atau tema
 * mitra yang baru terbit di katalog Layanan, tetap tersusul tanpa admin
 * mencarinya sendiri.
 */
class TerapkanTemaBawaanWilayahCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'tema:terapkan-bawaan-wilayah {--paksa : Timpa tema aktif walau bukan tema default rilis (esensi/wira)}';

    /**
     * @var string
     */
    protected $description = 'Terapkan tema mitra kabupaten/kota sebagai tema default bila desa berada di wilayah mitra.';

    public function handle(PenyelesaiTemaBawaan $penyelesai): int
    {
        $slug = $penyelesai->terapkan(null, (bool) $this->option('paksa'));

        if ($slug === null) {
            $this->info('Tidak ada tema mitra wilayah untuk diterapkan.');

            return self::SUCCESS;
        }

        $this->info("Tema mitra wilayah diaktifkan: {$slug}");

        return self::SUCCESS;
    }
}
