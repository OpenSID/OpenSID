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

namespace App\Services\SpamDetection\Analyzers;

use App\Services\SpamDetection\Contracts\ContentAnalyzerInterface;
use App\Services\SpamDetection\DTOs\AnalyzerResult;

abstract class AbstractAnalyzer implements ContentAnalyzerInterface
{
    /**
     * Normalisasi teks sebelum dianalisis:
     * - Ubah ke lowercase
     * - Ganti karakter visual mirip huruf (l33tspeak bypass)
     */
    protected function normalize(string $content): string
    {
        $content = mb_strtolower($content, 'UTF-8');

        $replacements = [
            '0' => 'o', '1' => 'i', '3' => 'e',
            '4' => 'a', '5' => 's', '7' => 't',
            '@' => 'a', '$' => 's',
        ];

        return strtr($content, $replacements);
    }

    /**
     * Buat AnalyzerResult dengan skor yang sudah di-cap sesuai maxScore()
     *
     * @param array<string, mixed> $metadata
     */
    protected function result(int $rawScore, array $metadata = []): AnalyzerResult
    {
        $cappedScore = min($this->maxScore(), max(0, $rawScore));

        return new AnalyzerResult(
            type: $this->type(),
            score: $cappedScore,
            metadata: $metadata,
        );
    }
}
