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

namespace App\Rules;

use App\Services\SpamDetection\DTOs\SpamDetectionResult;
use App\Services\SpamDetection\SpamDetectionService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class NotSpam implements ValidationRule
{
    /**
     * Instance dari SpamDetectionService (injected via service container)
     */
    private SpamDetectionService $spamDetectionService;

    /**
     * Threshold untuk menganggap konten sebagai spam (0-100)
     */
    private int $threshold;

    /**
     * Result terakhir dari analisis (untuk akses data detail)
     */
    private ?SpamDetectionResult $lastResult = null;

    /**
     * Konstruktor
     *
     * @param int $threshold Threshold spam score (0–100), default 70
     */
    public function __construct(int $threshold = SpamDetectionService::DEFAULT_THRESHOLD)
    {
        $this->spamDetectionService = new SpamDetectionService();
        $this->threshold = $threshold;
    }

    /**
     * Run the validation rule
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $this->lastResult = $this->spamDetectionService->analyze($value, $this->threshold);

        if ($this->lastResult->isSpam) {
            $fail("Konten {$attribute} terdeteksi sebagai spam. Mohon periksa kembali isi pesan Anda.");
        }
    }

    /**
     * Dapatkan skor spam terakhir yang dihitung
     */
    public function getSpamScore(): int
    {
        return $this->lastResult?->score ?? 0;
    }

    /**
     * Dapatkan detail deteksi spam terakhir
     */
    public function getDetectionDetails(): array
    {
        return $this->lastResult?->details ?? [];
    }

    /**
     * Dapatkan tipe deteksi yang terdeteksi
     */
    public function getDetectedTypes(): array
    {
        return $this->lastResult?->getDetectedTypes() ?? [];
    }

    /**
     * Dapatkan result object lengkap
     */
    public function getResult(): ?SpamDetectionResult
    {
        return $this->lastResult;
    }
}
