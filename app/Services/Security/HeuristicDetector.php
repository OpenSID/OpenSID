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

namespace App\Services\Security;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class HeuristicDetector
{
    /**
     * Pattern kategorisasi untuk risk assessment
     * Total: 136 patterns across 15 categories
     */
    private const PATTERNS = [

        // CATEGORY: Obfuscation & Encoding (Weight: 40)
        'obfuscation' => [
            '/base64_decode\s*\(/i',                     // Most common obfuscation
            '/str_rot13\s*\(/i',                         // ROT13 encoding
            '/gzuncompress\s*\(/i',                      // Decompression
            '/gzinflate\s*\(/i',                         // Decompression
            '/strrev\s*\(/i',                            // String reversal
            '/convert_uudecode\s*\(/i',                  // UU decode
            '/preg_replace\s*\(.*[\'"]\/.*e[\'"].*\)/i', // preg_replace /e modifier (dangerous!)
            '/pack\s*\(/i',                              // Binary packing
            '/unpack\s*\(/i',                            // Binary unpacking
            '/base_convert\s*\(/i',                      // Base conversion trick
            '/mcrypt_encrypt\s*\(/i',                    // Encryption (legacy)
            '/mcrypt_decrypt\s*\(/i',                    // Decryption (legacy)
            '/openssl_encrypt\s*\(/i',                   // Modern encryption
            '/openssl_decrypt\s*\(/i',                   // Modern decryption
        ],

        // CATEGORY: Code Execution (Weight: 50) - CRITICAL!
        'execution' => [
            '/eval\s*\(/i',                 // Arbitrary code execution
            '/assert\s*\(/i',               // Can execute PHP code
            '/system\s*\(/i',               // System command
            '/shell_exec\s*\(/i',           // Shell execution
            '/exec\s*\(/i',                 // Command execution
            '/passthru\s*\(/i',             // Execute with raw output
            '/popen\s*\(/i',                // Process pipe
            '/proc_open\s*\(/i',            // Process open
            '/pcntl_exec\s*\(/i',           // Process control execution
            '/call_user_func\s*\(/i',       // Dynamic function call
            '/call_user_func_array\s*\(/i', // Dynamic function call with array
            '/create_function\s*\(/i',      // Create anonymous function (deprecated)
            '/`[^`]+`/i',                   // Backtick shell execution
        ],

        // CATEGORY: File Operations (Weight: 30)
        'file_ops' => [
            '/fopen\s*\([^,]+,\s*["\']w/i',                 // Open file for writing
            '/fwrite\s*\(/i',                               // Write to file
            '/fputs\s*\(/i',                                // Write to file (alias)
            '/file_put_contents\s*\(/i',                    // Write entire file
            '/file_get_contents\s*\(\s*["\']https?:\/\//i', // Remote file inclusion
            '/unlink\s*\(/i',                               // Delete file
            '/rename\s*\(/i',                               // Rename file
            '/copy\s*\(/i',                                 // Copy file
            '/move_uploaded_file\s*\(/i',                   // Move uploaded file
            '/chmod\s*\(/i',                                // Change permissions
            '/chown\s*\(/i',                                // Change owner
            '/chgrp\s*\(/i',                                // Change group
        ],

        // CATEGORY: Superglobal Access (Weight: 20)
        'superglobals' => [
            '/\$_REQUEST\s*\[/i', // Request data
            '/\$_POST\s*\[/i',    // POST data
            '/\$_GET\s*\[/i',     // GET data
            '/\$_FILES\s*\[/i',   // Uploaded files
            '/\$_SERVER\s*\[/i',  // Server variables
            '/\$_COOKIE\s*\[/i',  // Cookies
            '/\$_SESSION\s*\[/i', // Session data
            '/\$_ENV\s*\[/i',     // Environment variables
            '/\$GLOBALS\s*\[/i',  // Global scope access
        ],

        // CATEGORY: HTTP Behavior (Weight: 25)
        'http' => [
            '/\$_SERVER\s*\[\s*[\'"]HTTP_REFERER["\']\s*\]/i',    // Referer checking
            '/\$_SERVER\s*\[\s*[\'"]HTTP_USER_AGENT["\']\s*\]/i', // User agent checking
            '/\$_SERVER\s*\[\s*[\'"]REMOTE_ADDR["\']\s*\]/i',     // IP address
            '/header\s*\(\s*["\']Location:/i',                    // Redirect
            '/header\s*\(\s*["\']HTTP\//i',                       // HTTP header manipulation
            '/setcookie\s*\(/i',                                  // Set cookie
            '/setrawcookie\s*\(/i',                               // Set raw cookie
        ],

        // CATEGORY: Output Manipulation (Weight: 20)
        'output' => [
            '/echo\s+["\']<script/i',        // Inline script injection
            '/print\s+["\']<script/i',       // Print script tag
            '/printf\s*\(\s*["\']<script/i', // Formatted script output
            '/ob_start\s*\(/i',              // Output buffering
            '/ob_get_clean\s*\(/i',          // Get and clean buffer
            '/ob_end_clean\s*\(/i',          // End buffer
            '/ob_get_contents\s*\(/i',       // Get buffer contents
            '/ob_flush\s*\(/i',              // Flush buffer
        ],

        // CATEGORY: Network Operations (Weight: 35)
        'network' => [
            '/curl_exec\s*\(/i',            // cURL execution
            '/curl_multi_exec\s*\(/i',      // Multi cURL
            '/fsockopen\s*\(/i',            // Socket connection
            '/pfsockopen\s*\(/i',           // Persistent socket
            '/stream_socket_client\s*\(/i', // Socket client
            '/stream_socket_server\s*\(/i', // Socket server
            '/socket_create\s*\(/i',        // Create socket
            '/socket_connect\s*\(/i',       // Connect socket
            '/ftp_connect\s*\(/i',          // FTP connection
            '/ftp_login\s*\(/i',            // FTP login
            '/ssh2_connect\s*\(/i',         // SSH connection
        ],

        // CATEGORY: Database Operations (Weight: 15)
        'database' => [
            '/mysql_query\s*\(/i',        // MySQL query (deprecated)
            '/mysqli_query\s*\(/i',       // MySQLi query
            '/mysqli_multi_query\s*\(/i', // Multiple queries
            '/pg_query\s*\(/i',           // PostgreSQL query
            '/sqlite_query\s*\(/i',       // SQLite query
            '/PDO::query\s*\(/i',         // PDO query
        ],

        // CATEGORY: Dangerous PHP Functions (Weight: 30)
        'dangerous' => [
            '/phpinfo\s*\(/i',                       // PHP info disclosure
            '/ini_set\s*\(/i',                       // Runtime configuration
            '/ini_alter\s*\(/i',                     // Alias of ini_set
            '/ini_restore\s*\(/i',                   // Restore config
            '/register_shutdown_function\s*\(/i',    // Shutdown function
            '/register_tick_function\s*\(/i',        // Tick function
            '/set_time_limit\s*\(\s*0\s*\)/i',       // No time limit
            '/ignore_user_abort\s*\(\s*true\s*\)/i', // Continue after disconnect
            '/dl\s*\(/i',                            // Load PHP extension
            '/error_reporting\s*\(\s*0\s*\)/i',      // Suppress errors
        ],

        // CATEGORY: Variable Variables & Reflection (Weight: 25)
        'dynamic' => [
            '/\$\$[a-zA-Z_]/i',              // Variable variables
            '/\$\{[^}]+\}/i',                // Variable interpolation
            '/ReflectionFunction\s*\(/i',    // Function reflection
            '/ReflectionMethod\s*\(/i',      // Method reflection
            '/ReflectionClass\s*\(/i',       // Class reflection
            '/get_defined_functions\s*\(/i', // Get all functions
            '/get_defined_vars\s*\(/i',      // Get all variables
            '/extract\s*\(/i',               // Extract array to variables
            '/parse_str\s*\([^,]+\s*\)/i',   // Parse string to variables (no 2nd arg)
        ],

        // CATEGORY: Known Malware Signatures (Weight: 50)
        'malware' => [
            '/c99shell/i',          // C99 webshell
            '/r57shell/i',          // R57 webshell
            '/b374k/i',             // B374k shell
            '/wso[\s_]?shell/i',    // WSO shell
            '/backdoor/i',          // Backdoor keyword
            '/webshell/i',          // Webshell keyword
            '/remoteexec/i',        // Remote execution
            '/FilesMan/i',          // File manager (often malicious)
            '/uname\s*-a/i',        // System info gathering
            '/safe_mode/i',         // PHP safe mode bypass
            '/disable_functions/i', // Disabled functions check
        ],

        // CATEGORY: WordPress Specific (Weight: 30)
        'wordpress' => [
            '/add_action\s*\(.*eval/i',                     // Eval in WP hook
            '/add_filter\s*\(.*eval/i',                     // Eval in WP filter
            '/add_action\s*\(.*base64_decode/i',            // Obfuscated hook
            '/add_filter\s*\(.*base64_decode/i',            // Obfuscated filter
            '/\$GLOBALS\s*\[\s*["\']wp_filter["\']\s*\]/i', // Hook manipulation
            '/wp_eval_request\s*\(/i',                      // Known malicious pattern
            '/do_action\s*\(.*eval/i',                      // Action with eval
            '/apply_filters\s*\(.*eval/i',                  // Filter with eval
        ],

        // CATEGORY: Polyglot & MIME Confusion (Weight: 40)
        'polyglot' => [
            '/^GIF89a.*<\?php/s',     // GIF + PHP
            '/^‰PNG.*<\?php/s',       // PNG + PHP
            '/^<\?xml.*<\?php/s',     // XML + PHP
            '/^<svg.*<\?php/s',       // SVG + PHP
            '/^JFIF.*<\?php/s',       // JPEG + PHP
            '/^PK\x03\x04.*<\?php/s', // ZIP + PHP
        ],

        // CATEGORY: Encoded PHP Tags (Weight: 35)
        // Note: These patterns detect OBFUSCATED tags, not normal <?php
        'encoded_tags' => [
            '/\\\\x3c\\\\x3f\\\\x70\\\\x68\\\\x70/i', // String literal "\x3c\x3f\x70\x68\x70"
            '/\\\\x3c\\\\x3f/i',                      // String literal "\x3c\x3f"
            '/\\\\u003c\\\\u003f/i',                  // String literal "\u003c\u003f"
            '/&lt;\?php/i',                           // HTML entity <?php
            '/chr\s*\(\s*60\s*\)/i',                  // chr(60) = '<'
            '/chr\s*\(\s*63\s*\)/i',                  // chr(63) = '?'
        ],

        // CATEGORY: Suspicious Strings (Weight: 20)
        'suspicious_strings' => [
            '/hacked\s+by/i', // Defacement signature
            '/owned\s+by/i',  // Defacement signature
            '/priv8/i',       // Private/leaked tool
            '/exploit/i',     // Exploit keyword
            '/vuln/i',        // Vulnerability
            '/0day/i',        // Zero-day
            '/rootkit/i',     // Rootkit
            '/trojan/i',      // Trojan
        ],
    ];

    /**
     * Pattern weights untuk risk scoring
     */
    private const WEIGHTS = [
        'execution'          => 50, // CRITICAL
        'malware'            => 50, // CRITICAL
        'obfuscation'        => 40,
        'polyglot'           => 40,
        'network'            => 35,
        'encoded_tags'       => 35,
        'file_ops'           => 30,
        'wordpress'          => 30,
        'dangerous'          => 30,
        'dynamic'            => 25,
        'http'               => 25,
        'output'             => 20,
        'superglobals'       => 20,
        'suspicious_strings' => 20,
        'database'           => 15,
    ];

    /**
     * Maximum file size to scan (1MB default)
     */
    private int $maxFileSize = 1048576;

    /**
     * Set maximum file size untuk scanning
     *
     * @param int $bytes Size in bytes
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->maxFileSize = $bytes;

        return $this;
    }

    /**
     * Scan single file dengan comprehensive pattern matching
     *
     * @param string $filepath Path to file
     *
     * @return array Detection result with risk score and matched patterns
     */
    public function scanFile(string $filepath): array
    {
        $result = [
            'suspicious'       => false,
            'risk_score'       => 0,
            'risk_level'       => 'SAFE',
            'matched_patterns' => [],
            'categories'       => [],
            'recommendation'   => 'No action needed',
        ];

        // Check file exists
        if (! file_exists($filepath)) {
            return array_merge($result, [
                'error' => 'File not found',
            ]);
        }

        // Check file size
        $filesize = filesize($filepath);
        if ($filesize > $this->maxFileSize) {
            return array_merge($result, [
                'skipped' => true,
                'reason'  => 'File too large: ' . $this->formatBytes($filesize),
            ]);
        }

        // Read file content
        $content = @file_get_contents($filepath);
        if ($content === false) {
            return array_merge($result, [
                'error' => 'Unable to read file',
            ]);
        }

        // Scan with all pattern categories
        foreach (self::PATTERNS as $category => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content, $matches)) {
                    $weight = self::WEIGHTS[$category] ?? 10;
                    $result['risk_score'] += $weight;

                    // Sanitize match untuk JSON encoding - ambil 100 chars pertama
                    $matchText = mb_substr($matches[0], 0, 100);

                    // Binary data: encode base64, text biasa: bersihkan control characters
                    if (! mb_check_encoding($matchText, 'UTF-8')) {
                        $matchText = '[Binary: ' . base64_encode($matchText) . ']';
                    } else {
                        $matchText = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '�', $matchText);
                    }

                    $result['matched_patterns'][] = [
                        'category' => $category,
                        'pattern'  => $pattern,
                        'weight'   => $weight,
                        'match'    => $matchText,
                    ];

                    if (! in_array($category, $result['categories'])) {
                        $result['categories'][] = $category;
                    }
                }
            }
        }

        // Determine risk level and recommendation
        if ($result['risk_score'] > 0) {
            $result['suspicious'] = true;

            if ($result['risk_score'] >= 100) {
                $result['risk_level']     = 'CRITICAL';
                $result['recommendation'] = 'DELETE IMMEDIATELY - Multiple high-risk patterns detected';
            } elseif ($result['risk_score'] >= 50) {
                $result['risk_level']     = 'HIGH';
                $result['recommendation'] = 'QUARANTINE - Likely malicious, requires investigation';
            } elseif ($result['risk_score'] >= 30) {
                $result['risk_level']     = 'MEDIUM';
                $result['recommendation'] = 'REVIEW - Suspicious patterns found, manual review needed';
            } else {
                $result['risk_level']     = 'LOW';
                $result['recommendation'] = 'MONITOR - Low risk, may be legitimate but worth checking';
            }
        }

        return $result;
    }

    /**
     * Quick check hanya ekstension dan filename
     * Untuk pre-filtering sebelum full scan
     */
    public function quickCheck(string $filepath): bool
    {
        $ext      = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        $filename = strtolower(basename($filepath));

        // Dangerous extensions
        $dangerous_ext = [
            'php',
            'php3',
            'php4',
            'php5',
            'php7',
            'phps',
            'pht',
            'phtml',
            'pgif',
            'pjpg',
            'pjpeg',
            'exe',
            'sh',
            'bat',
            'cmd',
            'com',
        ];

        if (in_array($ext, $dangerous_ext)) {
            return true;
        }

        // Suspicious filename patterns
        $suspicious = [
            'shell',
            'c99',
            'r57',
            'b374k',
            'wso',
            'backdoor',
            'webshell',
            'exploit',
            'hacked',
            'owned',
            'priv8',
            '0day',
            'rootkit',
        ];

        foreach ($suspicious as $pattern) {
            if (strpos($filename, $pattern) !== false) {
                return true;
            }
        }

        // Hidden files (starts with dot)
        return (bool) (strpos($filename, '.') === 0 && $filename !== '.htaccess');
    }

    /**
     * Batch scan multiple files
     *
     * @param string $directory   Target directory
     * @param array  $excludeDirs Directories to exclude
     *
     * @return array Scan results summary
     */
    public function scanDirectory(string $directory, array $excludeDirs = []): array
    {
        $results = [
            'total_scanned'    => 0,
            'suspicious_count' => 0,
            'clean_count'      => 0,
            'skipped_count'    => 0,
            'error_count'      => 0,
            'files'            => [],
        ];

        if (! is_dir($directory)) {
            return array_merge($results, ['error' => 'Directory not found']);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $filepath = $file->getPathname();

            // Check if excluded - normalize paths for comparison
            $excluded       = false;
            $normalizedPath = str_replace('\\', '/', $filepath);

            foreach ($excludeDirs as $excludeDir) {
                $normalizedExclude = str_replace('\\', '/', $excludeDir);
                // Check if path contains the excluded directory
                if (strpos($normalizedPath, $normalizedExclude) !== false) {
                    $excluded = true;
                    break;
                }
            }

            if ($excluded) {
                continue;
            }

            // Scan PHP files AND suspicious image/archive files
            if (! $this->isPhpFile($filepath) && ! $this->isSuspiciousNonPhpFile($filepath)) {
                continue;
            }

            $results['total_scanned']++;

            $scan = $this->scanFile($filepath);

            if (isset($scan['error'])) {
                $results['error_count']++;
            } elseif (isset($scan['skipped'])) {
                $results['skipped_count']++;
            } elseif ($scan['suspicious']) {
                $results['suspicious_count']++;
                $results['files'][$filepath] = $scan;
            } else {
                $results['clean_count']++;
            }
        }

        // Sort by risk score (highest first)
        uasort($results['files'], static fn ($a, $b) => $b['risk_score'] <=> $a['risk_score']);

        return $results;
    }

    /**
     * Check if file should be scanned (PHP files or suspicious non-PHP files)
     * Centralized method to avoid duplication
     */
    public function shouldScanFile(string $filepath): bool
    {
        return $this->isPhpFile($filepath) || $this->isSuspiciousNonPhpFile($filepath);
    }

    /**
     * Check if non-PHP file is suspicious and should be scanned
     * Detects polyglot files (image/archive + PHP)
     */
    public function isSuspiciousNonPhpFile(string $filepath): bool
    {
        $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

        // File extensions yang sering disalahgunakan untuk menyembunyikan PHP
        $suspicious_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'zip', 'rar', 'ico', 'txt', 'log'];

        if (! in_array($ext, $suspicious_ext)) {
            return false;
        }

        // Check file size (skip if too large)
        if (filesize($filepath) > $this->maxFileSize) {
            return false;
        }

        // Read first 8KB untuk magic bytes check
        $handle = @fopen($filepath, 'rb');
        if ($handle === false) {
            return false;
        }

        $header = fread($handle, 8192);
        fclose($handle);

        // Check untuk PHP tags dalam file non-PHP
        if (
            preg_match('/<\?php/i', $header)
            || preg_match('/<\?=/i', $header)
            || preg_match('/<script[^>]*language\s*=\s*["\']?php["\']?/i', $header)
        ) {
            return true; // Suspicious: PHP code in non-PHP file
        }

        // Check untuk encoded PHP tags
        return (bool) (
            preg_match('/\\\\x3c\\\\x3f/i', $header)
            || preg_match('/chr\s*\(\s*60\s*\).*chr\s*\(\s*63\s*\)/i', $header)
        );
              // Suspicious: Encoded PHP tags
    }

    /**
     * Generate detailed report
     */
    public function generateReport(array $scanResults): string
    {
        $report   = [];
        $report[] = '═══════════════════════════════════════════════════════';
        $report[] = '        OPENSID SECURITY SCAN REPORT';
        $report[] = '═══════════════════════════════════════════════════════';
        $report[] = '';
        $report[] = 'Scan Time: ' . date('Y-m-d H:i:s');
        $report[] = 'Total Files Scanned: ' . $scanResults['total_scanned'];
        $report[] = 'Clean Files: ' . $scanResults['clean_count'];
        $report[] = 'Suspicious Files: ' . $scanResults['suspicious_count'];
        $report[] = 'Skipped Files: ' . $scanResults['skipped_count'];
        $report[] = 'Errors: ' . $scanResults['error_count'];
        $report[] = '';

        if ($scanResults['suspicious_count'] > 0) {
            $report[] = '═══════════════════════════════════════════════════════';
            $report[] = '        SUSPICIOUS FILES DETAILS';
            $report[] = '═══════════════════════════════════════════════════════';
            $report[] = '';

            foreach ($scanResults['files'] as $filepath => $result) {
                $report[] = '┌─────────────────────────────────────────────────────';
                $report[] = '│ File: ' . $filepath;
                $report[] = '│ Risk Level: ' . $result['risk_level'] . ' (Score: ' . $result['risk_score'] . ')';
                $report[] = '│ Categories: ' . implode(', ', $result['categories']);
                $report[] = '│ Recommendation: ' . $result['recommendation'];
                $report[] = '├─────────────────────────────────────────────────────';
                $report[] = '│ Matched Patterns:';

                foreach ($result['matched_patterns'] as $match) {
                    $report[] = "│   - [{$match['category']}] Weight: {$match['weight']}";
                    $report[] = '│     Match: ' . substr($match['match'], 0, 80);
                }

                $report[] = '└─────────────────────────────────────────────────────';
                $report[] = '';
            }
        } else {
            $report[] = '✓ No suspicious files detected. Directory appears clean.';
            $report[] = '';
        }

        $report[] = '═══════════════════════════════════════════════════════';
        $report[] = 'End of Report';
        $report[] = '═══════════════════════════════════════════════════════';

        return implode("\n", $report);
    }

    /**
     * Get pattern statistics
     */
    public function getPatternStats(): array
    {
        $stats         = [];
        $totalPatterns = 0;

        foreach (self::PATTERNS as $category => $patterns) {
            $count = count($patterns);
            $totalPatterns += $count;
            $stats[$category] = [
                'count'  => $count,
                'weight' => self::WEIGHTS[$category] ?? 10,
            ];
        }

        return [
            'categories'       => $stats,
            'total_patterns'   => $totalPatterns,
            'total_categories' => count(self::PATTERNS),
        ];
    }

    /**
     * Check if file is PHP file
     */
    private function isPhpFile(string $filepath): bool
    {
        $ext            = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        $php_extensions = ['php', 'php3', 'php4', 'php5', 'php7', 'phps', 'pht', 'phtml'];

        return in_array($ext, $php_extensions);
    }

    /**
     * Helper: Format bytes
     */
    private function formatBytes(int $bytes): string
    {
        $units  = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf('%.2f %s', $bytes / 1024 ** $factor, $units[$factor]);
    }
}
