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

class Verifikasi extends Mandiri_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
    }

    public function telegram()
    {
        $data = [
            'tgl_verifikasi'    => $this->otp_library->driver('telegram')->cek_verifikasi_otp($this->is_login->id_pend),
            'form_kirim_userid' => site_url('layanan-mandiri/verifikasi/telegram/kirim-userid'),
            'form_kirim_otp'    => site_url('layanan-mandiri/verifikasi/telegram/kirim-otp'),
        ];

        if ($data['tgl_verifikasi']) {
            $this->session->set_flashdata('sudah-diverifikasi', '#langkah4');
        }

        $this->render('verifikasi', $data);
    }

    /**
     * Langkah 2
     */
    public function kirim_otp_telegram()
    {
        $post    = $this->input->post();
        $userID  = $post['telegram_userID'];
        $token   = hash('sha256', $raw_token = mt_rand(100000, 999999));
        $id_pend = $this->session->is_login->id_pend;

        $this->db->trans_begin();

        if ($this->otp_library->driver('telegram')->cek_akun_terdaftar(['telegram' => $userID, 'id' => $id_pend])) {
            try {
                $this->db->where('id', $id_pend)->update('tweb_penduduk', [
                    'telegram'                => $userID,
                    'telegram_token'          => $token,
                    'telegram_tgl_kadaluarsa' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 minutes')),
                ]);

                $this->otp_library->driver('telegram')->kirim_otp($userID, $raw_token);

                $this->db->trans_commit();
            } catch (\Exception $e) {
                log_message('error', $e);

                $this->session->set_flashdata('notif_telegram', [
                    'status' => -1,
                    'pesan'  => 'Tidak berhasil mengirim OTP, silahkan mencoba kembali.',
                ]);

                $this->db->trans_rollback();

                redirect('layanan-mandiri/verifikasi/telegram/#langkah-2');
            }

            $this->session->set_flashdata('notif_telegram', [
                'status' => 1,
                'pesan'  => 'OTP telegram anda berhasil terkirim, silahkan cek telegram anda!',
            ]);

            $this->session->set_flashdata('kirim-otp-telegram', '#langkah3');

            redirect('layanan-mandiri/verifikasi/telegram/#langkah-3');
        } else {
            $this->session->set_flashdata('notif_telegram', [
                'status' => -1,
                'pesan'  => 'Akun Telegram yang Anda Masukkan tidak valid, Silahkan ulangi lagi.',
            ]);
            redirect('layanan-mandiri/verifikasi/telegram/#langkah-2');
        }
    }

    /**
     * Langkah 3
     */
    public function verifikasi_telegram()
    {
        $post       = $this->input->post();
        $otp        = $post['token_telegram'];
        $user       = $this->session->is_login->id_pend;
        $telegramID = $this->db->where('id', $user)->get('tweb_penduduk')->row()->telegram;

        if ($this->otp_library->driver('telegram')->verifikasi_otp($otp, $user)) {
            $this->session->set_flashdata('notif_telegram', [
                'status' => 1,
                'pesan'  => 'Selamat, akun telegram anda berhasil terverifikasi.',
            ]);

            try {
                $this->otp_library->driver('telegram')->verifikasi_berhasil($telegramID);
            } catch (\Exception $e) {
                log_message('error', $e);
            }

            redirect('layanan-mandiri/verifikasi/telegram/#langkah-4');
        }

        $this->session->set_flashdata('notif_telegram', [
            'status' => -1,
            'pesan'  => 'Tidak berhasil memverifikasi, Token tidak sesuai atau waktu Anda habis, silahkan mencoba kembali.',
        ]);

        redirect('layanan-mandiri/verifikasi/telegram/#langkah-2');
    }
}
