<?php

/*
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
 * @package   OpenSID
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 */

namespace App\Services\SpamDetection\ML\Transformers;

use App\Services\SpamDetection\Analyzers\UrlAnalyzer;
use Rubix\ML\DataType;
use Rubix\ML\Transformers\Transformer;

/**
 * Text preprocessor for spam detection.
 *
 * Normalizes raw text before vectorization, mirroring the Python
 * implementation from judol-comment-classification and adding
 * l33t digit normalization. Implemented as a Rubix Transformer so
 * it can be serialized within the Pipeline (.rbx).
 */
final class SpamPreprocessorTransformer implements Transformer
{
    /** Obfuscation character mappings. */
    private const OBFUSCATION_MAP = [
        '🅰' => 'a', '🅱' => 'b', '🅲' => 'c', '🅳' => 'd', '🅴' => 'e',
        '🅵' => 'f', '🅶' => 'g', '🅷' => 'h', '🅸' => 'i', '🅹' => 'j',
        '🅺' => 'k', '🅻' => 'l', '🅼' => 'm', '🅽' => 'n', '🅾' => 'o',
        '🅿' => 'p', '🆀' => 'q', '🆁' => 'r', '🆂' => 's', '🆃' => 't',
        '🆄' => 'u', '🆅' => 'v', '🆆' => 'w', '🆇' => 'x', '🆈' => 'y',
        '🆉' => 'z',
        '🅰️' => 'a', '🅱️' => 'b', '🅾️' => 'o', '🅿️' => 'p',
        'ⓐ' => 'a', 'ⓑ' => 'b', 'ⓒ' => 'c', 'ⓓ' => 'd', 'ⓔ' => 'e',
        'ⓕ' => 'f', 'ⓖ' => 'g', 'ⓗ' => 'h', 'ⓘ' => 'i', 'ⓙ' => 'j',
        'ⓚ' => 'k', 'ⓛ' => 'l', 'ⓜ' => 'm', 'ⓝ' => 'n', 'ⓞ' => 'o',
        'ⓟ' => 'p', 'ⓠ' => 'q', 'ⓡ' => 'r', 'ⓢ' => 's', 'ⓣ' => 't',
        'ⓤ' => 'u', 'ⓥ' => 'v', 'ⓦ' => 'w', 'ⓧ' => 'x', 'ⓨ' => 'y',
        'ⓩ' => 'z',
        'Ⓐ' => 'a', 'Ⓑ' => 'b', 'Ⓒ' => 'c', 'Ⓓ' => 'd', 'Ⓔ' => 'e',
        'Ⓕ' => 'f', 'Ⓖ' => 'g', 'Ⓗ' => 'h', 'Ⓘ' => 'i', 'Ⓙ' => 'j',
        'Ⓚ' => 'k', 'Ⓛ' => 'l', 'Ⓜ' => 'm', 'Ⓝ' => 'n', 'Ⓞ' => 'o',
        'Ⓟ' => 'p', 'Ⓠ' => 'q', 'Ⓡ' => 'r', 'Ⓢ' => 's', 'Ⓣ' => 't',
        'Ⓤ' => 'u', 'Ⓥ' => 'v', 'Ⓦ' => 'w', 'Ⓧ' => 'x', 'Ⓨ' => 'y',
        'Ⓩ' => 'z',
        '⓪' => '0', '①' => '1', '②' => '2', '③' => '3', '④' => '4',
        '⑤' => '5', '⑥' => '6', '⑦' => '7', '⑧' => '8', '⑨' => '9',
        '❶' => '1', '❷' => '2', '❸' => '3', '❹' => '4', '❺' => '5',
        '❻' => '6', '❼' => '7', '❽' => '8', '❾' => '9',
        '0️⃣' => '0', '1️⃣' => '1', '2️⃣' => '2', '3️⃣' => '3',
        '4️⃣' => '4', '5️⃣' => '5', '6️⃣' => '6',
        '7️⃣' => '7', '8️⃣' => '8', '9️⃣' => '9',
        '@' => 'a', '$' => 's', '!' => 'i', '€' => 'e',
        '𝐚' => 'a', '𝐛' => 'b', '𝐜' => 'c', '𝐝' => 'd', '𝐞' => 'e',
        '𝐟' => 'f', '𝐠' => 'g', '𝐡' => 'h', '𝐢' => 'i', '𝐣' => 'j',
        '𝐤' => 'k', '𝐥' => 'l', '𝐦' => 'm', '𝐧' => 'n', '𝐨' => 'o',
        '𝐩' => 'p', '𝐪' => 'q', '𝐫' => 'r', '𝐬' => 's', '𝐭' => 't',
        '𝐮' => 'u', '𝐯' => 'v', '𝐰' => 'w', '𝐱' => 'x', '𝐲' => 'y',
        '𝐳' => 'z',
        '𝒶' => 'a', '𝒷' => 'b', '𝒸' => 'c', '𝒹' => 'd', 'ℯ' => 'e',
        '𝒻' => 'f', 'ℊ' => 'g', '𝒽' => 'h', '𝒾' => 'i', '𝒿' => 'j',
        '𝓀' => 'k', '𝓁' => 'l', '𝓂' => 'm', '𝓃' => 'n', 'ℴ' => 'o',
        '𝓅' => 'p', '𝓆' => 'q', '𝓇' => 'r', '𝓈' => 's', '𝓉' => 't',
        '𝓊' => 'u', '𝓋' => 'v', '𝓌' => 'w', '𝓍' => 'x', '𝓎' => 'y',
        '𝓏' => 'z',
        'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd', 'ｅ' => 'e',
        'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j',
        'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n', 'ｏ' => 'o',
        'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't',
        'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y',
        'ｚ' => 'z',
        '﹐' => ',', '﹑' => ',', '﹒' => '.', '﹔' => ';', '﹕' => ':',
        '﹖' => '?', '﹗' => '!', '﹙' => '(', '﹚' => ')', '﹛' => '{',
        '﹜' => '}', '﹝' => '[', '﹞' => ']', '﹟' => '#', '﹠' => '&',
        '﹡' => '*', '﹢' => '+', '﹣' => '-', '﹤' => '<', '﹥' => '>',
        '﹦' => '=', '﹨' => '/', '﹩' => '$', '﹪' => '%', '﹫' => '@',
        '﹬' => '\\', '﹭' => '_', '﹮' => '~', '﹯' => '.',
        '｡' => '.', '｢' => '"', '｣' => '"', '､' => ',',
        '･' => '·', 'ｦ' => 'o', 'ｧ' => 'a', 'ｨ' => 'i', 'ｩ' => 'u',
        'ｪ' => 'e', 'ｫ' => 'o', 'ｬ' => 'ya', 'ｭ' => 'yu', 'ｮ' => 'yo',
        'ｯ' => 'tsu', 'ｰ' => '-', 'ｱ' => 'a', 'ｲ' => 'i', 'ｳ' => 'u',
        'ｴ' => 'e', 'ｵ' => 'o', 'ｶ' => 'ka', 'ｷ' => 'ki', 'ｸ' => 'ku',
        'ｹ' => 'ke', 'ｺ' => 'ko', 'ｻ' => 'sa', 'ｼ' => 'shi', 'ｽ' => 'su',
        'ｾ' => 'se', 'ｿ' => 'so', 'ﾀ' => 'ta', 'ﾁ' => 'chi', 'ﾂ' => 'tsu',
        'ﾃ' => 'te', 'ﾄ' => 'to', 'ﾅ' => 'na', 'ﾆ' => 'ni', 'ﾇ' => 'nu',
        'ﾈ' => 'ne', 'ﾉ' => 'no', 'ﾊ' => 'ha', 'ﾋ' => 'hi', 'ﾌ' => 'fu',
        'ﾎ' => 'he', 'ﾏ' => 'ma', 'ﾐ' => 'mi', 'ﾑ' => 'mu', 'ﾒ' => 'me',
        'ﾓ' => 'mo', 'ﾔ' => 'ya', 'ﾕ' => 'yu', 'ﾖ' => 'yo', 'ﾗ' => 'ra',
        'ﾘ' => 'ri', 'ﾙ' => 'ru', 'ﾚ' => 're', 'ﾛ' => 'ro', 'ﾜ' => 'wa',
        'ﾝ' => 'n', 'ﾞ' => '"', 'ﾟ' => '°',
    ];

    /** L33t digit mappings (narrow set validated via multi-seed). */
    private const L33T_DIGIT_MAP = [
        '0' => 'o', '1' => 'i', '3' => 'e',
        '4' => 'a', '5' => 's', '7' => 't',
    ];

    private const URL_PATTERN = '#(?:https?://|www\.)\S+#iu';

    /** Token untuk URL umum (spam signal). */
    private const URL_TOKEN = 'iniurl';

    /** Token untuk URL dari domain resmi / tepercaya (bukan spam). */
    private const LEGIT_URL_TOKEN = 'govurl';

    /**
     * Domain tepercaya yang diizinkan — single source of truth via UrlAnalyzer.
     * Dijaga sinkron agar UrlAnalyzer (rule-based) dan ML preprocessor
     * tidak menghukum URL legit yang sama (mis. skm.go.id → govurl, bukan iniurl).
     *
     * @see \App\Services\SpamDetection\Analyzers\UrlAnalyzer::ALLOWED_DOMAINS
     */
    public const LEGIT_DOMAINS = UrlAnalyzer::ALLOWED_DOMAINS;

    /** Pola domain legit tanpa protokol (mis. skm.go.id, malangkab.go.id) — bare domain. */
    private const LEGIT_BARE_DOMAIN_PATTERN = '#\b[a-z0-9-]+(?:\.[a-z0-9-]+)+\b(?:/[^\s]*)?#iu';

    /**
     * Cache regex suffix untuk host legit.
     * Dibangun sekali dari LEGIT_DOMAINS agar isLegitHost() tidak loop per domain.
     *
     * @var string|null
     */
    private static ?string $legitHostPattern = null;

    private const KEYBOARD_WALK_PATTERNS = [
        'qwerty', 'wertyu', 'ertyui', 'rtyui', 'tyuiu', 'yuiu', 'uiu',
        'asdfg', 'sdfgh', 'dfghj', 'fghjk', 'ghjkl', 'hjkl',
        'zxcvb', 'xcvbn', 'cvbnm', 'vbnm',
        'qaz', 'wsx', 'edc', 'rfv', 'tgb', 'yhn', 'ujm',
    ];

    private const ENTROPY_THRESHOLD = 3.5;

    /**
     * @return list<DataType>
     */
    public function compatibility(): array
    {
        return DataType::all();
    }

    /**
     * Transform the dataset in place.
     *
     * @param list<list<mixed>> $samples
     */
    public function transform(array &$samples): void
    {
        foreach ($samples as &$sample) {
            foreach ($sample as &$value) {
                if (is_string($value)) {
                    $value = $this->clean($value);
                }
            }
            unset($value);
        }
        unset($sample);
    }

    /**
     * Normalize raw text to its canonical form.
     */
    public function clean(string $content): string
    {
        $text = $this->normalizeUnicode($content);
        $text = strtr($text, self::OBFUSCATION_MAP);
        $text = strtr($text, self::L33T_DIGIT_MAP);
        $text = $this->normalizeUnicode($text);
        $text = $this->replaceUrlsWithTokens($text);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;

        if ($this->isGibberish($text)) {
            $text = 'gibberish ' . $text;
        }

        return trim($text);
    }

    /**
     * Ganti URL dengan token yang sesuai whitelist.
     * Bare domain hanya di-scan bila teks mengandung titik, menghindari
     * pemindaian regex generik yang tidak perlu untuk teks tanpa domain.
     */
    private function replaceUrlsWithTokens(string $text): string
    {
        $text = preg_replace_callback(self::URL_PATTERN, function (array $m): string {
            $url = $m[0];
            $host = parse_url($url, PHP_URL_HOST);
            if ($host === null || $host === false || $host === '') {
                $host = parse_url('http://' . ltrim($url, '/'), PHP_URL_HOST);
            }
            $host = mb_strtolower((string) $host);
            if ($host !== '' && $this->isLegitHost($host)) {
                return ' ' . self::LEGIT_URL_TOKEN . ' ';
            }

            return ' ' . self::URL_TOKEN . ' ';
        }, $text) ?? $text;

        if (!str_contains($text, '.')) {
            return $text;
        }

        $text = preg_replace_callback(self::LEGIT_BARE_DOMAIN_PATTERN, function (array $m): string {
            $candidate = $m[0];
            $host = explode('/', $candidate, 2)[0];
            $host = rtrim(mb_strtolower($host), '.');

            if ($this->isLegitHost($host)) {
                return ' ' . self::LEGIT_URL_TOKEN . ' ';
            }

            return $candidate;
        }, $text) ?? $text;

        return $text;
    }

    /**
     * Regex suffix untuk host legit, dibangun sekali dari LEGIT_DOMAINS.
     * Alternation diurutkan terpanjang dulu agar pencocokan deterministik.
     */
    private static function getLegitHostPattern(): string
    {
        if (self::$legitHostPattern !== null) {
            return self::$legitHostPattern;
        }

        $domains = [];
        foreach (self::LEGIT_DOMAINS as $raw) {
            $d = mb_strtolower(trim((string) $raw));
            if (str_contains($d, '/')) {
                $d = explode('/', $d, 2)[0];
            }
            $d = ltrim($d, '.');
            if ($d === '') {
                continue;
            }
            $domains[] = preg_quote($d, '/');
        }

        $domains = array_unique($domains);
        usort($domains, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        $alt = implode('|', $domains);

        return self::$legitHostPattern = '/^(?:.*\.)?(?:' . $alt . ')$/i';
    }

    /**
     * Host legit hanya jika sama persis atau subdomain dari whitelist.
     * Memperbaiki bug str_contains() yang meloloskan evil-opendesa.id.evil.com.
     */
    private function isLegitHost(string $host): bool
    {
        $host = mb_strtolower($host);
        if ($host === '') {
            return false;
        }

        return (bool) preg_match(self::getLegitHostPattern(), $host);
    }

    private function isGibberish(string $text): bool
    {
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        if ($words === []) {
            return false;
        }

        $gibberishCount = 0;
        foreach ($words as $word) {
            if (strlen($word) < 4) {
                continue;
            }

            $lowerWord = strtolower($word);
            foreach (self::KEYBOARD_WALK_PATTERNS as $pattern) {
                if (str_contains($lowerWord, $pattern)) {
                    $gibberishCount++;
                    break;
                }
            }

            if ($this->shannonEntropy($lowerWord) > self::ENTROPY_THRESHOLD) {
                $gibberishCount++;
            }
        }

        return $gibberishCount / max(1, count($words)) > 0.3;
    }

    private function shannonEntropy(string $str): float
    {
        $len = strlen($str);
        if ($len === 0) {
            return 0.0;
        }

        $freq = [];
        for ($i = 0; $i < $len; $i++) {
            $c = $str[$i];
            $freq[$c] = ($freq[$c] ?? 0) + 1;
        }

        $entropy = 0.0;
        foreach ($freq as $count) {
            $p = $count / $len;
            $entropy -= $p * log($p, 2);
        }

        return $entropy;
    }

    private function normalizeUnicode(string $text): string
    {
        if (! mb_check_encoding($text, 'UTF-8')) {
            $text = $this->sanitizeUtf8($text);
        }

        if ($text === '') {
            return '';
        }

        $text = strtr($text, self::OBFUSCATION_MAP);

        if (class_exists(\Normalizer::class)) {
            $normalized = \Normalizer::normalize($text, \Normalizer::NFKC);
            if ($normalized !== false) {
                $text = $normalized;
            }
        }

        if ($text === '') {
            return '';
        }

        return preg_replace('/\p{M}+/u', '', $text) ?? $text;
    }

    private function sanitizeUtf8(string $text): string
    {
        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }

    public function __toString(): string
    {
        return 'Spam Preprocessor';
    }
}
