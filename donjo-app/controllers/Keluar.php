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

use App\Models\LogSurat;
use App\Models\User;

defined('BASEPATH') || exit('No direct script access allowed');

class Keluar extends Admin_Controller
{
    private $list_session = ['cari', 'tahun', 'bulan', 'jenis', 'nik', 'masuk', 'ditolak'];
    public $isAdmin;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('keluar_model');
        $this->load->model('surat_model');

        $this->load->helper('download');
        $this->load->model('pamong_model');
        $this->modul_ini     = 4;
        $this->sub_modul_ini = 32;
        $this->isAdmin       = $this->session->isAdmin->pamong;
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
    }

    public function clear($redirect = null)
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->set_userdata('per_page', 20);
        if ($redirect != null) {
            redirect($this->controller . '/' . $redirect);
        }
        redirect('keluar');
    }

    public function index($p = 1, $o = 0)
    {
        $this->tab_ini = 10;
        $data['p']     = $p;
        $data['o']     = $o;

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        if ($this->input->post('per_page') !== null) {
            $this->session->per_page = $this->input->post('per_page');
        }

        if (! isset($this->session->tahun)) {
            $this->session->unset_userdata('bulan');
        }

        $data['per_page']    = $this->session->per_pages;
        $data['title']       = 'Arsip Layanan Surat';
        $data['operator']    = ((int) $this->isAdmin->pamong_ub == 0 && (int) $this->isAdmin->pamong_ttd == 0) ? true : false;
        $data['paging']      = $this->keluar_model->paging($p, $o);
        $data['main']        = $this->keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['tahun_surat'] = $this->keluar_model->list_tahun_surat();
        $data['bulan_surat'] = ($this->session->tahun == null) ? [] : $this->keluar_model->list_bulan_surat(); //ambil list bulan dari log
        $data['jenis_surat'] = $this->keluar_model->list_jenis_surat();
        $data['keyword']     = $this->keluar_model->autocomplete();
        $data['widgets']     = $this->widget();
        $this->render('surat/surat_keluar', $data);
    }

    public function masuk($p = 1, $o = 0)
    {
        $this->tab_ini        = 11;
        $this->session->masuk = true;
        $data['p']            = $p;
        $data['o']            = $o;

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        if ($this->input->post('per_page') !== null) {
            $this->session->per_page = $this->input->post('per_page');
        }

        if (! isset($this->session->tahun)) {
            $this->session->unset_userdata('bulan');
        }

        $data['per_page'] = $this->session->per_pages;
        $data['title']    = 'Layanan Surat';
        $data['operator'] = ((int) $this->isAdmin->pamong_ub == 0 && (int) $this->isAdmin->pamong_ttd == 0) ? true : false;

        if ($this->isAdmin->pamong_ttd == 1) {
            $data['next'] = null;
        } elseif ($this->isAdmin->pamong_ub == 1) {
            $data['next'] = setting('verifikasi_kades') ? setting('sebutan_kepala_desa') : null;
        } else {
            if (setting('verifikasi_sekdes')) {
                $data['next'] = 'sekretaris desa';
            } elseif (setting('verifikasi_kades')) {
                $data['next'] = setting('sebutan_kepala_desa');
            } else {
                $data['next'] = null;
            }
        }

        $data['paging']      = $this->keluar_model->paging($p, $o);
        $data['main']        = $this->keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['tahun_surat'] = $this->keluar_model->list_tahun_surat();
        $data['bulan_surat'] = ($this->session->tahun == null) ? [] : $this->keluar_model->list_bulan_surat(); //ambil list bulan dari log
        $data['jenis_surat'] = $this->keluar_model->list_jenis_surat();
        $data['keyword']     = $this->keluar_model->autocomplete();
        $data['widgets']     = $this->widget();
        $this->render('surat/surat_keluar', $data);
    }

    public function ditolak()
    {
        $this->tab_ini = 12;

        $this->session->ditolak = true;
        $data['p']              = $p;
        $data['o']              = $o;

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        if ($this->input->post('per_page') !== null) {
            $this->session->per_page = $this->input->post('per_page');
        }

        if (! isset($this->session->tahun)) {
            $this->session->unset_userdata('bulan');
        }

        $data['per_page'] = $this->session->per_pages;
        $data['title']    = 'Surat Ditolak';
        $data['operator'] = ((int) $this->isAdmin->pamong_ub == 0 && (int) $this->isAdmin->pamong_ttd == 0) ? true : false;

        if (setting('verifikasi_sekdes')) {
            $data['next'] = 'sekretaris desa';
        } elseif (setting('verifikasi_kades')) {
            $data['next'] = setting('sebutan_kepala_desa');
        } else {
            $data['next'] = null;
        }

        $data['paging']      = $this->keluar_model->paging($p, $o);
        $data['main']        = $this->keluar_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['tahun_surat'] = $this->keluar_model->list_tahun_surat();
        $data['bulan_surat'] = ($this->session->tahun == null) ? [] : $this->keluar_model->list_bulan_surat(); //ambil list bulan dari log
        $data['jenis_surat'] = $this->keluar_model->list_jenis_surat();
        $data['keyword']     = $this->keluar_model->autocomplete();
        $data['widgets']     = $this->widget();
        $this->render('surat/surat_keluar', $data);
    }

    public function verifikasi()
    {
        $id = $this->input->post('id');
        //update verifikasi
        //cek setting
        $array_verifikasi = ['foot', 'bike', 'car', 'plane'];

        if ($this->isAdmin->pamong_ttd == 1) {
            $current = 'verifikasi_kades';
            $next    = (setting('tte')) ? 'tte' : null;
            $log     = null;
        } elseif ($this->isAdmin->pamong_ub == 1) {
            $current = 'verifikasi_sekdes';
            $next    = setting('verifikasi_kades') ? 'verifikasi_kades' : null;
            $log     = 'Verifikasi ' . setting('sebutan_kepala_desa');
        } else {
            $current = 'verifikasi_operator';
            if (setting('verifikasi_sekdes')) {
                $next = 'verifikasi_sekdes';
                $log  = 'Verifikasi Sekretaris Desa';
            } elseif (setting('verifikasi_kades')) {
                $next = 'verifikasi_kades';
                $log  = 'Verifikasi ' . setting('sebutan_kepala_desa');
            } else {
                $next = null;
                $log  = null;
            }
        }

        if ($next == null) {
            LogSurat::where('id', '=', $id)->update([$current => 1, 'log_verifikasi' => $log]);
        } else {
            $log_surat = LogSurat::where('id', '=', $id)->first();
            $log_surat->update([$current => 1,  $next => 0, 'log_verifikasi' => $log]);
            $kirim_telegram = User::whereHas('pamong', static function ($query) use ($next) {
                if ($next == 'verifikasi_sekdes') {
                    return $query->where('pamong_ub', '=', '1');
                }
                if ($next == 'verifikasi_kades') {
                    return $query->where('pamong_ttd', '=', '1');
                }
            })
                ->where('notif_telegram', '=', '1')
                ->first();

            if ($kirim_telegram != null) {
                $jenis_surat = $log_surat->formatSurat->nama;
                $telegram    = new Telegram();
                $telegram->sendMessage([
                    'chat_id' => $kirim_telegram->id_telegram,
                    'text'    => <<<EOD
                        Permohonan Surat Baru,

                        Jenis Surat : {$jenis_surat}

                        TERIMA KASIH.
                        EOD,
                    'parse_mode'   => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [[
                            ['text' => 'Lihat detail', 'url' => site_url('keluar/clear/masuk')],
                        ]],
                    ]),
                ]);
            }
        }
    }

    public function tolak()
    {
        $id        = $this->input->post('id');
        $log_surat = LogSurat::where('id', '=', $id)->first();
        $log_surat->update([
            'verifikasi_kades'    => null,
            'verifikasi_sekdes'   => null,
            'verifikasi_operator' => -1,
        ]);
        $jenis_surat = $log_surat->formatSurat->nama;

        $kirim_telegram = User::whereHas('pamong', static function ($query) {
            return $query->where('pamong_ub', '=', '0')->where('pamong_ttd', '=', '0');
        })
            ->where('notif_telegram', '=', '1')
            ->get();

        $telegram = new Telegram();

        foreach ($kirim_telegram as $value) {
            $telegram->sendMessage([
                'chat_id' => $value->id_telegram,
                'text'    => <<<EOD
                    Permohonan Surat telah ditolak,

                    Jenis Surat : {$jenis_surat}

                    TERIMA KASIH.
                    EOD,
                'parse_mode'   => 'Markdown',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [[
                        ['text' => 'Lihat detail', 'url' => site_url('keluar/clear/ditolak')],
                    ]],
                ]),
            ]);
        }
    }

    public function edit_keterangan($id = 0)
    {
        $this->redirect_hak_akses('u');
        $data['main']        = $this->keluar_model->get_surat($id);
        $data['form_action'] = site_url("keluar/update_keterangan/{$id}");
        $this->load->view('surat/ajax_edit_keterangan', $data);
    }

    public function update_keterangan($id = '')
    {
        $this->redirect_hak_akses('u');
        $data = ['keterangan' => $this->input->post('keterangan')];
        $data = $this->security->xss_clean($data);
        $data = html_escape($data);
        $this->keluar_model->update_keterangan($id, $data);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h');
        session_error_clear();
        $this->keluar_model->delete($id);
        redirect("keluar/masuk/{$p}/{$o}");
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }
    }

    public function perorangan_clear()
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = 20;
        redirect('keluar/perorangan');
    }

    public function perorangan($nik = '', $p = 1, $o = 0)
    {
        if ($this->input->post('nik')) {
            $id = $this->input->post('nik');
        } elseif ($nik) {
            $id = $this->db->select('id')->get_where('penduduk_hidup', ['nik' => $nik])->row()->id;
        }

        if ($id) {
            $data['individu'] = $this->surat_model->get_penduduk($id);
        } else {
            $data['individu'] = null;
        }

        $data['p'] = $p;
        $data['o'] = $o;

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $this->input->post('per_page');
        }
        $data['per_page']    = $this->session->per_page;
        $data['paging']      = $this->keluar_model->paging_perorangan($id, $p, $o);
        $data['main']        = $this->keluar_model->list_data_perorangan($id, $o, $data['paging']->offset, $data['paging']->per_page);
        $data['form_action'] = site_url("sid_surat_keluar/perorangan/{$data['individu']['nik']}");

        $this->render('surat/surat_keluar_perorangan', $data);
    }

    public function graph()
    {
        $data['stat'] = $this->keluar_model->grafik();

        $this->render('surat/surat_keluar_graph', $data);
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($filter == 'tahun') {
            $this->session->unset_userdata('bulan');
        } //hapus filter bulan
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('keluar');
    }

    public function unduh($tipe, $id)
    {
        if ($tipe == 'tinymce') {
            redirect("surat/cetak/{$id}");
        } else {
            $berkas = $this->keluar_model->get_surat($id);
            if ($tipe == 'pdf') {
                $berkas->nama_surat = basename($berkas->nama_surat, 'rtf') . 'pdf';
            }
            ambilBerkas($tipe == 'lampiran' ? $berkas->lampiran : $berkas->nama_surat, $this->controller);
        }
    }

    public function dialog_cetak($aksi = '')
    {
        $data['aksi']           = $aksi;
        $data['pamong']         = $this->pamong_model->list_data();
        $data['pamong_ttd']     = $this->pamong_model->get_ub();
        $data['pamong_ketahui'] = $this->pamong_model->get_ttd();
        $data['form_action']    = site_url("keluar/cetak/{$aksi}");
        $this->load->view('global/ttd_pamong', $data);
    }

    public function cetak($aksi = '')
    {
        $data['aksi']           = $aksi;
        $data['input']          = $this->input->post();
        $data['config']         = $this->header['desa'];
        $data['pamong_ttd']     = $this->pamong_model->get_data($_POST['pamong_ttd']);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($_POST['pamong_ketahui']);
        $data['desa']           = $this->header['desa'];
        $data['main']           = $this->keluar_model->list_data();

        //pengaturan data untuk format cetak/ unduh
        $data['file']      = 'Data Arsip Layanan Desa ';
        $data['isi']       = 'surat/cetak';
        $data['letak_ttd'] = ['2', '2', '3'];

        $this->load->view('global/format_cetak', $data);
    }

    public function qrcode($id = null)
    {
        if ($id) {
            $data = $this->surat_model->getQrCode($id);

            $this->load->view('surat/qrcode', $data);
        }
    }

    public function widget()
    {
        return [
            'suratMasuk' => LogSurat::when($this->isAdmin->pamong_ttd == '1', static function ($q) {
                return $q->where('verifikasi_kades', '=', '0');
            })
                ->when($this->isAdmin->pamong_ub == '1', static function ($q) {
                    return $q->where('verifikasi_sekdes', '=', '0');
                })
                ->when((int) $this->isAdmin->pamong_ttd == 0 && (int) $this->isAdmin->pamong_ub == 0, static function ($q) {
                    return $q->where('verifikasi_operator', '=', '0');
                })->count(),
            'arsip' => LogSurat::when($this->isAdmin->pamong_ttd == '1', static function ($q) {
                return $q->where('verifikasi_kades', '=', '1');
            })
                ->when($this->isAdmin->pamong_ub == '1', static function ($q) {
                    return $q->where('verifikasi_sekdes', '=', '1');
                })
                ->when((int) $this->isAdmin->pamong_ttd == 0 && (int) $this->isAdmin->pamong_ub == 0, static function ($q) {
                    return $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator');
                })->count(),
            'tolak' => LogSurat::where('verifikasi_operator', '=', '-1')->count(),
        ];
    }
}
