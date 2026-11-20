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

namespace App\Services\SpamDetection\DTOs;

final class SpamDetectionResult
{
    /**
     * @param int              $score     Skor spam 0–100
     * @param bool             $isSpam    Apakah konten dianggap spam
     * @param int              $threshold Threshold yang digunakan
     * @param AnalyzerResult[] $details   Detail dari setiap analyzer
     */
    public function __construct(
        public readonly int $score,
        public readonly bool $isSpam,
        public readonly int $threshold,
        public readonly array $details = [],
    ) {}

    /**
     * Ambil detail berdasarkan tipe analyzer
     */
    public function getDetailByType(string $type): ?AnalyzerResult
    {
        foreach ($this->details as $detail) {
            if ($detail->type === $type) {
                return $detail;
            }
        }

        return null;
    }

    /**
     * Daftar tipe yang terdeteksi
     *
     * @return string[]
     */
    public function getDetectedTypes(): array
    {
        return array_map(fn (AnalyzerResult $d) => $d->type, $this->details);
    }

    public function toArray(): array
    {
        return [
            'score'     => $this->score,
            'is_spam'   => $this->isSpam,
            'threshold' => $this->threshold,
            'details'   => array_map(fn (AnalyzerResult $d) => $d->toArray(), $this->details),
        ];
    }
}
