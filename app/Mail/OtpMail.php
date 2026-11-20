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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable;
 use SerializesModels;

    /**
     * @var int
     */
    public $otp;

    /**
     * @var string
     */
    public $purpose;

    public $expiryMinutes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $otp, string $purpose = 'login')
    {
        $this->otp           = $otp;
        $this->purpose       = $purpose;
        $this->expiryMinutes = setting('otp_expiry_minutes');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = match ($this->purpose) {
            'activation'     => 'Kode OTP Aktivasi - ' . ucwords((string) setting('sebutan_desa')) . ' ' . identitas('nama_desa'),
            '2fa_activation' => 'Kode OTP Aktivasi 2FA - ' . ucwords((string) setting('sebutan_desa')) . ' ' . identitas('nama_desa'),
            default          => 'Kode OTP Login - ' . ucwords((string) setting('sebutan_desa')) . ' ' . identitas('nama_desa'),
        };

        return $this->subject($subject)
            ->view('email.otp')
            ->with([
                'otp'           => $this->otp,
                'purpose'       => $this->purpose,
                'expiryMinutes' => $this->expiryMinutes,
            ]);
    }
}
