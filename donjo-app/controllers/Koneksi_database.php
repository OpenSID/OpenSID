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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Config;

defined('BASEPATH') || exit('No direct script access allowed');

class Koneksi_database extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

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

    public function updateKey()
    {
        $this->cekConfig();

        $appKeyDb = Config::first();
        resetCacheDesa();
        updateAppKey($appKeyDb->app_key);

        redirect(site_url());
    }

    public function desaBaru()
    {
        if ($this->session->cek_app_key) {
            // Tambahkan data sementara
            Config::create([
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

            // hapus cache
            resetCacheDesa();

            // hapus session
            session_destroy();
        }

        redirect(site_url());
    }

    private function cekConfig()
    {
        if (! $this->session->cek_app_key) {
            redirect(site_url());
        }

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
}
