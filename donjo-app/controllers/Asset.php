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

use Illuminate\Http\File;
use Symfony\Component\Mime\MimeTypes;

class Asset extends Web_Controller
{
    public function serveTheme()
    {
        $filename  = explode('?', request()->get('file'))[0];
        $path      = FCPATH . theme_full_path() . '/assets/' . $filename;
        $file      = new File($path);
        $mimeType  = $file->getMimeType();
        $mimeTypes = new MimeTypes();
        $mimeType  = $mimeTypes->getMimeTypes($file->getExtension())[0] ?? 'application/octet-stream';

        header('Content-Length: ' . $file->getSize());
        header('Content-Type: ' . $mimeType);
        header('Pragma: cache');
        header('Cache-Control: public, max-age=2592000');

        readfile($path);

        exit;
    }

    public function serveModule($moduleName)
    {
        $originalModule = $this->getOriginalModule($moduleName);
        $filename       = explode('?', request()->get('file'))[0];

        $path      = module_path($originalModule) . '/Views/assets/' . $filename;
        $file      = new File($path);
        $mimeType  = $file->getMimeType();
        $mimeTypes = new MimeTypes();
        $mimeType  = $mimeTypes->getMimeTypes($file->getExtension())[0] ?? 'application/octet-stream';

        header('Content-Length: ' . $file->getSize());
        header('Content-Type: ' . $mimeType);
        header('Pragma: cache');
        header('Cache-Control: public, max-age=2592000');

        readfile($path);

        exit;
    }

    private function getOriginalModule($moduleName)
    {
        $originalModule = ucfirst($moduleName);

        switch($moduleName) {
            case 'bukutamu':
                $originalModule = 'BukuTamu';
                break;

            case 'ppid':
                $originalModule = 'PPID';
                break;
        }

        return $originalModule;
    }
}
