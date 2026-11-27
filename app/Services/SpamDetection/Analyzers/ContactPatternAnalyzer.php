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

use App\Services\SpamDetection\DTOs\AnalyzerResult;

class ContactPatternAnalyzer extends AbstractAnalyzer
{
    /**
     * Contact solicitation patterns yang sering di promosi spam
     * Pattern: Hub WA, Hubungi WA, Kontak WA, Hub: 0812..., dll
     */
    private const CONTACT_PATTERNS = [
        'hub wa',
        'hubungi wa',
        'kontak wa',
        'hub whatsapp',
        'hubungi whatsapp',
        'chat wa',
        'chatting wa',
        'wa kami',
        'hubungi kami di',
        'hubungi nomor',
        'hubungi telepon',
        'hubungi ke',
        'chat kami',
        'hubungi sekarang',
        'hubungi segera',
        'pesan wa',
        'pesan sekarang',
    ];

    public function type(): string
    {
        return 'contact_patterns';
    }

    public function maxScore(): int
    {
        return 12;
    }

    public function analyze(string $content): ?AnalyzerResult
    {
        $normalized = $this->normalize($content);
        $found      = [];

        foreach (self::CONTACT_PATTERNS as $pattern) {
            if (str_contains($normalized, $pattern)) {
                $found[] = $pattern;
            }
        }

        if (empty($found)) {
            return null;
        }

        $patternCount = count($found);

        return $this->result(
            rawScore: $patternCount * 6, // 6 poin per pattern
            metadata: [
                'patterns'   => $found,
                'count'      => $patternCount,
            ],
        );
    }
}
