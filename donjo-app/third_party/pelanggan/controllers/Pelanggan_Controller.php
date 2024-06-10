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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Anjungan;
use App\Services\Pelanggan;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class Pelanggan_Controller extends Admin_Controller
{
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

        $response        = Pelanggan::api_pelanggan_pemesanan();
        $notif_langganan = Pelanggan::status_langganan();

        // Ubah layanan_opendesa_token terbaru, jangan perbaharui jika token tersimpan di config (untuk developmen)
        if ((null !== $response && $response->body->token !== setting('layanan_opendesa_token')) && empty(config_item('token_layanan'))) {
            $post['layanan_opendesa_token'] = $response->body->token;
            $this->setting_model->update_setting($post);

            redirect($this->controller);
        }

        view('admin.pelanggan.index', [
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

        $response        = Pelanggan::api_pelanggan_pemesanan();
        $notif_langganan = Pelanggan::status_langganan();

        view('admin.pelanggan.index', [
            'title'           => 'Info Peringatan',
            'response'        => $response,
            'notif_langganan' => $notif_langganan,
            'error_premium'   => $error_premium,
            'pesan'           => $pesan,
        ]);
    }

    public function perbarui(): void
    {
        hapus_cache('status_langganan');
        cache()->forget('siappakai');
        session_success();
        sleep(3);
        redirect($this->controller);
    }

    public function perpanjang_layanan(): void
    {
        view('admin.pelanggan.perpanjang_layanan', [
            'title'        => 'Layanan Pelanggan',
            'pemesanan_id' => $_GET['pemesanan_id'],
            'server'       => $_GET['server'],
            'invoice'      => $_GET['invoice'],
            'token'        => $_GET['token'],
        ]);
    }

    public function perpanjang()
    {
        $this->load->library('MY_Upload', null, 'upload');
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['file_name']     = 'dokumen-permohonan.pdf';
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 1024;
        $config['overwrite']     = true;
        $this->upload->initialize($config);

        try {
            $this->upload->do_upload('permohonan');
            $this->client->post(config_item('server_layanan') . '/api/v1/pelanggan/perpanjang', [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'pemesanan_id', 'contents' => (int) $this->input->post('pemesanan_id')],
                    ['name' => 'permohonan', 'contents' => Psr7\Utils::tryFopen(LOKASI_DOKUMEN . 'dokumen-permohonan.pdf', 'r')],
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
        session_success();
        sleep(3);
        redirect($this->controller);
    }

    public function pemesanan()
    {
        $this->load->helper('file');
        if ($this->input->is_ajax_request()) {
            if (config_item('demo_mode')) {
                cache()->forget('identitas_desa');
                hapus_cache('status_langganan');
                $this->cache->pakai_cache(fn () => // request ke api layanan.opendesa.id
                    json_decode(json_encode($this->request, JSON_THROW_ON_ERROR), false), 'status_langganan', 24 * 60 * 60);

                return json([
                    'status'  => false,
                    'message' => 'Tidak dapat mengganti token pada wabsite demo.',
                ]);
            }

            if (isset($this->request['body']['token'])) {
                hapus_cache('status_langganan');
                cache()->forget('identitas_desa');
                if ($this->request['body']['desa_id'] != kode_wilayah($this->header['desa']['kode_desa'])) {

                    return json([
                        'status'  => false,
                        'message' => ucwords(setting('sebutan_desa') . ' ' . $this->header['desa']['nama_desa']) . ' tidak terdaftar di ' . config_item('server_layanan') . ' atau Token yang di input tidak sesuai dengan kode desa',
                    ]);
                }

                // periksa file config dan ganti token jika tersedia
                if (config_item('token_layanan') != null) {
                    file_put_contents(LOKASI_CONFIG_DESA . '/config.php', implode(
                        '',
                        array_map(fn ($data): string => stristr($data, 'token_layanan') ? "\$config['token_layanan']  = '" . $this->request['body']['token'] . "';\n" : $data, file(LOKASI_CONFIG_DESA . '/config.php'))
                    ));
                }

                $post['layanan_opendesa_token'] = $this->request['body']['token'];
                $this->setting_model->update_setting($post);

                $this->cache->pakai_cache(fn () => // request ke api layanan.opendesa.id
                    json_decode(json_encode($this->request, JSON_THROW_ON_ERROR), false), 'status_langganan', 24 * 60 * 60);

                Anjungan::where('tipe', '1')
                    ->where('status', '0')
                    ->where('status_alasan', 'tidak berlangganan anjungan')
                    ->update(['status' => '1']);

                return json([
                    'status'  => true,
                    'message' => 'Token berhasil tersimpan',
                ]);
            }

            return json([
                'status'  => false,
                'message' => 'Token tidak ada.',
            ]);
        }
    }
}
