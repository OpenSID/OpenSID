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

use App\Models\PesanMandiri;

defined('BASEPATH') || exit('No direct script access allowed');

class Pesan extends Mandiri_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['permohonan_surat_model']);
    }

    public function index($kat = 1): void
    {
        $data = [
            'kat'   => $kat,
            'judul' => ($kat == 1) ? 'Keluar' : 'Masuk',
            'pesan' => PesanMandiri::whereTipe($kat)->wherePendudukId($this->is_login->id_pend)->get()->toArray(),
        ];

        $this->render('pesan', $data);
    }

    // TODO: Pisahkan mailbox dari komentar
    // TODO: Ganti nik jadi id_pend
    public function kirim($kat = 2): void
    {
        $data = $this->input->post();

        if (PesanMandiri::hasDelay($this->is_login->id_pend)) {
            $respon = [
                'status' => 'error',
                'pesan'  => 'Anda mencapai batasan pengiriman pesan. Silahkan kirim kembali pesan anda setelah 60 detik.',
                'data'   => $data,
            ];
            redirect_with('notif', $respon, 'layanan-mandiri/pesan/tulis');
        }

        $this->load->library('Telegram/telegram');

        $post['penduduk_id'] = $this->is_login->id_pend; // kolom email diisi nik untuk pesan
        $post['owner']       = $this->is_login->nama;
        $post['subjek']      = $data['subjek'];
        $post['komentar']    = $data['pesan'];
        $post['tipe']        = PesanMandiri::MASUK;
        $post['status']      = PesanMandiri::UNREAD;
        PesanMandiri::create($post);

        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            try {
                $this->telegram->sendMessage([
                    'text'       => sprintf('Warga RT. %s atas nama %s telah mengirim pesan melalui Layanan Mandiri pada tanggal %s. Link : %s', $this->is_login->rt, $this->is_login->nama, tgl_indo2(date('Y-m-d H:i:s')), APP_URL),
                    'parse_mode' => 'Markdown',
                    'chat_id'    => $this->setting->telegram_user_id,
                ]);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }

        if ($kat == 1) {
            redirect('layanan-mandiri/pesan-keluar');
        }

        redirect('layanan-mandiri/pesan-masuk');
    }

    public function baca($kat = 2, $id = ''): void
    {
        $pesan = PesanMandiri::findOrFail($id);
        if ($kat == 2) {
            $pesan->status = PesanMandiri::READ;
            $pesan->save();
        }

        $data = [
            'kat'        => $kat,
            'owner'      => ($kat == 2) ? 'Penerima' : 'Pengirim',
            'tujuan'     => ($kat == 2) ? 'pesan-masuk' : 'pesan-keluar',
            'pesan'      => $pesan->toArray(),
            'permohonan' => $this->permohonan_surat_model->get_permohonan(['id' => $pesan['permohonan']]),
        ];

        $this->render('baca_pesan', $data);
    }

    public function tulis($kat = 2): void
    {
        $data = [
            'tujuan' => ($kat == 2) ? 'pesan-masuk' : 'pesan-keluar',
            'kat'    => $kat,
            'subjek' => $this->input->post('subjek'),
        ];

        $this->render('tulis_pesan', $data);
    }
}
