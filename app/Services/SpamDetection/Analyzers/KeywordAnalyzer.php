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

class KeywordAnalyzer extends AbstractAnalyzer
{
    private const SCORE_PER_KEYWORD = 20;

    private const MULTI_CATEGORY_BONUS = 15;

    private const HIGH_RISK_CATEGORY_BONUS = 20;

    private const HIGH_RISK_CATEGORIES = [
        'judi_gambling',
        'phishing',
        'barang_ilegal',
        'pinjaman_online',
    ];

    /**
     * Keyword dengan keyakinan sangat tinggi bahwa konten adalah spam.
     * Satu kemunculan saja cukup untuk menaikkan skor melampaui threshold.
     */
    private const HIGH_CONFIDENCE_KEYWORDS = [
        'spinjam', 'spaylater', 'shopee paylater', 'pinjaman shopee',
        'kredit shopee', 'pinjol shopee', 'cair shopee',
        'pinjol', 'pinjaman online', 'akulaku', 'kredivo', 'pinjamflexi', 'tunaiku',
        'call center', 'customer service',
        'kreditpintar', 'kredit pintar',
    ];

    private const HIGH_CONFIDENCE_BONUS = 50;

    private const KEYWORDS = [
        'pinjaman_online' => [
            'pinjamflexi', 'akulaku', 'kredivo', 'tunaiku',
            'pinjaman online', 'pinjol', 'cicilan', 'kredit tanpa',
            'uang cepat', 'uang instan', 'dana tunai', 'tunai cepat',
            'modal usaha', 'investasi menguntungkan',
            'spinjam', 'spaylater', 'shopee paylater', 'pinjaman shopee',
            'kredit shopee', 'pinjol shopee', 'cair shopee',
            'kreditpintar', 'kredit pintar',
        ],
        'judi_gambling' => [
            'togel', 'slot online', 'situs judi', 'bandar togel',
            'live sgp', 'live hk', 'jackpot', 'situs casino',
            'taruhan', 'hongkong pools', 'singapore pools',
        ],
        'barang_ilegal' => [
            'obat aborsi', 'perlancar haid', 'obat kuat',
            'sertifikat palsu', 'ijazah palsu', 'ktp palsu', 'identitas palsu',
        ],
        'jual_beli_digital_ilegal' => [
            'beli follower', 'jual follower', 'jual like',
            'jual akun', 'jual data', 'carding', 'cc murah', 'dump track',
        ],
        'phishing' => [
            'klik link berikut', 'konfirmasi identitas',
            'verifikasi akun', 'reset password anda', 'ubah password anda',
        ],
        'layanan_spam' => [
            'call center', 'customer service',
        ],
    ];

    public function type(): string
    {
        return 'spam_keywords';
    }

    public function maxScore(): int
    {
        return 100;
    }

    public function analyze(string $content): ?AnalyzerResult
    {
        $normalized = $this->normalize($content);
        $found      = [];

        foreach (self::KEYWORDS as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($normalized, $this->normalize($keyword))) {
                    $found[$category][] = $keyword;
                }
            }
        }

        if (empty($found)) {
            return null;
        }

        $totalFound    = array_sum(array_map('count', $found));
        $categoryCount = count($found);
        $baseScore     = $totalFound * self::SCORE_PER_KEYWORD;

        // Bonus jika keyword berasal dari lebih dari satu kategori sekaligus
        $bonusScore = $categoryCount > 1
            ? ($categoryCount - 1) * self::MULTI_CATEGORY_BONUS
            : 0;

        // Bonus khusus high-risk category — satu keyword sudah cukup berbahaya
        $highRiskBonus = 0;

        foreach (array_keys($found) as $category) {
            if (in_array($category, self::HIGH_RISK_CATEGORIES, true)) {
                $highRiskBonus = self::HIGH_RISK_CATEGORY_BONUS;
                break;
            }
        }

        // Bonus keyakinan tinggi — keyword seperti "spinjam", "pinjol", merek
        // pinjol tertentu sudah cukup untuk menganggap konten sebagai spam.
        $highConfidenceBonus = 0;

        foreach ($found as $keywords) {
            foreach ($keywords as $keyword) {
                if (in_array($keyword, self::HIGH_CONFIDENCE_KEYWORDS, true)) {
                    $highConfidenceBonus = self::HIGH_CONFIDENCE_BONUS;
                    break 2;
                }
            }
        }

        // Bonus khusus jika pinjaman_online terdeteksi (high-risk category)
        $pinjamanOnlineBonus = 0;

        return $this->result(
            rawScore: $baseScore + $bonusScore + $highRiskBonus + $highConfidenceBonus + $pinjamanOnlineBonus,
            metadata: [
                'categories'            => array_keys($found),
                'category_count'        => $categoryCount,
                'keywords'              => $found,
                'total'                 => $totalFound,
                'bonus'                 => $bonusScore,
                'high_risk_bonus'       => $highRiskBonus,
                'high_confidence_bonus' => $highConfidenceBonus,
                'pinjaman_online_bonus' => $pinjamanOnlineBonus,
            ],
        );
    }
}
