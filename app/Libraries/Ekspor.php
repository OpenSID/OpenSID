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

namespace App\Libraries;

use App\Models\Config;
use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use MySQLDump;
use mysqli;
use mysqli_result;
use mysqli_sql_exception;
use MySQLImport;
use Throwable;
use TypeError;

class Ekspor
{
    public const DAFTAR_KOLOM = [
        'alamat',
        'dusun',
        'rw',
        'rt',
        'nama',
        'no_kk',
        'no_rtm',
        'nik',
        'sex',
        'tempatlahir',
        'tanggallahir',
        'agama_id',
        'pendidikan_kk_id',
        'pendidikan_sedang_id',
        'pekerjaan_id',
        'status_kawin',
        'kk_level',
        'warganegara_id',
        'ayah_nik',
        'nama_ayah',
        'ibu_nik',
        'nama_ibu',
        'golongan_darah_id',
        'akta_lahir',
        'dokumen_pasport',
        'tanggal_akhir_paspor',
        'dokumen_kitas',
        'akta_perkawinan',
        'tanggalperkawinan',
        'akta_perceraian',
        'tanggalperceraian',
        'cacat_id',
        'cara_kb_id',
        'hamil',
        'ktp_el',
        'status_rekam',
        'alamat_sekarang',
        'status_dasar',
        'suku',
        'tag_id_card',
        'id_asuransi',
        'no_asuransi',
        'lat',
        'lng',
        'ket',
    ];

    private readonly mysqli $db;
    private array $config;
    private ?int $configId = null;

    public function __construct()
    {
        $databaseConnection = new Database();
        $this->config       = $databaseConnection->getDatabaseOption();
        $this->db           = new mysqli($this->config['host'], $this->config['username'], $this->config['password'], $this->config['database'], $this->config['port']);
    }

    /**
     * Set config_id untuk backup per desa.
     *
     * @param int $configId ID dari tabel config (desa) yang akan di-backup
     */
    public function setConfigId(int $configId): self
    {
        $this->configId = $configId;

        return $this;
    }

    public function backup(): string
    {
        try {
            $dump = new MySQLDump($this->db, 'utf8mb4', $this->configId);

            // Daftarkan tabel config sebagai master table jika backup per desa
            if ($this->configId !== null) {
                $dump->addMasterTable('config', 'id');
            }

            // Save backup to file
            $backupDir = DESAPATH . '/backup';
            if (! is_dir($backupDir)) {
                if (! mkdir($backupDir, 0777, true)) {
                    throw new Exception("Gagal membuat direktori backup: {$backupDir}");
                }
            } else {
                File::cleanDirectory($backupDir);
            }

            // Naming untuk file backup
            $timestamp = date('Y-m-d-H-i-s');
            if ($this->configId !== null) {
                $desa     = identitas();
                $desaName = $desa ? Str::slug($desa->nama_desa) : 'desa';
                $dbName   = "{$backupDir}/backup-desa-{$this->configId}-{$desaName}-{$timestamp}.sql.gz";
            } else {
                $dbName = "{$backupDir}/backup-on-{$timestamp}.sql.gz";
            }

            $dump->save($dbName);

            if (! file_exists($dbName)) {
                throw new Exception("File backup gagal dibuat di: {$dbName}");
            }

            return $dbName;
        } catch (Exception $e) {
            throw new Exception('Backup database gagal: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Restore database dari file backup.
     *
     * @param bool $preserveAdminCredentials Restore dari instalasi berbeda: pertahankan
     *                                       kredensial admin, app_key, dan koneksi database
     *                                       lokal agar tidak tertimpa oleh isi backup.
     */
    public function restore(string $filename, bool $preserveAdminCredentials = false): bool
    {
        // Simpan kredensial admin lokal sebelum seluruh tabel ditimpa oleh dump.
        $savedCredentials = $preserveAdminCredentials ? $this->saveAdminCredentials() : [];

        // MySQL 8.0 tetap memaksa kecocokan charset kolom FK meski FOREIGN_KEY_CHECKS=0.
        // Men-drop seluruh tabel lebih dulu memastikan dump membuatnya kembali dengan
        // charset yang benar tanpa konflik FK — penting untuk dump lintas-instalasi.
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $tables = $this->db->query('SHOW TABLES');
        if ($tables) {
            $drops = [];
            while ($row = $tables->fetch_row()) {
                $drops[] = '`' . $this->db->real_escape_string($row[0]) . '`';
            }
            if ($drops !== []) {
                $this->db->query('DROP TABLE IF EXISTS ' . implode(',', $drops));
            }
        }

        try {
            $import = new MySQLImport($this->db);
            $import->load($filename);
        } catch (TypeError $e) {
            // Bug EOF MySQLImport: strlen(false) dengan strict_types saat EOF gzip — aman diabaikan.
            logger()->warning('MySQLImport EOF bug: ' . $e->getMessage());
        } catch (Exception $e) {
            // Sebuah statement pada dump gagal (mis. CREATE VIEW dengan DEFINER asing).
            // phpMyAdmin menaruh VIEW di bagian akhir, sehingga data tabel biasanya sudah
            // terimpor penuh. Catat dan lanjutkan agar pemulihan kredensial tetap berjalan.
            logger()->warning('MySQLImport terputus (dilanjutkan): ' . $e->getMessage());
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        $this->fixViewDefiners();

        if ($preserveAdminCredentials && $savedCredentials !== []) {
            $this->restoreAdminCredentials($savedCredentials);
        }

        try {
            $this->clearCache();
            $this->resetAppKey($preserveAdminCredentials);
        } catch (Throwable $e) {
            logger()->warning('Post-restore cleanup gagal: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * Ambil kredensial semua user aktif sebelum restore. Query lengkap menyertakan
     * email dan slug grup; jika skema tidak memilikinya (mis. backup versi lama),
     * fallback ke username + password saja agar admin tetap dapat login.
     */
    private function saveAdminCredentials(): array
    {
        $result = $this->querySafe(
            'SELECT u.username, u.password, u.email, u.email_verified_at, g.slug AS grup_slug '
            . 'FROM `user` u LEFT JOIN `user_grup` g ON u.id_grup = g.id '
            . 'WHERE u.active = 1'
        );

        if (! $result) {
            $result = $this->querySafe('SELECT username, password FROM `user` WHERE active = 1');
        }

        if (! $result) {
            return [];
        }

        $credentials = [];
        while ($row = $result->fetch_assoc()) {
            $credentials[$row['username']] = [
                'password'          => $row['password'],
                'email'             => $row['email'] ?? null,
                'email_verified_at' => $row['email_verified_at'] ?? null,
                'grup_slug'         => $row['grup_slug'] ?? null,
            ];
        }

        return $credentials;
    }

    /**
     * Terapkan kembali kredensial admin lokal ke database yang baru di-restore.
     */
    private function restoreAdminCredentials(array $credentials): void
    {
        // Pastikan autocommit=1 agar UPDATE langsung di-commit dan bertahan setelah
        // koneksi ditutup. Impor dapat meninggalkan autocommit=0 lewat LOCK TABLES.
        $this->db->autocommit(true);

        $now = date('Y-m-d H:i:s');

        foreach ($credentials as $username => $data) {
            $password        = $data['password'];
            $email           = $data['email'];
            $emailVerifiedAt = $data['email_verified_at'];
            $grupSlug        = $data['grup_slug'] ?? null;

            $emailSql = $email !== null
                ? ', email = \'' . $this->db->real_escape_string($email) . '\''
                  . ', email_verified_at = ' . ($emailVerifiedAt ? '\'' . $this->db->real_escape_string($emailVerifiedAt) . '\'' : 'NULL')
                : '';

            $updated = $this->querySafe(
                'UPDATE `user` SET password = \'' . $this->db->real_escape_string($password) . '\''
                . ', active = 1'
                . ', last_login = \'' . $now . '\''
                . $emailSql
                . ' WHERE username = \'' . $this->db->real_escape_string($username) . '\''
            );

            // Username tidak ditemukan pada dump yang diimpor (restore lintas-instalasi).
            // Buat ulang user grup administrator agar admin lokal tetap bisa login.
            if ($updated !== false && $this->db->affected_rows === 0 && $grupSlug === 'administrator') {
                $this->insertAdminUser($username, $password, $email, $emailVerifiedAt, $now);
            }
        }

        // COMMIT eksplisit — jaga-jaga bila ada transaksi implisit yang terbuka.
        $this->querySafe('COMMIT');
    }

    /**
     * Buat ulang user administrator lokal yang hilang dari dump, dipaksa ber-id=1
     * agar super_admin() (yang memilih administrator dengan id terkecil) selalu
     * mengembalikan user ini meski dump memiliki akun admin ber-id lebih besar.
     */
    private function insertAdminUser(string $username, string $password, ?string $email, ?string $emailVerifiedAt, string $now): void
    {
        $grupResult = $this->querySafe("SELECT id FROM `user_grup` WHERE slug = 'administrator' LIMIT 1");
        $grupId     = $grupResult ? ($grupResult->fetch_assoc()['id'] ?? 0) : 0;

        $configResult = $this->querySafe('SELECT id FROM `config` LIMIT 1');
        $configId     = $configResult ? ($configResult->fetch_assoc()['id'] ?? 1) : 1;

        $usernameEsc      = $this->db->real_escape_string($username);
        $passwordEsc      = $this->db->real_escape_string($password);
        $emailVal         = $email !== null ? "'" . $this->db->real_escape_string($email) . "'" : 'NULL';
        $emailVerifiedVal = $emailVerifiedAt !== null ? "'" . $this->db->real_escape_string($emailVerifiedAt) . "'" : 'NULL';

        $this->querySafe(
            'INSERT INTO `user` (id, config_id, username, password, active, id_grup, id_telegram, session, foto, email, email_verified_at, last_login) '
            . "VALUES (1, {$configId}, '{$usernameEsc}', '{$passwordEsc}', 1, {$grupId}, '', '', 'kuser.png', {$emailVal}, {$emailVerifiedVal}, '{$now}') "
            . "ON DUPLICATE KEY UPDATE username='{$usernameEsc}', password='{$passwordEsc}', active=1, id_grup={$grupId}, last_login='{$now}', email={$emailVal}, email_verified_at={$emailVerifiedVal}"
        );
    }

    /**
     * Perbaiki DEFINER VIEW yang menunjuk ke user MySQL asing (umum pada backup
     * phpMyAdmin lintas-server) agar VIEW dapat diakses di server lokal.
     */
    private function fixViewDefiners(): void
    {
        $views = $this->querySafe('SHOW FULL TABLES WHERE Table_type = \'VIEW\'');
        if (! $views) {
            return;
        }
        while ($row = $views->fetch_row()) {
            $viewName = $row[0];
            $result   = $this->querySafe('SHOW CREATE VIEW `' . $this->db->real_escape_string($viewName) . '`');
            if (! $result) {
                continue;
            }
            $def = $result->fetch_assoc()['Create View'] ?? null;
            if (! $def) {
                continue;
            }
            $fixed = preg_replace('/DEFINER=`[^`]+`@`[^`]+`/', 'DEFINER=`' . $this->config['username'] . '`@`localhost`', $def);
            if ($fixed !== $def) {
                $this->querySafe('DROP VIEW IF EXISTS `' . $this->db->real_escape_string($viewName) . '`');
                $this->querySafe($fixed);
            }
        }
    }

    /**
     * Jalankan query mysqli dengan aman: kembalikan false alih-alih melempar
     * mysqli_sql_exception saat strict mode aktif, agar satu statement gagal
     * tidak menggagalkan seluruh proses restore.
     */
    private function querySafe(string $sql): mysqli_result|bool
    {
        try {
            return $this->db->query($sql);
        } catch (mysqli_sql_exception $e) {
            logger()->warning('[restore] query gagal: ' . $e->getMessage());

            return false;
        }
    }

    private function clearCache(): void
    {
        // reset cache blade
        kosongkanFolder(config_item('cache_blade'));
        cache()->flush();
    }

    private function resetAppKey(bool $paksa = false): void
    {
        if ($paksa) {
            // Restore lintas-instalasi: pertahankan app_key LOKAL dan paksa tabel config
            // hasil impor memakainya. Mencegah app_key desa lain menimpa desa/app_key lokal
            // dan merusak instalasi.
            $localKey = trim(@file_get_contents(DESAPATH . 'app_key') ?: '');
            if (! empty($localKey)) {
                Config::query()->update(['app_key' => $localKey]);

                // Sengaja TIDAK menyentuh desa/config/database.php. Karena app_key lokal
                // tidak berubah, password yang sudah tersimpan di file itu (terenkripsi
                // dengan key yang sama, atau kosong) tetap valid. Meng-encrypt ulang akan
                // menulis ulang file secara sia-sia — dan karena $this->config['password']
                // adalah nilai runtime yang SUDAH didekripsi, password kosong akan berubah
                // menjadi blob encrypt('') non-kosong yang menimpa setelan yang dipertahankan.
                logger()->info('[restore] resetAppKey (paksa): app_key lokal dipertahankan; database.php tidak diubah');

                return;
            }
        }

        // Restore normal (instalasi sama): adopsi app_key dari DB hasil impor agar
        // desa/app_key dan desa/config/database.php tetap sinkron dengan config.app_key.
        $app_key = Config::first()->app_key;
        if (empty($app_key)) {
            $app_key = set_app_key();
            Config::first()->update(['app_key' => $app_key]);
        }

        // Enkripsi password DB dengan key hasil impor sebelum mengaktifkannya. Menulis
        // file key paling akhir memastikan key lama tetap valid bila langkah di atas gagal.
        $rawKey       = base64_decode(Str::after($app_key, 'base64:'));
        $newEncrypter = new Encrypter($rawKey, 'AES-256-CBC');
        updateConfigFile('password', $newEncrypter->encrypt($this->config['password']));
        file_put_contents(DESAPATH . 'app_key', $app_key);
    }
}
