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

use App\Enums\Statistik\StatistikEnum;
use App\Libraries\Keuangan;
use App\Models\PendudukSaja;
use App\Models\Widget;

defined('BASEPATH') || exit('No direct script access allowed');

class First extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        parent::clear_cluster_session();

        // $this->load->library('security/security_header', null, 'security_header');
        // $this->security_header->handle();

        // $this->load->library('security/security_trusted_host', null, 'security_trusted_host');
        // $this->security_trusted_host->handle();

        $this->load->model('first_artikel_m');
        $this->load->model('analisis_import_model');
    }

    public function unduh_dokumen_artikel($id): void
    {
        // Ambil nama berkas dari database
        $dokumen = $this->first_artikel_m->get_dokumen_artikel($id);
        ambilBerkas($dokumen, $this->controller, null, LOKASI_DOKUMEN);
    }

    public function statistik($stat = null, $tipe = 0): void
    {
        if ($slug = StatistikEnum::slugFromKey($stat)) {
            redirect('data-statistik/' . $slug);
        }

        show_404();
    }

    public function kelompok($slug = ''): void
    {
        redirect('data-kelompok/' . $slug);
    }

    public function suplemen($slug = ''): void
    {
        redirect('data-suplemen/' . $slug);
    }

    public function dpt(): void
    {
        redirect('data-dpt');
    }

    public function add_comment($id = 0): void
    {
        $this->form_validation->set_rules('komentar', 'Komentar', 'required');
        $this->form_validation->set_rules('owner', 'Nama', 'required|max_length[50]');
        $this->form_validation->set_rules('no_hp', 'No HP', 'numeric|required|max_length[15]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|max_length[50]');

        $post = $this->input->post();

        if ($this->form_validation->run() == true) {
            // Periksa isian captcha
            $captcha = new App\Libraries\Captcha();
            if (! $captcha->check($this->input->post('captcha_code'))) {
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'Kode anda salah. Silakan ulangi lagi.',
                    'data'   => $post,
                ];
            } else {
                $data = [
                    'komentar'   => htmlentities($post['komentar']),
                    'owner'      => htmlentities($post['owner']),
                    'no_hp'      => bilangan($post['no_hp']),
                    'email'      => email($post['email']),
                    'status'     => 2,
                    'id_artikel' => $id,
                    'config_id'  => identitas('id'),
                ];

                $res = $this->first_artikel_m->insert_comment($data);

                if ($res) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'pesan'  => 'Komentar anda telah berhasil dikirim dan perlu dimoderasi untuk ditampilkan.',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => 'Komentar anda gagal dikirim. Silakan ulangi lagi.',
                        'data'   => $post,
                    ];
                }
            }
        } else {
            $respon = [
                'status' => -1, // Notif gagal
                'pesan'  => validation_errors(),
                'data'   => $post,
            ];
        }

        $this->session->set_flashdata('notif', $respon);

        redirect($_SERVER['HTTP_REFERER'] . '#kolom-komentar');
    }

    public function load_apbdes(): void
    {
        $data['transparansi'] = (new Keuangan())->grafik_keuangan_tema();

        view('web.gis.apbdes_web', $data);
    }

    public function load_aparatur_desa(): void
    {
        $data['tampilkanJabatan'] = Widget::getSetting('aparatur_desa', 'overlay');
        view('web.gis.aparatur_desa', $data);
    }

    public function load_aparatur_wilayah($id = '', $kd_jabatan = 0): void
    {
        $data['penduduk'] = PendudukSaja::find($id);
        $kepala_dusun     = ucwords(setting('sebutan_kepala_dusun'));

        switch ($kd_jabatan) {
            case '1':
            default:
                $data['jabatan'] = $kepala_dusun;
                break;

            case '2':
                $data['jabatan'] = 'Ketua RW';
                break;

            case '3':
                $data['jabatan'] = 'Ketua RT';
                break;
        }

        view('web.gis.aparatur_wilayah', $data);
    }

    public function get_form_info(): void
    {
        $redirect_link = $this->input->get('redirectLink', true);

        if ($this->session->inside_retry == false) {
            // Untuk kondisi SEBELUM autentikasi dan SETELAH RETRY hit API
            if ($this->input->get('outsideRetry', true) == 'true') {
                $this->session->inside_retry = true;
            }
            $this->session->google_form_id = $this->input->get('formId', true);
            $result                        = $this->analisis_import_model->import_gform($redirect_link);

            echo json_encode($result, JSON_THROW_ON_ERROR);
        } else {
            // Untuk kondisi SESAAT setelah Autentikasi
            $redirect_link = $this->session->inside_redirect_link;

            $this->session->unset_userdata(['inside_retry', 'inside_redirect_link']);

            header('Location: ' . $redirect_link . '?outsideRetry=true&code=' . $this->input->get('code', true) . '&formId=' . $this->session->google_form_id);
        }
    }

    public function utama(): void
    {
        redirect('/');
    }
}
