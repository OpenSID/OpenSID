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

use App\Models\Config;
use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;

/**
 * Acak identitas desa (kontak kantor & camat) pada tabel config.
 */
final class IdentitasDesaSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak identitas desa';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $config = Config::withoutGlobalScopes()->first();
        if ($config === null) {
            return;
        }

        $gen = $context->gen;

        $config->update([
            'alamat_kantor' => $gen->alamat((int) $config->id),
            'email'         => $gen->email('desa-' . $config->id),
            'telepon'       => $gen->phone('desa-telepon-' . $config->id),
            'ponsel'        => $gen->phone('desa-ponsel-' . $config->id),
            'website'       => $gen->website(),
            'kode_pos'      => (int) $gen->kodePos((int) $config->id),
            'nama_camat'    => $gen->name('camat-' . $config->id, 1),
            'nip_camat'     => $gen->nip('camat-' . $config->id),
        ]);

        $result->markTable('config');
        $result->addRows(1);
    }
}
