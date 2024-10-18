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

        $response        = $this->pelanggan_model->api_pelanggan_pemesanan();
        $notif_langganan = $this->pelanggan_model->status_langganan();

        kirim_versi_opensid();
        // Ubah layanan_opendesa_token terbaru, jangan perbaharui jika token tersimpan di config (untuk developmen)
        if ((null !== $response && $response->body->token !== $this->setting->layanan_opendesa_token) && empty(config_item('token_layanan'))) {
            $post['layanan_opendesa_token'] = $response->body->token;
            $this->setting_model->update_setting($post);

            redirect($this->controller);
        }

        $this->render('pelanggan/index', [
            'title'           => 'Info Layanan Pelanggan',
            'response'        => $response,
            'notif_langganan' => $notif_langganan,
        ]);
    }

    public function peringatan(): void
    {
        // hapus auto perbarui
        unset($this->header['perbaharui_langganan']);

        $response        = $this->pelanggan_model->api_pelanggan_pemesanan();
        $notif_langganan = $this->pelanggan_model->status_langganan();

        if (empty($this->session->error_premium)) {
            redirect('beranda');
        }

        $this->render('pelanggan/index', [
            'title'           => 'Info Peringatan',
            'response'        => $response,
            'notif_langganan' => $notif_langganan,
        ]);
    }

    public function perbarui(): void
    {
        kirim_versi_opensid();
        hapus_cache('status_langganan');
        cache()->forget('siappakai');
        session_success();
        sleep(3);
        redirect($this->controller);
    }

    public function perpanjang_layanan(): void
    {
        $this->render('pelanggan/perpanjang_layanan', ['pemesanan_id' => $_GET['pemesanan_id'], 'server' => $_GET['server'], 'invoice' => $_GET['invoice'], 'token' => $_GET['token']]);
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
                    // $this->setting_model->update_setting(['layanan_opendesa_token' => null]);

                    return json([
                        'status'  => false,
                        'message' => ucwords($this->setting->sebutan_desa . ' ' . $this->header['desa']['nama_desa']) . ' tidak terdaftar di ' . config_item('server_layanan') . ' atau Token yang di input tidak sesuai dengan kode desa',
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

                // TODO: Sederhanakan query ini, pindahkan ke model
                $this->db
                    ->set(['status' => '1'])
                    ->where('config_id', identitas('id'))
                    ->where('tipe', '1')
                    ->where('status', '0')
                    ->where('status_alasan', 'tidak berlangganan anjungan')
                    ->update('anjungan');

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
