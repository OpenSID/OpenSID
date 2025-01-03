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

use App\Libraries\Captcha;
use App\Models\Pengaduan as PengaduanModel;
use App\Traits\Upload;
use NotificationChannels\Telegram\Telegram;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengaduan extends Web_Controller
{
    use Upload;

    public function __construct()
    {
        parent::__construct();
        $this->hak_akses_menu('pengaduan');
    }

    public function index()
    {
        $data['form_action'] = ci_route('pengaduan.kirim');
        $data['cari']        = $this->input->get('cari', true);
        $data['caristatus']  = $this->input->get('caristatus', true);

        return view('theme::partials.pengaduan.index', $data);
    }

    public function kirim(): void
    {
        $post = $this->input->post();
        // Periksa isian captcha
        $captcha = new Captcha();
        if (! $captcha->check($this->request['captcha_code'])) {
            set_session('data', $post);
            redirect_with('error', 'Kode captcha anda salah. Silakan ulangi lagi.');
        }
        if (empty($this->input->ip_address())) {
            redirect_with('error', 'Pengaduan gagal dikirim. IP Address anda tidak dikenali.');
        }

        // Cek pengaduan dengan ip_address yang pada hari yang sama
            $cek = PengaduanModel::where('ip_address', '=', $this->input->ip_address())
                ->whereNull('id_pengaduan')
                ->whereDate('created_at', date('Y-m-d'))
                ->exists();
            if ($cek) {
                redirect_with('error', 'Pengaduan gagal dikirim. Anda hanya dapat mengirimkan satu pengaduan dalam satu hari.');
            }

                $dataInsert   = $this->validasi($post);
                $pengaduan    = PengaduanModel::create($dataInsert);
                $id_pengaduan = $pengaduan->id;
                if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
                    $telegram = new Telegram(setting('telegram_token'));

                    try {
                        $telegram->sendMessage([
                            'text'       => 'Halo! Ada pengaduan baru dari warga, mohon untuk segera ditindak lanjuti. Terima kasih.',
                            'parse_mode' => 'Markdown',
                            'chat_id'    => setting('telegram_user_id'),
                        ]);
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }
                // notifikasi penduduk
                $payload = '/pengaduan/detail/' . $id_pengaduan;
                $isi     = 'Halo! Ada pengaduan baru dari warga, mohon untuk segera ditindak lanjuti. Terima kasih.';
                $this->kirim_notifikasi_admin('all', $isi, $post['judul'], $payload);

        redirect_with('success', 'Pengaduan berhasil dikirim.');
    }

    private function validasi($post)
    {
        $data = [
            'nik'        => bilangan($post['nik']),
            'nama'       => nama($post['nama']),
            'email'      => email($post['email']),
            'telepon'    => bilangan($post['telepon']),
            'judul'      => bersihkan_xss($post['judul']),
            'isi'        => bersihkan_xss($post['isi']),
            'ip_address' => $this->input->ip_address(),
        ];
        if ($this->request['foto']) {
            $config['upload_path']   = LOKASI_PENGADUAN;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = max_upload() * 1024;
            $config['file_name']     = namafile($post['judul']);

            $data['foto'] = $this->upload('foto', $config);
        }

        return $data;
    }
}
