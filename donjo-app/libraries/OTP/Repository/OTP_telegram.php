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

require_once 'donjo-app/libraries/OTP/Interface/OTP_interface.php';
require_once 'donjo-app/libraries/Telegram/Telegram.php';

class OTP_telegram implements OTP_interface
{
    /**
     * Intance class codeigniter.
     *
     * @var CI_Controller
     */
    protected $ci;

    /**
     * @var Telegram
     */
    protected $telegram;

    public function __construct()
    {
        $this->ci       = get_instance();
        $this->telegram = new Telegram();
    }

    /**
     * {@inheritDoc}
     */
    public function kirim_otp($user, $otp)
    {
        if ($this->cek_verifikasi_otp($user)) {
            return true;
        }

        try {
            $this->telegram->sendMessage([
                'chat_id' => $user,
                'text'    => <<<EOD
                    Kode Verifikasi OTP Anda: {$otp}

                    JANGAN BERIKAN KODE RAHASIA INI KEPADA SIAPA PUN,
                    TERMASUK PIHAK YANG MENGAKU DARI DESA ANDA.

                    Terima kasih.
                    EOD,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function verifikasi_otp($otp, $user = null)
    {
        if ($this->cek_verifikasi_otp($user)) {
            return true;
        }

        $token                                   = $this->ci->db->from('tweb_penduduk')
            ->where('telegram_token', $raw_token = hash('sha256', $otp))
            ->get()
            ->row();

        if (null === $token) {
            return false;
        }

        if (date('Y-m-d H:i:s') > $token->telegram_tgl_kadaluarsa) {
            return false;
        }

        if (hash_equals($token->telegram_token, $raw_token)) {
            $this->ci->db
                ->where('id', $user)
                ->update('tweb_penduduk', [
                    'telegram_tgl_verifikasi' => date('Y-m-d H:i:s'),
                ]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function cek_verifikasi_otp($user)
    {
        $token = $this->ci->db->from('tweb_penduduk')
            ->select('telegram_tgl_verifikasi')
            ->where('id', $user)
            ->get()
            ->row();

        return (bool) ($token->telegram_tgl_verifikasi != null);
    }

    /**
     * {@inheritDoc}
     */
    public function verifikasi_berhasil($user, $nama)
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => $user,
                'text'    => <<<EOD
                    HALO {$nama},

                    SELAMAT AKUN TELEGRAM ANDA BERHASIL DIVERIFIKASI

                    Terima kasih.
                    EOD,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function kirim_pin_baru($user, $pin, $nama)
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => $user,
                'text'    => <<<EOD
                    HALO {$nama},

                    BERIKUT ADALAH KODE PIN YANG BARU SAJA DIHASILKAN,
                    KODE PIN INI SANGAT RAHASIA
                    JANGAN BERIKAN KODE PIN KEPADA SIAPA PUN,
                    TERMASUK PIHAK YANG MENGAKU DARI DESA ANDA.

                    KODE PIN: {$pin}

                    JIKA BUKAN ANDA YANG MELAKUKAN RESET PIN TERSEBUT
                    SILAHKAN LAPORKAN KEPADA OPERATOR DESA

                    EOD,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function cek_akun_terdaftar($user)
    {
        return isset($this->ci->db)
            ? ($this->ci->db->where('telegram', $user['telegram'])->where_not_in('id', $user['id'])->get('tweb_penduduk')->num_rows() === 0)
            : false;
    }

    /**
     * {@inheritDoc}
     */
    public function kirim_pesan($data = [])
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => $data['tujuan'],
                'text'    => <<<EOD
                    SUBJEK :
                    {$data['subjek']}

                    ISI :
                    {$data['isi']}
                    EOD,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
