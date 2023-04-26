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

use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Status_desa extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('data_publik');
        $this->modul_ini          = 200;
        $this->sub_modul_ini      = 101;
        $this->header['kategori'] = 'status sdgs';
    }

    public function index()
    {
        if (session('navigasi') == 'sdgs') {
            return $this->sdgs();
        }

        return $this->idm();
    }

    private function idm()
    {
        $tahun = session('tahun') ?? ($this->input->post('tahun') ?? ($this->setting->tahun_idm) ?? date('Y'));

        $data = [
            'tahun' => (int) $tahun,
            'idm'   => idm($this->header['desa']['kode_desa'], $tahun),
        ];

        return view('admin.status_desa.idm', $data);
    }

    public function perbarui_idm(int $tahun)
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

    private function sdgs()
    {
        set_session('navigasi', 'sdgs');

        $sdgs      = sdgs();
        $kode_desa = $this->header['desa']['kode_desa'];

        return view('admin.status_desa.sdgs', compact('sdgs', 'kode_desa'));
    }

    public function perbarui_bps()
    {
        if ($this->input->is_ajax_request()) {
            $kode_bps = $this->request['kode_bps'];
            SettingAplikasi::where('key', 'kode_desa_bps')->update(['value' => $kode_bps]);

            return json([
                'status' => true,
            ]);
        }

        return json([
            'status'  => false,
            'message' => 'Akses tidak di ijinkan',
        ]);
    }

    public function perbarui_sdgs()
    {
        set_session('navigasi', 'sdgs');

        if (cek_koneksi_internet()) {
            $kode_desa = setting('kode_desa_bps');
            $cache     = 'sdgs_' . $kode_desa . '.json';

            $this->cache->file->delete($cache);
        }

        redirect_with('success', 'Berhasil Perbarui Data');
    }

    public function navigasi($navigasi = 'idm')
    {
        redirect_with('navigasi', $navigasi);
    }
}
