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

use App\Libraries\OTP\OtpManager;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Verifikasi extends Mandiri_Controller
{
    private OtpManager $otp;

    public function __construct()
    {
        parent::__construct();
        $this->otp = new OtpManager();
    }

    public function index()
    {
        $data = [
            'tgl_verifikasi_telegram' => $this->otp->driver('telegram')->cekVerifikasiOtp($this->is_login->id_pend),
            'tgl_verifikasi_email'    => $this->otp->driver('email')->cekVerifikasiOtp($this->is_login->id_pend),
            'form_kirim_userid'       => site_url('layanan-mandiri/verifikasi/telegram/kirim-userid'),
            'form_kirim_email'        => site_url('layanan-mandiri/verifikasi/email/kirim-email'),
        ];

        if ($data['tgl_verifikasi_telegram']) {
            $this->session->set_flashdata('sudah-diverifikasi', '#langkah-4');
        }

        if ($data['tgl_verifikasi_email']) {
            $this->session->set_flashdata('sudah-diverifikasi-email', '#langkah-4');
        }

        if ($data['tgl_verifikasi_telegram'] && $data['tgl_verifikasi_email']) {
            $this->session->set_flashdata('sudah-verifikasi-semua', 1);
        }

        $this->session->set_flashdata('tab-aktif', [
            'status' => 0,
        ]);

        return view('layanan_mandiri.verifikasi.index', $data);
    }

    /**
     * Verifikasi Telegram
     */
    public function telegram()
    {
        $data = [
            'tgl_verifikasi_telegram' => $this->otp->driver('telegram')->cekVerifikasiOtp($this->is_login->id_pend),
            'tgl_verifikasi_email'    => $this->otp->driver('email')->cekVerifikasiOtp($this->is_login->id_pend),
            'form_kirim_userid'       => site_url('layanan-mandiri/verifikasi/telegram/kirim-userid'),
            'form_kirim_otp'          => site_url('layanan-mandiri/verifikasi/telegram/kirim-otp'),
        ];

        if ($data['tgl_verifikasi_telegram']) {
            $this->session->set_flashdata('sudah-diverifikasi', '#langkah4');
        }

        if ($data['tgl_verifikasi_email']) {
            $this->session->set_flashdata('sudah-diverifikasi-email', '#langkah4');
        }

        $this->session->set_flashdata('tab-aktif', [
            'status' => 0,
        ]);

        return view('layanan_mandiri.verifikasi.index', $data);
    }

    /**
     * Langkah 2 Verifikasi Telegram
     */
    public function kirim_otp_telegram()
    {
        $post    = $this->input->post();
        $userID  = $post['telegram_userID'];
        $token   = hash('sha256', $raw_token = random_int(100000, 999999));
        $id_pend = $this->session->is_login->id_pend;

        DB::beginTransaction();

        if ($this->otp->driver('telegram')->cekAkunTerdaftar(['telegram' => $userID, 'id' => $id_pend])) {
            try {
                // TODO: OpenKab - Perlu disesuaikan ulang setelah semua modul selesai
                Penduduk::where('id', $id_pend)->update([
                    'telegram'                => '-' . $userID,
                    'telegram_token'          => $token,
                    'telegram_tgl_kadaluarsa' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +5 minutes')),
                ]);

                $this->otp->driver('telegram')->kirimOtp($userID, $raw_token);

                DB::commit();
            } catch (Exception $e) {
                log_message('error', $e);

                $this->session->set_flashdata('notif_verifikasi', [
                    'status' => -1,
                    'pesan'  => 'Tidak berhasil mengirim OTP, silahkan mencoba kembali.',
                ]);

                DB::rollback();

                redirect('layanan-mandiri/verifikasi/telegram/#langkah-2');
            }

            $this->session->set_flashdata('notif_verifikasi', [
                'status' => 1,
                'pesan'  => 'OTP telegram anda berhasil terkirim, silahkan cek telegram anda!',
            ]);

            $this->session->set_flashdata('kirim-otp-telegram', '#langkah3');

            redirect('layanan-mandiri/verifikasi/telegram/#langkah-3');
        } else {
            $this->session->set_flashdata('notif_verifikasi', [
                'status' => -1,
                'pesan'  => 'Akun Telegram yang Anda Masukkan tidak valid, Silahkan ulangi lagi.',
            ]);
            redirect('layanan-mandiri/verifikasi/telegram/#langkah-2');
        }
    }

    /**
     * Langkah 3 Verifikasi Telegram
     */
    public function verifikasi_telegram()
    {
        $post = $this->input->post();
        $otp  = $post['token_telegram'];
        $user = $this->session->is_login->id_pend;
        $nama = $this->session->is_login->nama;
        // TODO: OpenKab - Perlu disesuaikan ulang setelah semua modul selesai
        $telegramID = Penduduk::find($user)->telegram;

        if ($this->otp->driver('telegram')->verifikasiOtp($otp, $user)) {
            $this->session->set_flashdata('notif_verifikasi', [
                'status' => 1,
                'pesan'  => 'Selamat, akun telegram anda berhasil terverifikasi.',
            ]);

            try {
                $this->otp->driver('telegram')->verifikasiBerhasil($telegramID, $nama);
            } catch (Exception $e) {
                log_message('error', $e);
            }

            redirect('layanan-mandiri/verifikasi/telegram/#langkah-4');
        }

        $this->session->set_flashdata('notif_verifikasi', [
            'status' => -1,
            'pesan'  => 'Tidak berhasil memverifikasi, Token tidak sesuai atau waktu Anda habis, silahkan mencoba kembali.',
        ]);

        redirect('layanan-mandiri/verifikasi/telegram/#langkah-2');
    }

    /**
     * Verifikasi Email
     */
    public function email()
    {
        $data = [
            'tgl_verifikasi_telegram' => $this->otp->driver('telegram')->cekVerifikasiOtp($this->is_login->id_pend),
            'tgl_verifikasi_email'    => $this->otp->driver('email')->cekVerifikasiOtp($this->is_login->id_pend),
            'form_kirim_email'        => site_url('layanan-mandiri/verifikasi/email/kirim-email'),
            'form_kirim_otp_email'    => site_url('layanan-mandiri/verifikasi/email/kirim-otp'),
        ];

        if ($data['tgl_verifikasi_telegram']) {
            $this->session->set_flashdata('sudah-diverifikasi', '#langkah4');
        }

        if ($data['tgl_verifikasi_email']) {
            $this->session->set_flashdata('sudah-diverifikasi-email', '#langkah4');
        }

        $this->session->set_flashdata('tab-aktif', [
            'status' => 1,
        ]);

        return view('layanan_mandiri.verifikasi.index', $data);
    }

    /**
     * Langkah 2 Verifikasi Email
     */
    public function kirim_otp_email()
    {
        $post    = $this->input->post();
        $email   = $post['alamat_email'];
        $token   = hash('sha256', $raw_token = random_int(100000, 999999));
        $id_pend = $this->session->is_login->id_pend;

        DB::beginTransaction();

        if ($this->otp->driver('email')->cekAkunTerdaftar(['email' => $email, 'id' => $id_pend])) {
            try {
                // TODO: OpenKab - Perlu disesuaikan ulang setelah semua modul selesai
                Penduduk::where('id', $id_pend)->update([
                    'email'                => $email,
                    'email_token'          => $token,
                    'email_tgl_kadaluarsa' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +5 minutes')),
                ]);

                $this->otp->driver('email')->kirimOtp($email, $raw_token);

                DB::commit();
            } catch (Exception $e) {
                log_message('error', $e);

                $this->session->set_flashdata('notif_verifikasi', [
                    'status' => -1,
                    'pesan'  => 'Tidak berhasil mengirim OTP, silahkan mencoba kembali.',
                ]);

                DB::rollback();

                redirect('layanan-mandiri/verifikasi/email/#langkah-2');
            }

            $this->session->set_flashdata('notif_verifikasi', [
                'status' => 1,
                'pesan'  => 'OTP email anda berhasil terkirim, silahkan cek email anda!',
            ]);

            $this->session->set_flashdata('kirim-otp-email', '#langkah3');

            redirect('layanan-mandiri/verifikasi/email/#langkah-3');
        } else {
            $this->session->set_flashdata('notif_verifikasi', [
                'status' => -1,
                'pesan'  => 'Akun Email yang Anda Masukkan tidak valid, Silahkan ulangi lagi.',
            ]);
            redirect('layanan-mandiri/verifikasi/email/#langkah-2');
        }
    }

    /**
     * Langkah 3 Verifikasi Email
     */
    public function verifikasi_email()
    {
        $post = $this->input->post();
        $otp  = $post['token_email'];
        $user = $this->session->is_login->id_pend;
        $nama = $this->session->is_login->nama;
        // TODO: OpenKab - Perlu disesuaikan ulang setelah semua modul selesai
        $email = Penduduk::find($user)->email;

        if ($this->otp->driver('email')->verifikasiOtp($otp, $user)) {
            $this->session->set_flashdata('notif_verifikasi', [
                'status' => 1,
                'pesan'  => 'Selamat, alamat email anda berhasil terverifikasi.',
            ]);

            try {
                $this->otp->driver('email')->verifikasiBerhasil($email, $nama);
            } catch (Exception $e) {
                log_message('error', $e);
            }

            redirect('layanan-mandiri/verifikasi/email/#langkah-4');
        }

        $this->session->set_flashdata('notif_verifikasi', [
            'status' => -1,
            'pesan'  => 'Tidak berhasil memverifikasi, Token tidak sesuai atau waktu Anda habis, silahkan mencoba kembali.',
        ]);

        redirect('layanan-mandiri/verifikasi/email/#langkah-2');
    }
}
