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

use App\Models\FormatSurat;
use App\Models\PermohonanSurat;
use App\Models\SyaratSurat;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class Surat extends Mandiri_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['keluar_model', 'permohonan_surat_model', 'surat_model', 'surat_master_model', 'lapor_model', 'penduduk_model']);
    }

    // Kat 1 = Permohonan
    // Kat 2 = Arsip
    public function index($kat = 1)
    {
        $arsip      = $this->keluar_model->list_data_perorangan($this->is_login->id_pend);
        $permohonan = $this->permohonan_surat_model->list_permohonan_perorangan($this->is_login->id_pend, 1);

        $data = [
            'kat'     => $kat,
            'judul'   => ($kat == 1) ? 'Permohonan Surat' : 'Arsip Surat',
            'main'    => ($kat == 1) ? $permohonan : $arsip,
            'printer' => $this->print_connector(),
        ];

        $this->render('surat', $data);
    }

    public function buat($id = '')
    {
        $id_pend = $this->is_login->id_pend;

        // Cek hanya status = 0 (belum lengkap) yg boleh di ubah
        if ($id) {
            $permohonan = $this->permohonan_surat_model->get_permohonan(['id' => $id, 'id_pemohon' => $id_pend, 'status' => 0]);

            if (! $permohonan) {
                redirect('layanan-mandiri/surat/buat');
            }

            $form_action = site_url("layanan-mandiri/surat/form/{$permohonan['id']}");
        } else {
            $form_action = site_url('layanan-mandiri/surat/form');
        }

        $data = [
            'menu_surat_mandiri'   => $this->surat_model->list_surat_mandiri(),
            'menu_dokumen_mandiri' => $this->lapor_model->get_surat_ref_all(),
            'list_dokumen'         => $this->penduduk_model->list_dokumen($id_pend),
            'kk'                   => ($this->is_login->kk_level === '1') ? $this->keluarga_model->list_anggota($this->is_login->id_kk) : '', // Ambil data anggota KK, jika Kepala Keluarga
            'permohonan'           => $permohonan,
            'form_action'          => $form_action,
        ];

        $this->render('buat_surat', $data);
    }

    public function cek_syarat()
    {
        $id_permohonan = $this->input->post('id_permohonan');
        $id_surat      = $this->input->post('id_surat');

        $syarat_permohonan = PermohonanSurat::select(['syarat'])->find($id_permohonan);
        $suratMaster       = FormatSurat::select(['syarat_surat'])->find($id_surat);
        $syaratSurat       = SyaratSurat::get();
        $dokumen           = $this->penduduk_model->list_dokumen($this->is_login->id_pend);

        $data = [];
        $no   = $_POST['start'];

        if ($syaratSurat) {
            $no = 1;

            foreach ($syaratSurat as $baris) {
                if (in_array($baris->ref_syarat_id, json_decode($suratMaster->syarat_surat))) {
                    $row   = [];
                    $row[] = $no++;
                    $row[] = $baris->ref_syarat_nama;
                    // Gunakan view sebagai string untuk mempermudah pembuatan pilihan
                    $pilihan_dokumen = $this->load->view(MANDIRI . '/pilihan_syarat', ['dokumen' => $dokumen, 'syarat_permohonan' => json_decode($syarat_permohonan, true), 'syarat_id' => $baris->ref_syarat_id, 'cek_anjungan' => $this->cek_anjungan], true);
                    $row[]           = $pilihan_dokumen;
                    $data[]          = $row;
                }
            }
        }

        return json([
            'recordsTotal'    => 10,
            'recordsFiltered' => 10,
            'data'            => $data,
        ]);
    }

    // Proses permohonan surat
    public function form($id = '')
    {
        $id_pend = $this->is_login->id_pend;

        // Simpan data dari buat surat
        $post                           = $this->input->post();
        $post                           = ($post) ?: $this->session->data_permohonan;
        $this->session->data_permohonan = $post;

        // Cek hanya status = 0 (belum lengkap) yg boleh di ubah
        if ($id) {
            $permohonan = $this->permohonan_surat_model->get_permohonan(['id' => $id, 'id_pemohon' => $id_pend, 'status' => 0]);

            if (! $permohonan || ! $post) {
                redirect('layanan-mandiri/surat/buat');
            }

            $data['permohonan'] = $permohonan;
            $data['isian_form'] = json_encode($this->permohonan_surat_model->ambil_isi_form($permohonan['isian_form']));
            $data['id_surat']   = $permohonan['id_surat'];
        } else {
            if (! $post) {
                redirect('layanan-mandiri/surat/buat');
            }
            $data['permohonan'] = null;
            $data['isian_form'] = null;
            $data['id_surat']   = $post['id_surat'];
        }

        $surat = $this->surat_model->cek_surat_mandiri($data['id_surat']);
        $url   = $surat['url_surat'];

        $data['url']          = $url;
        $data['list_dokumen'] = $this->penduduk_model->list_dokumen($id_pend);
        $data['individu']     = $this->surat_model->get_penduduk($id_pend);
        $data['anggota']      = $this->keluarga_model->list_anggota($data['individu']['id_kk']);
        $data['penduduk']     = $this->penduduk_model->get_penduduk($id_pend);
        $this->get_data_untuk_form($url, $data);
        $data['desa']         = $this->header;
        $data['surat_url']    = rtrim($_SERVER['REQUEST_URI'], '/clear');
        $data['form_action']  = site_url("surat/cetak/{$url}");
        $data['cek_anjungan'] = $this->cek_anjungan;
        $data['mandiri']      = 1; // Untuk tombol cetak/kirim surat

        $this->render('permohonan_surat', $data);
    }

    public function kirim($id = '')
    {
        $this->load->library('Telegram/telegram');

        $data_permohonan = $this->session->data_permohonan;

        $post = $this->input->post();
        $data = [
            'id_pemohon'  => $post['nik'],
            'id_surat'    => $post['id_surat'],
            'isian_form'  => json_encode($post),
            'status'      => 1, // Selalu 1 bagi penggun layanan mandiri
            'keterangan'  => $data_permohonan['keterangan'],
            'no_hp_aktif' => $data_permohonan['no_hp_aktif'],
            'syarat'      => json_encode($data_permohonan['syarat']),
        ];

        if ($id) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->permohonan_surat_model->update($id, $data);
        } else {
            $this->permohonan_surat_model->insert($data);

            if (! empty($this->setting->telegram_token) && cek_koneksi_internet()) {
                try {
                    $this->telegram->sendMessage([
                        'text'       => sprintf('Segera cek Halaman Admin, penduduk atas nama %s telah mengajukan %s melalui Layanan Mandiri pada tanggal %s', $this->is_login->nama, str_replace('_', ' ', mb_convert_case($post['url_surat'], MB_CASE_TITLE)), tgl_indo2(date('Y-m-d H:i:s'))),
                        'parse_mode' => 'Markdown',
                        'chat_id'    => $this->setting->telegram_user_id,
                    ]);
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                }
            }
        }

        $this->session->unset_userdata('data_permohonan');

        redirect('layanan-mandiri/permohonan-surat');
    }

    private function get_data_untuk_form($url, &$data)
    {
        $this->load->model('pamong_model');
        $this->load->model('surat_model');
        $data['surat_terakhir']     = $this->surat_model->get_last_nosurat_log($url);
        $data['surat']              = $this->surat_model->get_surat($url);
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
        $data['lokasi']             = $this->header['desa'];
        $data['pamong']             = $this->surat_model->list_pamong();
        $pamong_ttd                 = $this->pamong_model->get_ttd();
        $pamong_ub                  = $this->pamong_model->get_ub();
        $data_form                  = $this->surat_model->get_data_form($url);
        if (is_file($data_form)) {
            include $data_form;
        }
    }

    public function proses($id = '')
    {
        $this->permohonan_surat_model->proses($id, 5, $this->is_login->id_pend);

        redirect('layanan-mandiri/permohonan-surat');
    }

    public function cetak_no_antrian(string $no_antrian)
    {
        try {
            $connector = new NetworkPrintConnector($this->cek_anjungan['printer_ip'], $this->cek_anjungan['printer_port'], 5);
            $printer   = new Printer($connector);

            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->setEmphasis(true);
            $printer->text('ANJUNGAN MANDIRI');
            $printer->setEmphasis(false);
            $printer->feed(1);

            $printer->setTextSize(1, 1);
            $printer->text("SELAMAT DATANG \n");
            $printer->text('NOMOR ANTRIAN ANDA');
            $printer->feed();

            $printer->setTextSize(4, 4);
            $printer->text(get_antrian($no_antrian));
            $printer->feed();

            $printer->setTextSize(1, 1);
            $printer->text("TERIMA KASIH \n");
            $printer->text('ANDA TELAH MENUNGGU');
            $printer->feed();

            $printer->cut();
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            redirect($_SERVER['HTTP_REFERER']);
        } finally {
            $printer->close();
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    protected function print_connector()
    {
        if (null === ($anjungan = $this->cek_anjungan)) {
            return;
        }

        try {
            $connector = new NetworkPrintConnector($anjungan['printer_ip'], $anjungan['printer_port'], 5);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return false;
        }

        return $connector;
    }
}
