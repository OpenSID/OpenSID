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

use App\Libraries\Checker;
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

    protected function uploadAll($file, $config = [], $redirectUrl = null, ?Closure $callback = null)
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

            redirect_with('error', $this->upload->display_errors(), $redirectUrl ?? $this->controller);
        }

        return null;
    }

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
     * @param string   $file    Nama field input file.
     * @param string   $lokasi  Path untuk menyimpan file.
     * @param int|null $size    Ukuran logo yang diinginkan.
     * @param bool     $webp    Konversi ke WebP.
     * @param bool     $favicon Buat favicon.
     *
     * @return string Nama file yang diunggah.
     */
    public function uploadGambar(string $file, string $lokasi, ?int $size = null, bool $webp = true, bool $favicon = false)
    {
        return $this->upload(
            file: $file,
            config: [
                'upload_path'   => $lokasi,
                'allowed_types' => 'gif|jpg|png|jpeg|webp',
                'max_size'      => max_upload() * 1024,
                'overwrite'     => true,
            ],
            callback: static function ($uploadData) use ($size, $favicon, $webp) {
                $ext      = strtolower(pathinfo($uploadData['full_path'], PATHINFO_EXTENSION));
                $filePath = $uploadData['file_path'];
                $rawName  = $uploadData['raw_name'];
                $fullPath = $uploadData['full_path'];

                if ($ext === 'gif') {
                    return "{$rawName}.gif";
                }

                if ($size) {
                    Image::load($fullPath)->width($size)->height($size)->save("{$filePath}{$rawName}.{$ext}");
                }

                if ($favicon) {
                    Image::load($fullPath)->width(16)->height(16)->save("{$filePath}favicon.ico");

                    copyFavicon();
                }

                if ($webp) {
                    Image::load($fullPath)->format(Manipulations::FORMAT_WEBP)->save("{$filePath}{$rawName}.webp");

                    unlink($fullPath);

                    $ext = 'webp';
                }

                return "{$rawName}.{$ext}";
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
}
