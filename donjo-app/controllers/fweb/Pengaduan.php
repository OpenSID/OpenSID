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

use App\Models\Pengaduan as PengaduanModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengaduan extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pengaduan_model');
        $this->load->library('MY_Upload', null, 'upload');
    }

    public function index($p = 1): void
    {
        if (! $this->web_menu_model->menu_aktif('pengaduan')) {
            show_404();
        }

        $data = $this->includes;
        $this->_get_common_data($data);

        $data['form_action'] = site_url("{$this->controller}/kirim");
        $data['cari']        = $this->input->get('cari', true);
        $data['caristatus']  = $this->input->get('caristatus', true);

        // TODO : Sederhanakan bagian panging dengan suffix
        $data['paging']       = $this->pengaduan_model->paging_pengaduan($p, $data['cari'] ?? '', $data['caristatus'] ?? '');
        $data['paging_page']  = 'pengaduan';
        $data['paging_range'] = 3;
        $data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']   = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']        = range($data['start_paging'], $data['end_paging']);

        $data['pengaduan']       = $this->pengaduan_model->get_pengaduan($data['cari'] ?? '', $data['caristatus'] ?? '');
        $data['pengaduan']       = $data['pengaduan']->limit($data['paging']->per_page, $data['cari'] ? 0 : $data['paging']->offset)->get()->result_array();
        $data['pengaduan_balas'] = $this->pengaduan_model->get_pengaduan_balas($data['cari'] ?? '', $data['caristatus'] ?? '')->get()->result_array();
        $data['halaman_statis']  = 'pengaduan/index';

        $this->set_template('layouts/halaman_statis_lebar.tpl.php');
        theme_view($this->template, $data);
    }

    public function kirim(): void
    {
        $this->load->library('Telegram/telegram');
        $post = $this->input->post();
        // Periksa isian captcha
        $captcha = new App\Libraries\Captcha();
        if (! $captcha->check($this->input->post('captcha_code'))) {
            $notif = [
                'status' => 'error',
                'pesan'  => 'Kode captcha anda salah. Silakan ulangi lagi.',
                'data'   => $post,
            ];
        } elseif (empty($this->input->ip_address())) {
            $notif = [
                'status' => 'error',
                'pesan'  => 'Pengaduan gagal dikirim. IP Address anda tidak dikenali.',
            ];
        } else {
            // Cek pengaduan dengan ip_address yang pada hari yang sama
            $cek = PengaduanModel::where('ip_address', '=', $this->input->ip_address())
                ->whereNull('id_pengaduan')
                ->whereDate('created_at', date('Y-m-d'))
                ->exists();

            if ($cek) {
                $notif = [
                    'status' => 'error',
                    'pesan'  => 'Pengaduan gagal dikirim. Anda hanya dapat mengirimkan satu pengaduan dalam satu hari.',
                ];
            } elseif ($this->pengaduan_model->insert()) {
                $id_pengaduan = $this->db->insert_id();
                if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
                    try {
                        $this->telegram->sendMessage([
                            'text'       => 'Halo! Ada pengaduan baru dari warga, mohon untuk segera ditindak lanjuti. Terima kasih.',
                            'parse_mode' => 'Markdown',
                            'chat_id'    => $this->setting->telegram_user_id,
                        ]);
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }
                $notif = [
                    'status' => 'success',
                    'pesan'  => 'Pengaduan berhasil dikirim.',
                ];

                // notifikasi penduduk
                $payload = '/pengaduan/detail/' . $id_pengaduan;
                $isi     = 'Halo! Ada pengaduan baru dari warga, mohon untuk segera ditindak lanjuti. Terima kasih.';
                $this->kirim_notifikasi_admin('all', $isi, $this->input->post('judul'), $payload);
            } else {
                $notif = [
                    'status' => 'error',
                    'pesan'  => 'Pengaduan gagal dikirim.',
                    'data'   => $post,
                ];
            }
        }

        redirect_with('notif', $notif);
    }
}
