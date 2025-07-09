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

use App\Models\Config;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Koneksi_database extends CI_Controller
{
    public function index()
    {
        if ($this->session->db_error['code'] !== 1049) {
            redirect(site_url());
        }

        return view('periksa.koneksi');
    }

    public function config()
    {
        return view('periksa.config', $this->cekConfig());
    }

    public function updateKey(): void
    {
        $this->cekConfig();

        $appKeyDb = Config::first();
        resetCacheDesa();
        updateAppKey($appKeyDb->app_key);
        // setelah appKey diganti, password terenkrip harus diganti juga
        $password = (new Config())->getConnection()->getConfig('password');
        updateConfigFile('password', $password);

        redirect(ci_route('koneksi_database.encryptPassword'));
    }

    public function desaBaru(): void
    {
        $this->load->database();
        if (Config::appKey()->count() == 0) {
            reset_auto_increment('config');

            // Tambahkan data sementara
            Config::create([
                'app_key'           => get_app_key(),
                'nama_desa'         => '',
                'kode_desa'         => '',
                'nama_kecamatan'    => '',
                'kode_kecamatan'    => '',
                'nama_kabupaten'    => '',
                'kode_kabupaten'    => '',
                'nama_propinsi'     => '',
                'kode_propinsi'     => '',
                'nama_kepala_camat' => '',
                'nip_kepala_camat'  => '',
            ]);

            $this->load->model('migrations/data_awal', 'data_awal');
            $this->data_awal->up();

            DB::table('migrasi')->truncate();
            $this->load->model('database_model');
            $this->database_model->cek_migrasi(true);

            // hapus cache
            resetCacheDesa();

            // hapus session
            session_destroy();
        }

    }

    private function cekConfig(): array
    {
        if (! $this->session->cek_app_key) {
            redirect(site_url());
        }
        $this->load->database();
        $appKey   = get_app_key();
        $appKeyDb = Config::first();

        if (Config::count() > 1) {
            $appKeyDb = Config::appKey()->first();
        }

        if ($appKey === $appKeyDb->app_key) {
            redirect(site_url());
        }

        return [
            'appKey'   => $appKey,
            'appKeyDb' => $appKeyDb->app_key,
        ];
    }

    public function encryptPassword(): void
    {
        // setelah appKey diganti, password terenkrip harus diganti juga
        $password = (new Config())->getConnection()->getConfig('password');
        updateConfigFile('password', encrypt($password));
        redirect(site_url());
    }
}
