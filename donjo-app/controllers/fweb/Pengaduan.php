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

class Pengaduan extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pengaduan_model');
        $this->load->library('upload');
    }

    public function index($p = 1)
    {
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
        $this->load->view($this->template, $data);
    }

    public function kirim()
    {
        $this->load->library('Telegram/telegram');

        // Periksa isian captcha
        include FCPATH . 'securimage/securimage.php';
        $securimage = new Securimage();

        $post = $this->input->post();
        if ($securimage->check($post['captcha_code']) == false) {
            $notif = [
                'status' => 'danger',
                'pesan'  => 'Kode captcha anda salah. Silakan ulangi lagi.',
                'data'   => $post,
            ];
        } else {
            if ($this->pengaduan_model->insert()) {
                if (! empty($this->setting->telegram_token) && cek_koneksi_internet()) {
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
            } else {
                $notif = [
                    'status' => 'danger',
                    'pesan'  => 'Pengaduan gagal dikirim.',
                    'data'   => $post,
                ];
            }
        }

        redirect_with('notif', $notif);
    }
}
