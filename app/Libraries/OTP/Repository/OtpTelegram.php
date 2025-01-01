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

namespace App\Libraries\OTP\Repository;

use App\Libraries\OTP\Interface\OtpInterface;
use App\Models\PendudukSaja;
use NotificationChannels\Telegram\Telegram;

class OtpTelegram implements OtpInterface
{
    protected Telegram $telegram;

    public function __construct()
    {
        $token          = setting('telegram_token');
        $this->telegram = new Telegram($token);
    }

    /**
     * {@inheritDoc}
     */
    public function kirimOtp($user, $otp)
    {
        if ($this->cekVerifikasiOtp($user)) {
            return true;
        }

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
    }

    /**
     * {@inheritDoc}
     */
    public function verifikasiOtp($otp, $user = null): bool
    {
        if ($this->cekVerifikasiOtp($user)) {
            return true;
        }
        $raw_token = hash('sha256', $otp);
        $token     = PendudukSaja::where('telegram_token', $raw_token)->first();
        if (! $token) {
            return false;
        }

        if (date('Y-m-d H:i:s') > $token->telegram_tgl_kadaluarsa) {
            return false;
        }

        if (hash_equals($token->telegram_token, $raw_token)) {
            PendudukSaja::where('id', $user)
                ->update([
                    'telegram_tgl_verifikasi' => date('Y-m-d H:i:s'),
                ]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function cekVerifikasiOtp($user): bool
    {
        $token = PendudukSaja::select(['telegram_tgl_verifikasi'])->where('id', $user)->first();

        return $token->telegram_tgl_verifikasi != null;
    }

    /**
     * {@inheritDoc}
     */
    public function verifikasiBerhasil($user, $nama): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $user,
            'text'    => <<<EOD
                HALO {$nama},

                SELAMAT AKUN TELEGRAM ANDA BERHASIL DIVERIFIKASI

                Terima kasih.
                EOD,
            'parse_mode' => 'Markdown',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function kirimPinBaru($user, $pin, $nama): void
    {
        $pesanTelegram = [
            '[nama]'    => $nama,
            '[website]' => APP_URL,
            '[pin]'     => $pin,
        ];

        $kirimPesan = setting('notifikasi_reset_pin');
        $kirimPesan = str_replace(array_keys($pesanTelegram), array_values($pesanTelegram), $kirimPesan);
        $this->telegram->sendMessage([
            'chat_id'    => $user,
            'text'       => $kirimPesan,
            'parse_mode' => 'Markdown',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function cekAkunTerdaftar($user): bool
    {
        $listId = is_array($user['id']) ? $user['id'] : [$user['id']];

        return PendudukSaja::where('telegram', $user['telegram'])->whereNotIn('id', $listId)->doesntExist();
    }

    /**
     * {@inheritDoc}
     */
    public function kirimPesan(array $data = []): void
    {
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
    }
}
