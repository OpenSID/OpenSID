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
use App\Services\Acak\PeriksaResolver;
use Illuminate\Support\Facades\DB;

/**
 * Selesaikan masalah /periksa agar database demo lolos pemeriksaan.
 *
 * App\Libraries\Periksa (yang dipakai resolver) bergantung pada CI3
 * (identitas()/ci_auth()/kades()), sehingga langkah ini HANYA berjalan di
 * konteks web (ada instance CI_Controller). Pada jalur CLI/headless ia dilewati
 * dengan aman — sama seperti pendekatan er-laravel.
 */
final class PeriksaResolverSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Menyelesaikan pemeriksaan database (periksa)';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        if (! $this->ci3Tersedia()) {
            return;
        }

        $configId = (int) (identitas('id') ?: DB::table('config')->value('id'));
        if ($configId <= 0) {
            return;
        }

        $out = (new PeriksaResolver($configId))->resolve();

        $result->periksaResolved = $out['resolved'];
        $result->periksaResidual = $out['residual'];

        $total = array_sum($out['resolved']);
        if ($total > 0) {
            $result->addRows($total);
        }

        // Masalah yang masih tersisa (mis. level-skema) juga dilaporkan sebagai
        // peringatan agar terlihat di ringkasan CLI.
        foreach ($out['residual'] as $masalah) {
            $result->addWarning('periksa masih melaporkan: ' . $masalah);
        }
    }

    /**
     * Apakah konteks CI3 aktif (dibutuhkan Periksa). False di CLI/pengujian.
     */
    private function ci3Tersedia(): bool
    {
        return $this->ciInstance() !== null;
    }

    /**
     * Instance CI3 yang aktif, atau null pada konteks non-CI (artisan murni /
     * pengujian). Return type nullable — get_instance() dari stub CI dianggap
     * non-null oleh analisis statis, padahal runtime bisa null.
     */
    private function ciInstance(): ?object
    {
        return function_exists('get_instance') ? get_instance() : null;
    }
}
