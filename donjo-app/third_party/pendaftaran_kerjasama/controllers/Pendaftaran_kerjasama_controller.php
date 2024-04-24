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

use App\Models\Pamong;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class Pendaftaran_kerjasama_controller extends Admin_Controller
{
    public $modul_ini     = 'info-desa';
    public $sub_modul_ini = 'pendaftaran-kerjasama';

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

        $this->load->model(['surat_model', 'pamong_model']);
        $this->client = new Client();
        $this->server = config_item('server_layanan');
    }

    public function index()
    {
        return view('admin.pendaftaran_kerjasama.pendaftaran', []);
    }

    public function terdaftar(): void
    {
        $data = json_decode(json_encode($this->request, JSON_THROW_ON_ERROR), null);
        $this->load->view('pendaftaran_kerjasama/terdaftar', $data, false);
    }

    public function form(): void
    {
        $data = json_decode(json_encode($this->request, JSON_THROW_ON_ERROR), null);
        $this->load->view('pendaftaran_kerjasama/form', $data, false);
    }

    public function register()
    {
        isCan('u');

        $this->load->library('upload');
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['file_name']     = 'dokumen-permohonan.pdf';
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 1024;
        $config['overwrite']     = true;
        $this->upload->initialize($config);

        try {
            $this->upload->do_upload('permohonan');
            $response = $this->client->post("{$this->server}/api/v1/pelanggan/register", [
                'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                'multipart' => [
                    ['name' => 'user_id', 'contents' => (int) $this->input->post('user_id')],
                    ['name' => 'email', 'contents' => email($this->input->post('email'))],
                    ['name' => 'desa', 'contents' => bilangan_titik($this->input->post('desa'))],
                    ['name' => 'domain', 'contents' => alamat_web($this->input->post('domain'))],
                    ['name' => 'kontak_no_hp', 'contents' => bilangan($this->input->post('kontak_no_hp'))],
                    ['name' => 'kontak_nama', 'contents' => nama($this->input->post('kontak_nama'))],
                    ['name' => 'status_langganan', 'contents' => (int) $this->input->post('status_langganan_id')],
                    ['name' => 'permohonan', 'contents' => Psr7\Utils::tryFopen(LOKASI_DOKUMEN . 'dokumen-permohonan.pdf', 'r')],
                ],
            ])
                ->getBody();
        } catch (ClientException $cx) {
            log_message('error', $cx);
            $error = json_decode($cx->getResponse()->getBody(), null);
            $this->session->set_flashdata(['errors' => $error]);
            session_error();

            return redirect('pendaftaran_kerjasama/form');
        } catch (Exception $e) {
            try {
                $response = $this->client->post("{$this->server}/api/v1/pelanggan/register", [
                    'headers'   => ['X-Requested-With' => 'XMLHttpRequest'],
                    'multipart' => [
                        ['name' => 'user_id', 'contents' => (int) $this->input->post('user_id')],
                        ['name' => 'email', 'contents' => email($this->input->post('email'))],
                        ['name' => 'desa', 'contents' => bilangan_titik($this->input->post('desa'))],
                        ['name' => 'domain', 'contents' => alamat_web($this->input->post('domain'))],
                        ['name' => 'kontak_no_hp', 'contents' => bilangan($this->input->post('kontak_no_hp'))],
                        ['name' => 'kontak_nama', 'contents' => nama($this->input->post('kontak_nama'))],
                        ['name' => 'status_langganan', 'contents' => (int) $this->input->post('status_langganan_id')],
                        ['name' => 'permohonan', 'contents' => 0],
                    ],
                ])
                    ->getBody();
            } catch (Exception $e) {
                log_message('error', $e);
                session_error();

                return redirect('pendaftaran_kerjasama/form');
            }
        }

        $this->setting_model->update_setting([
            'layanan_opendesa_token' => json_decode($response, null)->data->token,
        ]);

        $this->session->success = 1;

        return redirect('pendaftaran_kerjasama');
    }

    public function dokumen_template(): void
    {
        $date = new DateTime();
        $desa = $this->header['desa'];

        $data['desa']         = $desa['nama_desa'];
        $data['logo']         = $desa['logo'];
        $data['random']       = substr(str_shuffle('0123456789'), 0, 4);
        $data['hari']         = $date->format('d');
        $data['nama_hari']    = ucwords(hari($date->getTimestamp()));
        $data['nama_tanggal'] = ucwords(to_word($date->format('d')));
        $data['bulan']        = $date->format('m');
        $data['nama_bulan']   = ucwords(getBulan($date->format('m')));
        $data['tahun']        = $date->format('Y');
        $data['nama_tahun']   = ucwords(to_word($date->format('Y')));
        $data['kepala_desa']  = strtoupper(Pamong::kepalaDesa()->first()->pamong_nama);
        $data['alamat']       = $desa['alamat_kantor'];
        $data['stempel']      = to_base64(STEMPEL);
        $data['layanan_logo'] = to_base64(LAYANAN_LOGO);

        $this->load->view('pendaftaran_kerjasama/template', $data);
    }
}
