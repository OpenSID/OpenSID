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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Traits;

use App\Models\Theme;
use Closure;
use Exception;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

trait Upload
{
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

        if (! is_dir($config['upload_path'])) {
            folder($config['upload_path'], '0755', 'htaccess1');
        }

        $this->load->library('upload');
        $this->upload->initialize($config);

        try {
            $upload = $this->upload->do_upload($file);

            if (! $upload) {
                if ($isAjax) {
                    return json(['error' => $this->upload->display_errors()], 400);
                }
                redirect_with('error', $this->upload->display_errors(), $redirectUrl ?? $this->controller);
            }

            $uploadData = $this->upload->data();

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

            redirect_with('error', $this->upload->display_errors(), $redirectUrl ?? $this->controller);
        }

        return null;
    }

    public function uploadImg($key = '', $lokasi = ''): string|false
    {
        return $this->upload(
            file: $key,
            config: [
                'upload_path'   => $lokasi,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'overwrite'     => true,
                'max_size'      => max_upload() * 1024,
            ],
            callback: static function ($uploadData) use($lokasi, $key) {
                $webpName = time() . $uploadData['raw_name'] . '.webp';

                Image::load($uploadData['full_path'])
                    ->format(Manipulations::FORMAT_WEBP)
                    ->save("{$uploadData['file_path']}{$webpName}");

                unlink($uploadData['full_path']);

                return $webpName;
            }
        );
    }

    public function uploadPicture($gambar = '', $lokasi = ''): string|false
    {
        return $this->upload(
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

                    return "{$rawName}.gif";
                }

                // Untuk selain GIF, proses seperti biasa
                Image::load($uploadData['full_path'])
                    ->format(Manipulations::FORMAT_WEBP)
                    ->save("{$filePath}{$rawName}.webp");

                Image::load($uploadData['full_path'])
                    ->width(440)
                    ->height(440)
                    ->save("{$filePath}kecil_{$rawName}.webp");

                Image::load($uploadData['full_path'])
                    ->width(880)
                    ->height(880)
                    ->save("{$filePath}sedang_{$rawName}.webp");

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
            $data['latar_website'] = $this->uploadImg('latar_website', (new Theme())->lokasiLatarWebsite());
        } else {
            $data['latar_website'] = setting('latar_website');
        }

        if ($data['latar_login']) {
            $data['latar_login'] = $this->uploadImg('latar_login', LATAR_LOGIN);
        } else {
            $data['latar_login'] = setting('latar_login');
        }

        if ($data['latar_login_mandiri']) {
            $data['latar_login_mandiri'] = $this->uploadImg('latar_login_mandiri', LATAR_LOGIN);
        } else {
            $data['latar_login_mandiri'] = setting('latar_login_mandiri');
        }

        if ($data['latar_kehadiran']) {
            $data['latar_kehadiran'] = $this->uploadImg('latar_kehadiran', LATAR_LOGIN);
        } else {
            $data['latar_kehadiran'] = setting('latar_kehadiran');
        }
    }

    public function uploadIcon(string $file, string $lokasi)
    {
        return $this->upload(
            file: $file,
            config: [
                'upload_path'   => $lokasi,
                'allowed_types' => 'jpg|png|jpeg|webp',
                'max_size'      => max_upload() * 1024,
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) {
                $extension = strtolower(pathinfo($uploadData['full_path'], PATHINFO_EXTENSION));
                $filePath  = $uploadData['file_path'];
                $rawName   = $uploadData['raw_name'];

                Image::load($uploadData['full_path'])->width(32)->height(32)->format(Manipulations::FORMAT_WEBP)->save("{$filePath}{$rawName}.webp");

                unlink($uploadData['full_path']);

                return "{$rawName}.webp";
            }
        );
    }

    public function uploadPeta(string $file, string $lokasi)
    {
        return $this->upload(
            file: $file,
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
                    return "{$rawName}.gif";
                }

                Image::load($uploadData['full_path'])->format(Manipulations::FORMAT_WEBP)->save("{$filePath}{$rawName}.webp");

                unlink($uploadData['full_path']);

                return "{$rawName}.webp";
            }
        );
    }

    public function uploadImgIdentitas($jenis = '', $resize = false, $ukuran = false): string|false
    {
        return $this->upload(
            file: $jenis,
            config: [
                'upload_path'   => LOKASI_LOGO_DESA,
                'allowed_types' => 'gif|jpg|png|jpeg|webp',
                'max_size'      => max_upload() * 1024,
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) use ($resize, $ukuran) {
                $extension = strtolower(pathinfo($uploadData['full_path'], PATHINFO_EXTENSION));
                $filePath  = $uploadData['file_path'];
                $rawName   = $uploadData['raw_name'];

                if ($extension === 'gif') {
                    // Jika GIF, cukup copy dan rename saja
                    return "{$rawName}.gif";
                }

                // Konversi ke WebP
                Image::load($uploadData['full_path'])
                    ->format(Manipulations::FORMAT_WEBP)
                    ->save("{$filePath}{$rawName}.webp");
                // Jika perlu resize
                if ($resize) {
                    Image::load($uploadData['full_path'])
                        ->width($ukuran)
                        ->height($ukuran)
                        ->save("{$filePath}{$rawName}.webp");

                    Image::load($uploadData['full_path'])
                        ->width(16)
                        ->height(16)
                        ->save("{$filePath}favicon.ico");

                    copyFavicon();
                }

                // Hapus file asli
                unlink($uploadData['full_path']);

                return "{$rawName}.webp";
            }
        );
    }
}
