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

use App\Models\PermohonanSurat;
use App\Models\PesanMandiri;
use NotificationChannels\Telegram\Telegram;

defined('BASEPATH') || exit('No direct script access allowed');

class Pesan extends Mandiri_Controller
{
    public function index($kat = 1)
    {
        $judul = ($kat == 1) ? 'Keluar' : 'Masuk';

        return view('layanan_mandiri.pesan.index', ['kat' => $kat, 'judul' => $judul]);
    }

    public function datatables($kat = 1)
    {
        if ($this->input->is_ajax_request()) {
            $query = PesanMandiri::where('tipe', $kat)->where('penduduk_id', $this->is_login->id_pend);

            // Handle ordering
            if ($this->input->get('order')) {
                $orderColumnIndex = $this->input->get('order')[0]['column'];
                $orderDirection   = $this->input->get('order')[0]['dir'];

                $columns = [
                    0 => 'DT_RowIndex',
                    1 => 'aksi',
                    2 => 'subjek',
                    3 => 'status',
                    4 => 'tgl_upload',
                ];

                $orderColumnName = $columns[$orderColumnIndex];
                $query           = $query->orderBy($orderColumnName, $orderDirection);
            }

            return datatables($query)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($item) use ($kat): string {
                    $url  = ci_route('layanan-mandiri.pesan.baca', ['kat' => $kat, 'uuid' => $item->uuid]);
                    $icon = $item->status == 2 ? 'fa-eye-slash' : 'fa-eye';

                    return '<a href="' . $url . '" class="btn bg-green btn-sm" title="Baca pesan"><i class="fa ' . $icon . '">&nbsp;</i></a>';
                })
                ->addColumn('status_baca', static fn ($item): string => $item->status == 1 ? 'Sudah Dibaca' : 'Belum Dibaca')
                ->addColumn('tgl_upload', static fn ($item) => tgl_indo2($item->tgl_upload))
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return show_404();
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

        $post['penduduk_id'] = $this->is_login->id_pend; // kolom email diisi nik untuk pesan
        $post['owner']       = $this->is_login->nama;
        $post['subjek']      = $data['subjek'];
        $post['komentar']    = $data['pesan'];
        $post['tipe']        = PesanMandiri::MASUK;
        $post['status']      = PesanMandiri::UNREAD;
        PesanMandiri::create($post);

        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            try {
                $telegram = new Telegram(setting('telegram_token'));
                $telegram->sendMessage([
                    'text'       => sprintf('Warga RT. %s atas nama %s telah mengirim pesan melalui Layanan Mandiri pada tanggal %s. Link : %s', $this->is_login->rt, $this->is_login->nama, tgl_indo2(date('Y-m-d H:i:s')), APP_URL),
                    'parse_mode' => 'Markdown',
                    'chat_id'    => setting('telegram_user_id'),
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

    public function baca($kat = 2, $id = '')
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
            'permohonan' => PermohonanSurat::where(['id' => $pesan['id']])->first(),
        ];

        return view('layanan_mandiri.pesan.baca', $data);
    }

    public function tulis($kat = 2)
    {
        $data = [
            'tujuan' => ($kat == 2) ? 'pesan-masuk' : 'pesan-keluar',
            'kat'    => $kat,
            'subjek' => $this->input->post('subjek'),
        ];

        return view('layanan_mandiri.pesan.tulis', $data);
    }
}
