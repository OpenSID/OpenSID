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

use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Status_desa extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('data_publik');
        $this->modul_ini     = 200;
        $this->sub_modul_ini = 101;
    }

    public function index()
    {
        $kode_desa = $this->header['desa']['kode_desa'];
        $tahun     = session('tahun') ?? ($this->input->post('tahun') ?? ($this->setting->tahun_idm));
        $cache     = 'idm_' . $tahun . '_' . $kode_desa;

        if (cek_koneksi_internet()) {
            $this->data_publik
                ->set_api_url(config_item('api_idm') . "/{$kode_desa}/{$tahun}", $cache)
                ->set_interval(7)
                ->set_cache_folder(config_item('cache_path'));

            $idm = $this->data_publik->get_url_content();
            if (! $idm->body || $idm->body->error) {
                $idm->body->mapData->error_msg = ($idm->body->message ? '<a href="' . $idm->header->url . ' ">' . $idm->header->url . '</a>' : 'Tidak dapat mengambil data IDM') . '<br><br>Periksa Kode Desa di ' . SebutanDesa('Identitas [Desa]') . ' dan masukkan kode lengkap. Contoh : 3507012006 <br>';
            }

            $data = [
                'tahun' => (int) $tahun,
                'idm'   => $idm->body->mapData,
            ];
        }

        return view('admin.status_desa.index', $data);
    }

    public function perbarui(int $tahun)
    {
        if (cek_koneksi_internet() && $tahun) {
            $kode_desa = $this->header['desa']['kode_desa'];
            $cache     = 'idm_' . $tahun . '_' . $kode_desa . '.json';

            $this->cache->file->delete($cache);
            set_session('tahun', $tahun);
        }

        redirect_with('success', 'Berhasil Perbarui Data');
    }

    public function simpan(int $tahun)
    {
        SettingAplikasi::where('key', 'tahun_idm')->update(['value' => $tahun]);
        set_session('tahun', $tahun);

        redirect_with('success', 'Berhasil Simpan Data');
    }
}
