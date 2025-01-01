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

defined('BASEPATH') || exit('No direct script access allowed');

class Optimasi_gambar extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'optimasi-gambar';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $folders = $this->get_folders(LOKASI_UPLOAD);

        return view('admin.optimasi_gambar.index', ['folders' => $folders]);
    }

    public function get_image($dir = null)
    {
        if (! $dir) {
            $folders = $this->get_folders(LOKASI_UPLOAD)->map(static fn ($dir): string => LOKASI_UPLOAD . $dir);
        } else {
            $folders = [LOKASI_UPLOAD . $dir];
        }
        $files = collect();

        foreach ($folders as $path) {
            $images = collect(array_diff(scandir($path), ['.', '..']))->filter(static function (string $file) use ($path) {
                $image_size = getimagesize($path . DIRECTORY_SEPARATOR . $file);
                if ($image_size == false) {
                    return;
                }
                if ($image_size[0] > '880') {
                    return $file;
                }

                if ($image_size[1] > '880') {
                    return $file;
                }
            })->map(static fn ($file): string => $path . DIRECTORY_SEPARATOR . $file);
            $files = $files->merge($images);
        }

        return json([
            'status' => true,
            'data'   => $files,
            'count'  => $files->count(),
        ]);
    }

    public function get_folders($path)
    {
        return collect(array_diff(scandir($path), ['.', '..']))
            ->filter(static fn ($dir) => $dir !== 'fonts')
            ->filter(static fn ($dir): bool => is_dir($path . DIRECTORY_SEPARATOR . $dir));
    }

    public function resize()
    {
        try {
            $request = $this->input->post();
            ResizeGambar($request['file'], $request['file'], ['width' => 880, 'height' => 880]);
        } catch (Exception $e) {
            return json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }

        return json([
            'status'  => true,
            'message' => 'berhasil',
        ]);
    }
}
