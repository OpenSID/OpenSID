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

defined('BASEPATH') || exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class Pelanggan extends Admin_Controller
{
    /**
     * @var Client HTTP Client
     */
    protected $client;

    protected $server;

    public function __construct()
    {
        parent::__construct();
        $this->modul_ini          = 200;
        $this->sub_modul_ini      = 313;
        $this->header['kategori'] = 'pelanggan';

        $this->load->model(['surat_model', 'pamong_model']);
        $this->client = new Client();
        $this->server = config_item('server_layanan');
    }

    public function index()
    {
        $response = $this->notif_model->api_pelanggan_pemesanan();

        // Ubah layanan_opendesa_token terbaru, jangan perbaharui jika token tersimpan di config (untuk developmen)
        if ((null !== $response && $response->body->token !== $this->setting->layanan_opendesa_token) && empty(config_item('token_layanan'))) {
            $post['layanan_opendesa_token'] = $response->body->token;
            $this->setting_model->update_setting($post);

            redirect($this->controller);
        }

        $this->render('pelanggan/index', ['response' => $response]);
    }

    public function perbarui()
    {
        $this->cache->hapus_cache_untuk_semua('status_langganan');
        session_success();
        sleep(3);
        redirect($this->controller);
    }

    public function perpanjang_layanan()
    {
        $this->render('pelanggan/perpanjang_layanan', ['pemesanan_id' => $_GET['pemesanan_id'], 'server' => $_GET['server'], 'invoice' => $_GET['invoice'], 'token' => $_GET['token']]);
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
            $response = $this->client->post("{$this->server}/api/v1/pelanggan/perpanjang", [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'pemesanan_id', 'contents' => (int) $this->input->post('pemesanan_id')],
                    ['name' => 'permohonan', 'contents' => Psr7\Utils::tryFopen(LOKASI_DOKUMEN . 'dokumen-permohonan.pdf', 'r')],
                ],
            ])
                ->getBody();
        } catch (ClientException $cx) {
            log_message('error', $cx);
            $this->session->set_flashdata(['errors' => json_decode($cx->getResponse()->getBody())]);
            session_error();

            return redirect('pelanggan');
        } catch (Exception $e) {
            log_message('error', $e);
            session_error();

            return redirect('pelanggan');
        }

        $this->cache->hapus_cache_untuk_semua('status_langganan');
        session_success();
        sleep(3);
        redirect($this->controller);
    }
}
