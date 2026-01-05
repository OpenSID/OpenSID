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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Repositories\SettingAplikasiRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use Modules\Anjungan\Models\Anjungan;
use Modules\Pelanggan\Services\PelangganService;

defined('BASEPATH') || exit('No direct script access allowed');

class PelangganController extends AdminModulController
{
    public $moduleName          = 'Pelanggan';
    public $modul_ini           = 'info-desa';
    public $sub_modul_ini       = 'layanan-pelanggan';
    public $kategori_pengaturan = 'pelanggan';

    /**
     * @var Client HTTP Client
     */
    protected Client $client;

    protected $server;

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        // jangan aktifkan jika demo dan di domain whitelist
        if (config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO)) {
            show_404();
        }

        $this->client = new Client();
    }

    public function index(): void
    {
        unset($this->header['perbaharui_langganan']);

        $response        = PelangganService::apiPelangganPemesanan();
        $notif_langganan = PelangganService::statusLangganan();

        // Ubah layanan_opendesa_token terbaru, jangan perbaharui jika token tersimpan di config (untuk developmen)
        if ((null !== $response && $response->body->token !== setting('layanan_opendesa_token')) && empty(config_item('token_layanan'))) {
            (new SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $response->body->token);

            redirect('pelanggan');
        }

        view('pelanggan::index', [
            'title'           => 'Info Layanan Pelanggan',
            'response'        => $response,
            'notif_langganan' => $notif_langganan,
            'server'          => config_item('server_layanan'),
            'token'           => setting('layanan_opendesa_token'),
        ]);
    }

    public function peringatan(): void
    {
        $error_premium = $this->session->error_premium;
        $pesan         = $this->session->error_premium_pesan;

        // hapus auto perbarui
        unset($this->header['perbaharui_langganan']);

        $response        = PelangganService::apiPelangganPemesanan();
        $notif_langganan = PelangganService::statusLangganan();

        view('pelanggan::index', [
            'title'           => 'Info Peringatan',
            'response'        => $response,
            'notif_langganan' => $notif_langganan,
            'error_premium'   => $error_premium,
            'pesan'           => $pesan,
        ]);
    }

    public function perbarui(): void
    {
        hapus_cache('tema_premium');
        cache()->forget('siappakai');
        cache()->forget('modul_aktif');
        cache()->forget('anjungan_aktif');
        session_success();
        sleep(3);
        redirect('pelanggan');
    }

    public function perpanjangLayanan(): void
    {
        view('pelanggan::perpanjang_layanan', [
            'title'        => 'Layanan Pelanggan',
            'pemesanan_id' => $_GET['pemesanan_id'],
            'server'       => $_GET['server'],
            'invoice'      => $_GET['invoice'],
            'token'        => $_GET['token'],
        ]);
    }

    public function perpanjang()
    {
        $this->load->library('upload');
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['file_name']     = 'dokumen-permohonan.pdf';
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 1024;
        $config['overwrite']     = true;
        $this->upload->initialize($config);

        try {
            $this->upload->do_upload('permohonan');
            $uploadData = $this->upload->data();
            $this->client->post(config_item('server_layanan') . '/api/v1/pelanggan/perpanjang', [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'pemesanan_id', 'contents' => (int) $this->input->post('pemesanan_id')],
                    ['name' => 'permohonan', 'contents' => Psr7\Utils::tryFopen(LOKASI_DOKUMEN . $uploadData['file_name'], 'r')],
                ],
            ])
                ->getBody();
        } catch (ClientException $cx) {
            log_message('error', $cx);
            // set_session('errors', json_decode($cx->getResponse()->getBody(), null));
            $this->session->set_flashdata(['errors' => json_decode($cx->getResponse()->getBody(), null)]);
            session_error();

            return redirect('pelanggan');
        } catch (Exception $e) {
            log_message('error', $e);
            session_error();

            return redirect('pelanggan');
        }

        hapus_cache('status_langganan');
        hapus_cache('tema_premium');
        session_success();
        sleep(3);
        redirect('pelanggan');
    }

    public function pemesanan()
    {
        $this->load->helper('file');

        if (! $this->input->is_ajax_request()) {
            return json([
                'status'  => false,
                'message' => 'Invalid request method.',
            ], 400);
        }

        if (config_item('demo_mode')) {
            return json([
                'status'  => false,
                'message' => 'Tidak dapat mengganti token pada website demo.',
            ], 400);
        }

        $token  = $this->request['body']['token'] ?? null;
        $desaId = $this->request['body']['desa_id'] ?? null;

        logger()->info(collect($this->request)->toJson(128));

        if (empty($token)) {
            return json([
                'status'  => false,
                'message' => 'Token tidak ditemukan dalam response API layanan. Harap periksa kembali response dari server.',
            ], 400);
        }

        if (! isset($this->request['body']) || empty($this->request['body'])) {
            return json([
                'status'  => false,
                'message' => 'Response data pemesanan dari API layanan kosong atau tidak valid.',
            ], 400);
        }

        $kodeDesa = kode_wilayah($this->header['desa']['kode_desa']);

        if ($desaId != $kodeDesa) {
            $namaDesa = ucwords(setting('sebutan_desa') . ' ' . $this->header['desa']['nama_desa']);
            $server   = config_item('server_layanan');

            return json([
                'status'  => false,
                'message' => "{$namaDesa} tidak terdaftar di {$server} atau Token tidak sesuai dengan kode desa",
            ], 400);
        }

        // Hapus cache lama
        hapus_cache('status_langganan');
        hapus_cache('tema_premium');
        cache()->forget('identitas_desa');

        // Update token di file config jika tersedia
        $configPath = LOKASI_CONFIG_DESA . '/config.php';
        if (config_item('token_layanan') != null) {
            $config  = file($configPath);
            $updated = array_map(
                static fn ($line): string => stristr($line, 'token_layanan')
                    ? "\$config['token_layanan']  = '{$token}';\n"
                    : $line,
                $config
            );
            file_put_contents($configPath, implode('', $updated));
        }

        // Simpan token ke database
        (new SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);

        // Simpan cache baru dengan durasi 30 tahun (forever)
        $data = json_decode(json_encode($this->request, JSON_THROW_ON_ERROR), false);
        $this->cache->pakai_cache(
            static fn () => $data,
            'status_langganan',
            60 * 60 * 24 * 365 * 30 // 30 tahun
        );

        // Update status Anjungan
        Anjungan::where('tipe', '1')
            ->where('status', '0')
            ->where('status_alasan', 'tidak berlangganan anjungan')
            ->update(['status' => '1']);

        return json([
            'status'  => true,
            'message' => 'Token berhasil tersimpan',
        ]);
    }
}
