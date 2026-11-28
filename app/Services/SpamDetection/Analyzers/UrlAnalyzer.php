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

class UrlAnalyzer extends AbstractAnalyzer
{
    /** Domain shortener dan mencurigakan yang sering digunakan spam */
    private const SUSPICIOUS_DOMAINS = [
        'bit.ly', 'tinyurl.com', 'short.link', 'ouo.io', 'adf.ly',
        'cutt.ly', 'rb.gy', 'is.gd', 't.co', 'ow.ly',
    ];
 
    /**
     * Domain resmi yang diizinkan — tidak akan ditandai spam
     * meski ada URL di dalamnya
     */
    private const ALLOWED_DOMAINS = [
        // Pemerintah Indonesia
        '.go.id', '.mil.id', '.ac.id', '.sch.id',
        // Platform media sosial
        'youtube.com', 'youtu.be',
        'google.com', 'maps.google.com',
        'facebook.com', 'instagram.com',
        'whatsapp.com',
        // OpenSID sendiri
        'opendesa.id', 'github.com/OpenSID',
    ];
 
    public function type(): string
    {
        return 'suspicious_urls';
    }
 
    public function maxScore(): int
    {
        return 35;
    }
 
    public function analyze(string $content): ?AnalyzerResult
    {
        $suspiciousFound   = [];
        $unrecognizedCount = 0;
 
        // Cek 1: suspicious domain muncul langsung di konten (tanpa butuh protokol)
        // Ini menangkap kasus "bit.ly/pinjol-cepat" tanpa https://
        foreach (self::SUSPICIOUS_DOMAINS as $domain) {
            if (stripos($content, $domain) !== false) {
                $suspiciousFound[] = $domain;
            }
        }
 
        // Cek 2: URL lengkap dengan protokol — cari domain tidak dikenal
        if (preg_match_all('/https?:\/\/([a-z0-9\-\.]+)/i', $content, $matches)) {
            foreach ($matches[1] as $domain) {
                $domain = mb_strtolower($domain);
 
                // Skip jika sudah tercatat sebagai suspicious
                if ($this->isSuspiciousDomain($domain)) {
                    continue;
                }
 
                if (! $this->isAllowedDomain($domain)) {
                    $unrecognizedCount++;
                }
            }
        }
 
        $score = count($suspiciousFound) * 20 + $unrecognizedCount * 8;
 
        if ($score === 0) {
            return null;
        }
 
        return $this->result(
            rawScore: $score,
            metadata: [
                'suspicious_domains' => array_unique($suspiciousFound),
                'unrecognized_count' => $unrecognizedCount,
            ],
        );
    }
 
    private function isSuspiciousDomain(string $domain): bool
    {
        foreach (self::SUSPICIOUS_DOMAINS as $suspicious) {
            if (str_contains($domain, $suspicious)) {
                return true;
            }
        }
 
        return false;
    }
 
    private function isAllowedDomain(string $domain): bool
    {
        foreach (self::ALLOWED_DOMAINS as $allowed) {
            if (str_ends_with($domain, $allowed) || str_contains($domain, $allowed)) {
                return true;
            }
        }
 
        return false;
    }
}
