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

use App\Models\DokumenHidup;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\SyaratSurat;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class Surat extends Mandiri_Controller
{
    // Kat 1 = Permohonan
    // Kat 2 = Arsip
    public function index($kat = 1): void
    {
        if ($kat == 1) {
            $listSurat = PermohonanSurat::with(['logSurat', 'surat'])->belumDiambil()->whereIdPemohon($this->is_login->id_pend)->get()
                ->map(static function ($item, $key) {
                    $item->nomor       = $item->isian_form['nomor'];
                    $item->status_id   = $item->status;
                    $item->jenis_surat = $item->surat->nama;
                    $item->status      = PermohonanSurat::STATUS_PERMOHONAN[$item->status];
                    $logSurat          = $item->logSurat->where('no_surat', $item->isian_form['nomor'])->first();
                    $item->id_log      = $logSurat ? $logSurat->id : null;
                    $item->tte         = $logSurat ? $logSurat->tte : null;

                    return $item;
                })->toArray();
        } else {
            $listSurat = [];
            $obj       = LogSurat::whereNull('deleted_at')->whereIdPend($this->is_login->id_pend)->get();
            if ($obj) {
                $listSurat = $obj->map(static function ($item) {
                    $item->format      = $item->formatSurat->nama ?? '';
                    $item->pamong_nama = $item->pamong->pamong_nama ?? $item->nama_pamong;

                    return $item;
                })->toArray();
            }
        }

        $data = [
            'kat'     => $kat,
            'judul'   => ($kat == 1) ? 'Permohonan Surat' : 'Arsip Surat',
            'main'    => $listSurat,
            'printer' => $this->print_connector(),
        ];

        $this->render('surat', $data);
    }

    public function buat($id = ''): void
    {
        $id_pend    = $this->is_login->id_pend;
        $permohonan = [];
        // Cek hanya status = 0 (belum lengkap) yg boleh di ubah
        if ($id) {
            $obj = PermohonanSurat::where(['id' => $id, 'id_pemohon' => $id_pend, 'status' => 0])->first();

            if (! $obj) {
                redirect('layanan-mandiri/surat/buat');
            }
            $permohonan  = $obj->toArray();
            $form_action = ci_route("layanan-mandiri/surat/form/{$id}");
        } else {
            $form_action = ci_route('layanan-mandiri/surat/form');
        }

        $data = [
            'menu_surat_mandiri'   => FormatSurat::kunci(0)->mandiri()->get(),
            'menu_dokumen_mandiri' => SyaratSurat::get()->toArray(),
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
        $dokumen           = DokumenHidup::whereIdPend($this->is_login->id_pend)->get()->toArray();

        $data = [];
        $no   = $_POST['start'];

        if ($syaratSurat) {
            $no = 1;

            foreach ($syaratSurat as $baris) {
                $syarat_surat = json_decode($suratMaster->syarat_surat, true);
                if (is_array($syarat_surat) && in_array($baris->ref_syarat_id, $syarat_surat)) {
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
            $permohonan = PermohonanSurat::where(['id' => $id, 'id_pemohon' => $id_pend, 'status' => 0])->first();

            if (! $permohonan || ! $post) {
                redirect('layanan-mandiri/surat/buat');
            }

            $data['permohonan'] = $permohonan->toArray();
            $data['isian_form'] = json_encode($permohonan->isian_form, JSON_THROW_ON_ERROR);
            $data['id_surat']   = $permohonan->id_surat;
        } else {
            if (! $post) {
                redirect('layanan-mandiri/surat/buat');
            }
            $data['permohonan'] = null;
            $data['isian_form'] = null;
            $data['id_surat']   = $post['id_surat'];
        }

        $surat = FormatSurat::find($data['id_surat']);
        $url   = $surat->url_surat;

        $penduduk = Penduduk::find($id_pend) ?? show_404();
        $individu = $penduduk->formIndividu();

        $data['url']      = $url;
        $data['individu'] = $individu;
        $data['anggota']  = $penduduk ? $penduduk->keluarga->anggota->toArray() : null;
        $this->get_data_untuk_form($url, $data);
        $data['surat_url']    = rtrim($_SERVER['REQUEST_URI'], '/clear');
        $data['form_action']  = ci_route("surat/cetak/{$url}");
        $data['cek_anjungan'] = $this->cek_anjungan;
        $data['mandiri']      = 1; // Untuk tombol cetak/kirim surat

        return $this->render('permohonan_surat_tinymce', $data);
    }

    public function kirim($id = ''): void
    {
        $this->load->library('Telegram/telegram');

        $data_permohonan = $this->session->data_permohonan;

        $post = $this->input->post();
        $data = [
            'id_pemohon'  => bilangan($post['nik']),
            'id_surat'    => (int) $post['id_surat'],
            'isian_form'  => json_encode($post, JSON_THROW_ON_ERROR),
            'status'      => 1, // Selalu 1 bagi penggun layanan mandiri
            'keterangan'  => $this->security->xss_clean($data_permohonan['keterangan']),
            'no_hp_aktif' => bilangan($data_permohonan['no_hp_aktif']),
            'syarat'      => json_encode($data_permohonan['syarat'], JSON_THROW_ON_ERROR),
        ];

        if ($id) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            PermohonanSurat::whereId($id)->update($data);
        } else {
            // pakai insert, karena di model ada setter untuk id_surat yang menjadikan malah bermasalah
            $data['config_id'] = identitas('id');
            PermohonanSurat::insert($data);

            if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
                try {
                    // Data pesan telegram yang akan digantikan
                    $pesanTelegram = [
                        '[nama_penduduk]' => $this->is_login->nama,
                        '[judul_surat]'   => FormatSurat::find($post['id_surat'])->nama,
                        '[tanggal]'       => tgl_indo2(date('Y-m-d H:i:s')),
                        '[melalui]'       => 'Layanan Mandiri',
                        '[website]'       => APP_URL,
                    ];

                    $kirimPesan = setting('notifikasi_pengajuan_surat');
                    $kirimPesan = str_replace(array_keys($pesanTelegram), array_values($pesanTelegram), $kirimPesan);
                    $this->telegram->sendMessage([
                        'text'       => $kirimPesan,
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

    private function get_data_untuk_form($url, array &$data): void
    {
        // Panggil 1 penduduk berdasarkan datanya sendiri
        $data['penduduk'] = [$data['periksa']['penduduk']];

        $data['surat_terakhir']     = LogSurat::lastNomerSurat($url);
        $data['surat']              = FormatSurat::where('url_surat', $url)->first()->toArray();
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = FormatSurat::format_penomoran_surat($data);
    }

    public function proses($id = ''): void
    {
        $permohanan         = PermohonanSurat::with(['surat'])->find($id);
        $permohanan->status = PermohonanSurat::DIBATALKAN;
        $permohanan->save();

        $isi = 'Penduduk atas nama : ' . $this->is_login->nama . ' - Telah membatalkan permohonan surat ' . $permohanan->surat->nama;
        $this->kirim_notifikasi_admin('verifikasi_operator', $isi, 'Pembatalan Permohanan Surat - ' . $permohanan->surat->nama);

        redirect('layanan-mandiri/permohonan-surat');
    }

    public function cetak_no_antrian(string $no_antrian): void
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
            $printer->text('NOMOR ANTREAN ANDA');
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

    public function cetak($id)
    {
        $surat = LogSurat::find($id);

        // Cek ada file
        if (file_exists(FCPATH . LOKASI_ARSIP . $surat->nama_surat)) {
            return ambilBerkas($surat->nama_surat, $this->controller, null, LOKASI_ARSIP, true);
        }
        echo 'Berkas tidak ditemukan';
    }
}
