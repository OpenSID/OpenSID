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
use App\Mail\GenericMail;
use App\Mail\NewPinMail;
use App\Mail\VerificationSuccessMail;
use App\Mail\VerifyEmail;
use App\Models\PendudukSaja;
use Exception;
use Illuminate\Support\Facades\Mail;

class OtpEmail implements OtpInterface
{
    public function __construct()
    {
        $configSmtp = config_email();
        config()->set('mail.mailer.smtp', [
            'transport'    => 'smtp',
            'url'          => null,
            'host'         => $configSmtp['smtp_host'],
            'port'         => $configSmtp['smtp_port'],
            'encryption'   => 'tls',
            'username'     => $configSmtp['smtp_user'],
            'password'     => $configSmtp['smtp_pass'],
            'timeout'      => null,
            'local_domain' => null,
        ]);
    }

    public function kirimOtp($user, $otp)
    {
        if ($this->cekVerifikasiOtp($user)) {
            return true;
        }

        Mail::to($user)->send(new VerifyEmail($otp));

        return true;
    }

    public function verifikasiOtp($otp, $user = null): bool
    {
        if ($this->cekVerifikasiOtp($user)) {
            return true;
        }
        $raw_token = hash('sha256', $otp);
        $token     = PendudukSaja::where('email_token', $raw_token)->first();

        if (null === $token) {
            return false;
        }

        if (date('Y-m-d H:i:s') > $token->email_tgl_kadaluarsa) {
            return false;
        }

        if (hash_equals($token->email_token, $raw_token)) {
            PendudukSaja::where('id', $user)
                ->update([
                    'email_tgl_verifikasi' => date('Y-m-d H:i:s'),
                ]);

            return true;
        }

        return false;
    }

    public function cekVerifikasiOtp($user): bool
    {
        $token = PendudukSaja::select(['email_tgl_verifikasi'])->where('id', $user)->first();

        return $token->email_tgl_verifikasi != null;
    }

    public function verifikasiBerhasil($email, $nama)
    {
        Mail::to($email)->send(new VerificationSuccessMail($nama));

        return true;
    }

    public function kirimPinBaru($user, $pin, $nama)
    {
        try {
            Mail::to($user)->send(new NewPinMail($pin, $nama));

            return true;
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode(), $th);
        }
    }

    public function cekAkunTerdaftar($user): bool
    {
        return PendudukSaja::where('email', $user['email'])->where('id', '!=', $user['id'])->doesntExist();
    }

    public function kirimPesan($data = [])
    {
        Mail::to($data['tujuan'])->send(new GenericMail($data['subjek'], $data['isi']));

        return true;
    }
}
