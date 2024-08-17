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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Simbol as SimbolModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Simbol extends Admin_Controller
{
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

        $this->upload_simbol();
        redirect('simbol');
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
            if ($file !== '' && $file != '.' && $file != '..') {
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

    public function upload_simbol(): void
    {
        $config['upload_path']   = LOKASI_SIMBOL_LOKASI;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->load->library('MY_Upload', null, 'upload');
        $namaFile = $_FILES['simbol']['full_path'];
        if (strlen($namaFile) > 27) {
            $config['file_name'] = 'simbol_' . time();   // maksimal 40 karakter di db
        }
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('simbol')) {
            session_error($this->upload->display_errors());

            return;
        }

        $uploadedImage = $this->upload->data();
        ResizeGambar($uploadedImage['full_path'], $uploadedImage['full_path'], ['width' => 32, 'height' => 32]); // ubah ukuran gambar
        $data['simbol'] = $uploadedImage['file_name'];

        try {
            SimbolModel::create($data);
            redirect_with('success', 'Simbol berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Simbol gagal disimpan');
        }
    }
}
