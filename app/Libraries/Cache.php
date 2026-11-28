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

class Cache
{
    /**
     * Path direktori penyimpanan cache.
     */
    protected string $path;

    /**
     * Inisialisasi library Cache.
     */
    public function __construct(?string $path = null)
    {
        $this->path = $path ?: FCPATH . LOKASI_CACHE;
    }

    /**
     * Dapatkan path lengkap file cache berdasarkan key.
     */
    protected function getFilePath(string $key): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $key;
    }

    /**
     * Ambil data dari cache.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $path = $this->getFilePath($key);

        if (! file_exists($path)) {
            return $default;
        }

        try {
            $contents = file_get_contents($path);
            if (empty($contents)) {
                return $default;
            }

            $payload = json_decode($contents, false);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Migrasi file lama (serialized) secara aman tanpa instansiasi class
                $unserialized = @unserialize($contents, ['allowed_classes' => false]);
                if ($unserialized !== false || $contents === 'b:0;') {
                    if (is_array($unserialized) && isset($unserialized['data']) && isset($unserialized['ttl'])) {
                        $value      = $unserialized['data'];
                        $ttl        = $unserialized['ttl'];
                        $expiration = $unserialized['time'] + $ttl;
                    } else {
                        $value      = $unserialized;
                        $expiration = 0;
                    }

                    // Konversi dan simpan ke format baru
                    $newPayload = [
                        'expiration' => $expiration,
                        'data'       => $value,
                    ];
                    $json = json_encode($newPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    file_put_contents($path, $json);

                    return json_decode(json_encode($value), false);
                }

                return $default;
            }

            if (! isset($payload->expiration) || ! property_exists($payload, 'data')) {
                return $default;
            }

            if ($payload->expiration !== 0 && time() >= $payload->expiration) {
                $this->forget($key);
                return $default;
            }

            return $payload->data;
        } catch (\Exception $e) {
            log_message('error', 'Gagal membaca cache: ' . $e->getMessage());
            return $default;
        }
    }

    /**
     * Simpan data ke cache dengan format JSON.
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl Durasi simpan dalam detik (null untuk selamanya)
     * @return bool
     */
    public function put(string $key, $value, ?int $ttl = null): bool
    {
        $path = $this->getFilePath($key);
        $expiration = $ttl ? (time() + $ttl) : 0;

        $payload = [
            'expiration' => $expiration,
            'data' => $value,
        ];

        try {
            $dir = dirname($path);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            return file_put_contents($path, $json) !== false;
        } catch (\Exception $e) {
            log_message('error', 'Gagal menyimpan cache: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Periksa apakah data cache ada dan belum kedaluwarsa.
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * Hapus data dari cache.
     */
    public function forget(string $key): bool
    {
        $path = $this->getFilePath($key);

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Alias dari forget() demi kompatibilitas.
     */
    public function delete(string $key): bool
    {
        return $this->forget($key);
    }
}
