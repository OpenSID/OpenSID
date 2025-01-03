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

use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Status_desa extends Admin_Controller
{
    public $modul_ini           = 'info-desa';
    public $sub_modul_ini       = 'status-desa';
    public $kategori_pengaturan = 'Status SDGs';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
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
        $tahun = session('tahun') ?? ($this->input->post('tahun') ?? (setting('tahun_idm')) ?? date('Y'));

        $data = [
            'tahun' => (int) $tahun,
            'idm'   => idm(identitas('kode_desa'), $tahun),
        ];

        return view('admin.status_desa.idm', $data);
    }

    public function perbarui_idm(int $tahun): void
    {
        if (cek_koneksi_internet() && $tahun) {
            $kode_desa = identitas('kode_desa');
            $cache     = 'idm_' . $tahun . '_' . $kode_desa . '.json';

            // Cek server Kemendes sebelum hapus cache
            try {
                $client   = new GuzzleHttp\Client();
                $response = $client->get(config_item('api_idm') . "/{$kode_desa}/{$tahun}", [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                    ],
                    'verify' => false,
                ]);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());

                redirect_with('error', 'Tidak dapat mengambil data IDM.');
            }

            if ($response?->getStatusCode() === 200 && ($response->getBody()->getContents() !== '' && $response->getBody()->getContents() !== '0')) {
                $this->cache->file->delete($cache);
                set_session('tahun', $tahun);

                redirect_with('success', 'Berhasil Perbarui Data');
            }

            redirect_with('error', 'Tidak dapat mengambil data IDM.');
        }

        redirect_with('error', 'Tidak dapat mengambil data IDM.');
    }

    public function simpan(int $tahun): void
    {
        SettingAplikasi::where('key', 'tahun_idm')->update(['value' => $tahun]);
        set_session('tahun', $tahun);
        (new SettingAplikasi())->flushQueryCache();
        redirect_with('success', 'Berhasil Simpan Data');
    }

    private function sdgs()
    {
        set_session('navigasi', 'sdgs');

        $data = [
            'sdgs'      => sdgs(),
            'kode_desa' => identitas('kode_desa'),
        ];

        return view('admin.status_desa.sdgs', $data);
    }

    public function perbarui_bps()
    {
        if ($this->input->is_ajax_request()) {
            $kode_bps = $this->request['kode_bps'];
            SettingAplikasi::where('key', 'kode_desa_bps')->update(['value' => $kode_bps]);
            (new SettingAplikasi())->flushQueryCache();

            return json([
                'status' => true,
            ]);
        }

        return json([
            'status'  => false,
            'message' => 'Akses tidak di ijinkan',
        ]);
    }

    public function perbarui_sdgs(): void
    {
        set_session('navigasi', 'sdgs');

        if (cek_koneksi_internet()) {
            $kode_desa = setting('kode_desa_bps');
            $cache     = 'sdgs_' . $kode_desa . '.json';

            // Cek server Kemendes sebelum hapus cache
            try {
                $client = new GuzzleHttp\Client();
                $client->get(config_item('api_sdgs') . $kode_desa, [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                    ],
                    'verify' => false,
                ]);

                $this->cache->file->delete($cache);

                redirect_with('success', 'Berhasil Perbarui Data');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }

        redirect_with('error', 'Tidak dapat mengambil data SDGS.');
    }

    public function navigasi($navigasi = 'idm'): void
    {
        redirect_with('navigasi', $navigasi);
    }
}
