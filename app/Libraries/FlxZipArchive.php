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

defined('BASEPATH') || exit('No direct script access allowed');

// Compress keseluruhan folder, seperti folder desa
// https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
class FlxZipArchive extends ZipArchive
{
    public $tmp_file;
    public $waktu_backup_terakhir;

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
            $this->addDir($backup_folder, basename($backup_folder));
            $this->close();

            return $this->tmp_file;
        }
        echo 'Could not create a zip archive';

        return null;
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
            $do        = (filetype($location . $file) == 'dir') ? 'addDir' : 'addFile';
            $file_info = get_file_info($location . $file);

            if ($this->waktu_backup_terakhir != null && ($do === 'addFile' && ! Carbon::createFromTimestamp($file_info['date'])->gt($this->waktu_backup_terakhir))) {
                continue;
            }

            $this->{$do}($location . $file, $name . $file);
        }
    }
}
