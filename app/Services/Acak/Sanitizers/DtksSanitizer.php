<?php

declare(strict_types=1);

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

namespace App\Services\Acak\Sanitizers;

use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ganti nama/kode petugas, pemeriksa, dan responden pada tabel DTKS dengan nilai
 * demo.
 */
final class DtksSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak data DTKS';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        if (! Schema::hasTable('dtks')) {
            return;
        }

        $affected = DB::table('dtks')->update([
            'nama_petugas_pencacahan' => 'Petugas Demo',
            'kode_petugas_pencacahan' => '00000',
            'nama_pemeriksa'          => 'Pemeriksa Demo',
            'kode_pemeriksa'          => '00000',
            'nama_responden'          => 'Responden Demo',
            'no_hp_responden'         => '08120000000',
            'nama_ppl'                => 'PPL Demo',
            'kode_ppl'                => '0000',
            'nama_pml'                => 'PML Demo',
            'kode_pml'                => '000',
        ]);

        if ($affected > 0) {
            $result->markTable('dtks');
            $result->addRows($affected);
        }
    }
}
