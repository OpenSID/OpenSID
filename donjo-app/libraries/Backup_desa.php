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

use App\Models\LogBackup;
use Carbon\Carbon;

class Backup_desa
{
    /**
     * @var CI_Controller
     */
    protected $ci;

    public function __construct()
    {
        $this->ci = get_instance();

        $this->ci->load->helper(['number', 'file']);
        $this->ci->load->library('zip');
    }

    /**
     * Backup inkremental folder desa.
     *
     * @return void
     */
    public function inkremental()
    {
        $separator   = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '\\' : '/';
        $last_backup = LogBackup::latest()->first()->created_at;
        $path        = DESAPATH . 'cache' . $separator . namafile('backup') . '.zip';
        $backup      = LogBackup::create(['path' => $path]); // tandai backup sedang berlangsung
        $directory   = new \RecursiveDirectoryIterator(DESAPATH);
        $iterator    = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            if ($info->getFilename() != '.' && $info->getFilename() != '..' && $info->getPath() != 'desa' . $separator . 'cache') {
                $file_info = get_file_info($info->getPathname());
                if ($last_backup == null) {
                    $this->ci->zip->read_file($info->getPathname(), true);
                    $this->ci->zip->archive($path);
                } elseif (Carbon::createFromTimestamp($file_info['date'])->gt($last_backup)) {
                    $this->ci->zip->read_file($info->getPathname(), true);
                    $this->ci->zip->archive($path);
                }
            }
        }

        $file_backup = get_file_info($path);
        $backup->update(['status' => 1, 'ukuran' => byte_format($file_backup['size'])]); // update backup sudah selesai
    }
}
