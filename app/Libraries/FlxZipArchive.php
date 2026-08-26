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

namespace App\Libraries;

use Carbon\Carbon;
use ZipArchive;

// Compress keseluruhan folder, seperti folder desa
// https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
class FlxZipArchive extends ZipArchive
{
    public $tmp_file;
    public $waktu_backup_terakhir;

    /**
     * premium#6964: symlink yang dilewati saat addDirDo() (path-di-zip => target
     * asli, mis. `desa/themes/<tema>` kategori C SiapPakai → `master-tema-pro/<tema>`).
     * Dicatat, bukan cuma dibuang, agar staf tahu symlink apa yang perlu dibuat
     * ulang secara manual saat memulihkan arsip ini ke server lain — lihat
     * {@see self::tulisManifestSymlink()}.
     *
     * @var array<string, string>
     */
    public array $symlinkDilewati = [];

    public function read_dir(string $backup_folder, $waktu_backup_terakhir = null, $archive = null)
    {
        // Simpan di temp file
        if ($waktu_backup_terakhir != null) {
            if ($archive != null) {
                $this->tmp_file = tempnam(BACKUPPATH, $waktu_backup_terakhir);
            } else {
                $this->tmp_file = tempnam(sys_get_temp_dir(), $waktu_backup_terakhir);
            }
        } else {
            $this->tmp_file = tempnam(sys_get_temp_dir(), '');
        }

        $this->waktu_backup_terakhir = ($waktu_backup_terakhir == null) ? null : Carbon::parse($waktu_backup_terakhir);
        $res                         = $this->open($this->tmp_file, ZipArchive::CREATE);
        if ($res === true) {
            $rootName = basename($backup_folder);
            $this->addDir($backup_folder, $rootName);
            $this->tulisManifestSymlink($rootName);
            $this->close();

            return $this->tmp_file;
        }
        echo 'Could not create a zip archive';

        return null;
    }

    /**
     * Tulis manifest JSON berisi symlink yang dilewati (bila ada) ke root arsip.
     * Sekali per arsip (dipanggil dari read_dir() setelah addDir() top-level
     * selesai) — addDirDo() mengumpulkan symlink dari SELURUH kedalaman folder
     * ke dalam satu array {@see self::$symlinkDilewati} milik instance ini.
     */
    private function tulisManifestSymlink(string $rootName): void
    {
        if ($this->symlinkDilewati === []) {
            return;
        }

        $this->addFromString(
            $rootName . '/SYMLINK_DILEWATI.json',
            json_encode($this->symlinkDilewati, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    public function download(string $nama_file): never
    {
        // Unduh berkas zip
        header('Content-Description: File Transfer');
        header('Content-disposition: attachment; filename=' . $nama_file);
        header('Content-type: application/zip');
        flush();
        readfile_chunked($this->tmp_file);

        exit();
    }

    public function addDir(string $location, string $name): void
    {
        $this->addEmptyDir($name);
        $this->addDirDo($location, $name);
    }

    private function addDirDo(string $location, string $name): void
    {
        $name     .= '/';
        $location .= '/';
        $dir = opendir($location);

        while ($file = readdir($dir)) {
            if ($file === '.') {
                continue;
            }
            if ($file === '..') {
                continue;
            }

            $fullPath = $location . $file;

            // premium#6964: JANGAN ikuti symlink (mis. `desa/themes/<tema>` kategori
            // C SiapPakai, symlink ke folder master `master-tema-pro/` dibagi banyak
            // tenant). filetype() di bawah resolve LEWAT symlink (mengembalikan 'dir'
            // untuk symlink ke folder) — tanpa guard ini, seluruh isi folder TARGET
            // tersalin utuh ke backup incremental SATU tenant (kebalikan dari
            // desa_backup() yang melewati symlink via Storage::disk('desa') links=skip).
            if (is_link($fullPath)) {
                $this->symlinkDilewati[$name . $file] = (string) readlink($fullPath);

                continue;
            }

            $do        = (filetype($fullPath) == 'dir') ? 'addDir' : 'addFile';
            $file_info = get_file_info($fullPath);

            if ($this->waktu_backup_terakhir != null && ($do === 'addFile' && ! Carbon::createFromTimestamp($file_info['date'])->gt($this->waktu_backup_terakhir))) {
                continue;
            }

            $this->{$do}($fullPath, $name . $file);
        }
    }
}
