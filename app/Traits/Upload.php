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

namespace App\Traits;

use App\Libraries\Checker;
use App\Models\Theme;
use Closure;
use Exception;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

trait Upload
{
    public function uploadPicture($gambar = '', $lokasi = '')
    {
        return $this->uploadAll(
            file: $gambar,
            config: [
                'upload_path'   => $lokasi,
                'allowed_types' => 'gif|jpg|png|jpeg|webp',
                'max_size'      => max_upload() * 1024,
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) {
                $extension = strtolower(pathinfo($uploadData['full_path'], PATHINFO_EXTENSION));
                $filePath  = $uploadData['file_path'];
                $rawName   = $uploadData['raw_name'];

                if ($extension === 'gif') {
                    // Jika GIF, cukup copy dan rename saja
                    copy($uploadData['full_path'], "{$filePath}kecil_{$rawName}.gif");
                    copy($uploadData['full_path'], "{$filePath}sedang_{$rawName}.gif");
                    unlink($uploadData['full_path']);

                    return "{$rawName}.gif";
                }
                if ($extension === 'webp') {
                    Image::load($uploadData['full_path'])
                        ->width(440)
                        ->height(440)
                        ->save("{$filePath}kecil_{$rawName}.webp");

                    Image::load($uploadData['full_path'])
                        ->width(880)
                        ->height(880)
                        ->save("{$filePath}sedang_{$rawName}.webp");

                } else {
                    Image::load($uploadData['full_path'])
                        ->width(440)
                        ->height(440)
                        ->format(Manipulations::FORMAT_WEBP)
                        ->save("{$filePath}kecil_{$rawName}.webp");

                    Image::load($uploadData['full_path'])
                        ->width(880)
                        ->height(880)
                        ->format(Manipulations::FORMAT_WEBP)
                        ->save("{$filePath}sedang_{$rawName}.webp");
                }

                // Hapus file asli
                unlink($uploadData['full_path']);

                return "{$rawName}.webp";
            }
        );
    }

    public function uploadImgSetting(&$data)
    {
        // TODO : Jika sudah dipisahkan, buat agar upload gambar dinamis/bisa menyesuaikan dengan kebutuhan tema (u/ Modul Pengaturan Tema)
        if ($data['latar_website']) {
            $data['latar_website'] = $this->uploadGambar('latar_website', (new Theme())->lokasiLatarWebsite());
        } else {
            $data['latar_website'] = setting('latar_website');
        }

        if ($data['latar_login']) {
            $data['latar_login'] = $this->uploadGambar('latar_login', LATAR_LOGIN);
        } else {
            $data['latar_login'] = setting('latar_login');
        }

        if ($data['latar_login_mandiri']) {
            $data['latar_login_mandiri'] = $this->uploadGambar('latar_login_mandiri', LATAR_LOGIN);
        } else {
            $data['latar_login_mandiri'] = setting('latar_login_mandiri');
        }

        if ($data['latar_kehadiran']) {
            $data['latar_kehadiran'] = $this->uploadGambar('latar_kehadiran', LATAR_LOGIN);
        } else {
            $data['latar_kehadiran'] = setting('latar_kehadiran');
        }
    }

    /**
     * Mengunggah logo ke path yang ditentukan.
     *
     * @param string      $file     Nama field input file.
     * @param string      $lokasi   Path untuk menyimpan file.
     * @param int|null    $size     Ukuran logo yang diinginkan.
     * @param bool        $webp     Konversi ke WebP.
     * @param bool        $favicon  Buat favicon.
     * @param string|null $filename Nama file custom.
     *
     * @return string Nama file yang diunggah.
     */
    public function uploadGambar(string $file, string $lokasi, int|string|null $size = null, bool $webp = true, bool $favicon = false, ?string $filename = null, ?string $old_filename = null)
    {
        if (empty($_FILES[$file]['name'])) {
            return null;
        }

        $config = [
            'upload_path'   => $lokasi,
            'allowed_types' => 'gif|jpg|png|jpeg|webp',
            'max_size'      => max_upload() * 1024,
            'overwrite'     => true,
        ];

        if ($filename) {
            $config['file_name'] = $filename;
        }

        return $this->upload(
            file: $file,
            config: $config,
            callback: static function ($uploadData) use ($size, $favicon, $webp, $lokasi, $old_filename) {
                $ext      = strtolower(pathinfo($uploadData['full_path'], PATHINFO_EXTENSION));
                $filePath = $uploadData['file_path'];
                $rawName  = $uploadData['raw_name'];
                $fullPath = $uploadData['full_path'];

                if ($ext === 'gif') {
                    $new_ext = 'gif';
                } else {
                    if ($size) {
                        $image = Image::load($fullPath);
                        if (is_int($size)) {
                            $image->width($size)->height($size);
                        } elseif (is_string($size)) {
                            $dimensi = generateDimensi($size);
                            $image->width($dimensi['width'])->height($dimensi['height']);
                        }
                        $image->save($fullPath);
                    }

                    if ($favicon) {
                        Image::load($fullPath)->width(16)->height(16)->save("{$filePath}favicon.ico");

                        copyFavicon();
                    }

                    if ($webp) {
                        Image::load($fullPath)->format(Manipulations::FORMAT_WEBP)->save("{$filePath}{$rawName}.webp");

                        unlink($fullPath);

                        $new_ext = 'webp';
                    } else {
                        $new_ext = $ext;
                    }
                }

                $new_filename_with_ext = "{$rawName}.{$new_ext}";

                // On success, delete old file
                if ($old_filename && $old_filename !== $new_filename_with_ext && file_exists($lokasi . $old_filename)) {
                    unlink($lokasi . $old_filename);
                }

                return $new_filename_with_ext;
            }
        );
    }

    public function uploadFotoPenduduk(?string $nama_file = '', ?string $dimensi = '', string $lokasi = LOKASI_USER_PICT)
    {
        $foto     = $_POST['foto'];
        $old_foto = $_POST['old_foto'];

        if ($nama_file) {
            $nama_file = time() . random_int(10000, 999999);
        }

        if ($_FILES['foto']['tmp_name']) {
            $nama_file .= get_extension($_FILES['foto']['name']);
            $nama_file = (new Checker(get_app_key(), $nama_file))->encrypt();
            $nama_file = $this->uploadFoto($nama_file, $old_foto, $dimensi, $lokasi);
        } elseif ($foto) {
            $nama_file .= '.webp';
            $foto = str_replace('data:image/png;base64,', '', $foto);
            $foto = base64_decode($foto, true);

            if (! $foto) {
                throw new Exception('Gagal mendekode base64: Data tidak valid atau kosong.');
            }

            $tempPng = $lokasi . 'temp_' . time() . '.png';
            file_put_contents($tempPng, $foto); // Simpan sebagai PNG sementara

            if (! file_exists($tempPng) || filesize($tempPng) == 0) {
                unlink($tempPng);

                throw new Exception('File sementara gagal dibuat atau kosong.');
            }

            // Hapus foto lama jika ada
            if (isset($old_foto)) {
                unlink($lokasi . $old_foto);
                unlink($lokasi . 'kecil_' . $old_foto);
            }

            // Enkripsi nama file
            $nama_file = (new Checker(get_app_key(), $nama_file))->encrypt();

            Image::load($tempPng)
                ->format(Manipulations::FORMAT_WEBP)
                ->width(500) // Atur sesuai kebutuhan
                ->height(500)
                ->save($lokasi . $nama_file);

            // Buat thumbnail kecil
            Image::load($tempPng)
                ->format(Manipulations::FORMAT_WEBP)
                ->width(100)
                ->height(100)
                ->save($lokasi . 'kecil_' . $nama_file);

            // Hapus file sementara
            unlink($tempPng);
        } else {
            $nama_file = null;
        }

        return $nama_file;
    }

    public function uploadFoto(?string $fupload_name, ?string $old_foto, string $dimensi = '200x200', string $lokasi = LOKASI_USER_PICT): string
    {
        return $this->upload(
            file: 'foto',
            config: [
                'upload_path'   => $lokasi,
                'allowed_types' => 'gif|jpg|png|jpeg|webp',
                'max_size'      => max_upload() * 1024,
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) use ($old_foto, $dimensi) {
                $extension = strtolower(pathinfo($uploadData['full_path'], PATHINFO_EXTENSION));
                $filePath  = $uploadData['file_path'];
                // $rawName   = $fupload_name;
                $rawName = $uploadData['raw_name'];

                if ($extension === 'gif') {
                    return "{$rawName}.gif";
                }

                if ($old_foto != '') {
                    // Hapus old_foto
                    unlink($filePath . $old_foto);
                }

                $dimensi = generateDimensi($dimensi);

                Image::load($uploadData['full_path'])
                    ->format(Manipulations::FORMAT_WEBP)
                    ->width($dimensi['width'])
                    ->height($dimensi['height'])
                    ->save("{$filePath}{$rawName}.webp");

                unlink($uploadData['full_path']);

                return "{$rawName}.webp";
            }
        );
    }

    /**
     * Mengunggah file ke path yang ditentukan dengan konfigurasi yang diberikan.
     *
     * @param string       $file        Nama field input file.
     * @param array        $config      Opsi konfigurasi untuk unggahan.
     * @param string|null  $redirectUrl URL untuk dialihkan jika terjadi kesalahan (opsional).
     * @param Closure|null $callback    Fungsi callback yang akan dieksekusi setelah unggahan berhasil (opsional).
     *
     * @return array|string|null Mengembalikan nama file yang diunggah jika berhasil, array dengan pesan kesalahan jika gagal, atau null.
     */
    protected function upload($file, $config = [], $redirectUrl = null, ?Closure $callback = null)
    {
        $isAjax = request()->ajax();
        $CI     = &get_instance();

        if (! is_dir($config['upload_path'])) {
            folder($config['upload_path'], '0755', 'htaccess1');
        }

        $CI->load->library('upload');
        $CI->upload->initialize($config);

        try {
            $upload = $CI->upload->do_upload($file);

            if (! $upload) {
                if ($isAjax) {
                    return json(['error' => $CI->upload->display_errors()], 400);
                }
                redirect_with('error', $CI->upload->display_errors(), $redirectUrl ?? $this->controller);
            }

            $uploadData = $CI->upload->data();

            if ($callback && $uploadData['file_ext'] !== '.webp') {
                return $callback($uploadData);
            }

            if (isset($config['resize'])) {
                resizeImage($uploadData['full_path'], $uploadData['file_type'], $config['resize']);
            }

            return $uploadData['file_name'];
        } catch (Exception $e) {
            logger()->errror($e);

            if ($isAjax) {
                return json(['error' => $e->getMessage()], 400);
            }

            redirect_with('error', $CI->upload->display_errors(), $redirectUrl ?? $this->controller);
        }

        return null;
    }

    protected function uploadAll($file, $config = [], $redirectUrl = null, ?Closure $callback = null)
    {
        $isAjax = request()->ajax();
        $CI     = &get_instance();

        if (! is_dir($config['upload_path'])) {
            folder($config['upload_path'], '0755', 'htaccess1');
        }

        $CI->load->library('upload');
        $CI->upload->initialize($config);

        try {
            $upload = $CI->upload->do_upload($file);

            if (! $upload) {
                if ($isAjax) {
                    return json(['error' => $CI->upload->display_errors()], 400);
                }
                redirect_with('error', $CI->upload->display_errors(), $redirectUrl ?? $this->controller);
            }

            $uploadData = $CI->upload->data();

            if ($callback) {
                return $callback($uploadData);
            }

            if (isset($config['resize'])) {
                resizeImage($uploadData['full_path'], $uploadData['file_type'], $config['resize']);
            }

            return $uploadData['file_name'];
        } catch (Exception $e) {
            logger()->errror($e);

            if ($isAjax) {
                return json(['error' => $e->getMessage()], 400);
            }

            redirect_with('error', $CI->upload->display_errors(), $redirectUrl ?? $this->controller);
        }

        return null;
    }

    /**
     * Mengkonversi URL Google Drive menjadi format yang bisa ditampilkan sebagai gambar
     */
    protected function processImageUrl($url)
    {
        // Jika URL kosong, return apa adanya
        if (empty($url)) {
            return $url;
        }

        // Decode URL jika sudah di-encode sebelumnya
        $decodedUrl = urldecode($url);

        // Handle Google Image Search URLs
        if (strpos($decodedUrl, 'google.com/imgres?imgurl=') !== false) {
            $parts = parse_url($decodedUrl);
            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);
                if (isset($query['imgurl'])) {
                    return $query['imgurl'];
                }
            }
        }

        // Jika sudah dalam format yang benar, return apa adanya
        if (strpos($decodedUrl, 'drive.google.com/uc?') !== false || strpos($decodedUrl, 'drive.google.com/thumbnail?') !== false) {
            return $decodedUrl;
        }

        // Ekstrak ID file dari berbagai format Google Drive URL
        $patterns = [
            '/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)\/?/', // https://drive.google.com/file/d/1HdrQiVDy2vQeD7wv1-Zp9gpMcGhtMqXG/view
            '/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/',     // https://drive.google.com/open?id=1HdrQiVDy2vQeD7wv1-Zp9gpMcGhtMqXG
            '/docs\.google\.com\/uc\?id=([a-zA-Z0-9_-]+)/',        // https://docs.google.com/uc?id=1HdrQiVDy2vQeD7wv1-Zp9gpMcGhtMqXG
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $decodedUrl, $matches)) {
                $fileId = $matches[1];
                // Gunakan format uc export view yang lebih reliable
                return "https://drive.google.com/uc?id={$fileId}";
            }
        }

        // Jika tidak ada pattern yang match, return URL asli
        return $url;
    }

    /**
     * Proxy untuk menampilkan gambar dari Google Drive untuk mengatasi masalah CORS/X-Frame-Options.
     */
    protected function image_proxy()
    {
        $url = $this->input->get('url');

        if (empty($url)) {
            return show_404();
        }
        
        $url = urldecode($url);

        // Gunakan cURL untuk mengambil gambar dan menangani redirect
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

        $imageData = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        curl_close($ch);

        if ($httpCode == 200 && $imageData && strpos($contentType, 'image/') !== false) {
            header('Content-Type: ' . $contentType);
            header('Content-Length: ' . strlen($imageData));
            echo $imageData;
            exit;
        }

        // Jika gagal, tampilkan 404
        return show_404();
    }
}
