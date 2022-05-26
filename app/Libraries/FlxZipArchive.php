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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

namespace App\Libraries;

use ZipArchive;

// Compress keseluruhan folder, seperti folder desa
// https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
class FlxZipArchive extends ZipArchive
{
    public $tmp_file;

    public function read_dir($backup_folder)
    {
        // Simpan di temp file
        $this->tmp_file = tempnam(sys_get_temp_dir(), '');
        $res            = $this->open($this->tmp_file, ZipArchive::CREATE);
        if ($res === true) {
            $this->addDir($backup_folder, basename($backup_folder));
            $this->close();
        } else {
            echo 'Could not create a zip archive';
        }
    }

    public function download($nama_file)
    {
        // Unduh berkas zip
        header('Content-Description: File Transfer');
        header('Content-disposition: attachment; filename=' . $nama_file);
        header('Content-type: application/zip');
        flush();
        readfile_chunked($this->tmp_file);
    }

    public function addDir($location, $name)
    {
        $this->addEmptyDir($name);
        $this->addDirDo($location, $name);
    }

    private function addDirDo($location, $name)
    {
        $name     .= '/';
        $location .= '/';
        $dir = opendir($location);

        while ($file = readdir($dir)) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $do = (filetype($location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->{$do}($location . $file, $name . $file);
        }
    }
}
