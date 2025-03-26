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

use App\Models\Simbol as SimbolModel;
use App\Traits\Upload;

defined('BASEPATH') || exit('No direct script access allowed');

class Simbol extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'pemetaan';
    public $sub_modul_ini = 'pengaturan-peta';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['simbol'] = SimbolModel::get()->toArray();
        $data['tip']    = 6;

        return view('admin.simbol.index', $data);
    }

    public function tambah_simbol(): void
    {
        isCan('u');

        try {
            SimbolModel::create(['simbol' => $this->uploadGambar('simbol', LOKASI_SIMBOL_LOKASI, 32)]);
            redirect_with('success', 'Simbol berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Simbol gagal disimpan');
        }
    }

    public function delete_simbol($id = ''): void
    {
        isCan('h');

        try {
            SimbolModel::destroy($id);
            redirect_with('success', 'Simbol berhasil dihapus');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Simbol gagal dihapus');
        }
        redirect('simbol');
    }

    public function salin_simbol_default(): void
    {
        isCan('u');

        $this->salin_simbol();
        redirect('simbol');
    }

    public function salin_simbol(): void
    {
        $dir     = LOKASI_SIMBOL_LOKASI_DEF;
        $files   = scandir($dir);
        $new_dir = LOKASI_SIMBOL_LOKASI;
        $outp    = true;

        foreach ($files as $file) {
            if ($file !== '' && $file !== '.' && $file !== '..') {
                $source      = $dir . '/' . $file;
                $destination = $new_dir . '/' . $file;
                if (! file_exists($destination)) {
                    $outp   = $outp && copy($source, $destination);
                    $simbol = basename($file);

                    try {
                        SimbolModel::updateOrInsert(
                            ['simbol' => $simbol]
                        );
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                        redirect_with('error', 'Simbol gagal disalin');
                    }
                }
            }
        }
        redirect_with('success', 'Simbol berhasil disalin');
    }
}
