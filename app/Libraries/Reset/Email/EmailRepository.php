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

namespace App\Libraries\Reset\Email;

use App\Libraries\Reset\Interface\PasswordInterface;
use App\Libraries\Reset\Interface\PasswordResetInterface;
use App\Mail\ResetPasswordMail;
use App\Mail\VerificationMail;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Mail;

class EmailRepository implements PasswordInterface
{
    protected PasswordResetInterface $tokens;

    public function __construct(PasswordResetInterface $token)
    {
        $this->tokens = $token;
        $configSmtp   = config_email();
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

    public function sendResetLink(array $credentials, ?Closure $callback = null): string
    {
        $user = $this->getUser($credentials);

        if (null === $user) {
            return static::INVALID_USER;
        }

        if ($this->tokens->recentlyCreatedToken($user)) {
            return static::RESET_THROTTLED;
        }

        $token = $this->tokens->create($user);

        if ($callback instanceof Closure) {
            $callback($user, $token);
        } else {
            // Send the reset password email
            Mail::to($user->email)->send(new ResetPasswordMail($token, $user->email));

            return static::RESET_LINK_SENT;
        }

        return static::RESET_LINK_SENT;
    }

    public function sendVerifyLink(array $credentials, ?Closure $callback = null): string
    {
        $user = $this->getUser($credentials);

        if (null === $user) {
            return static::INVALID_USER;
        }

        if ($callback instanceof Closure) {
            $callback($user);
        } else {
            // Send the email verification link
            Mail::to($user->email)->send(new VerificationMail($user));

            return static::VERIFY_LINK_SENT;
        }

        return static::VERIFY_LINK_SENT;
    }

    public function reset(array $credentials, Closure $callback)
    {
        $user = $this->validateReset($credentials);

        if (in_array($user, [static::INVALID_USER, static::INVALID_TOKEN])) {
            return $user;
        }

        $password = $credentials['password'];

        $callback($user, $password);
        $this->tokens->destroy($user);

        return static::PASSWORD_RESET;
    }

    public function getUser(array $credentials)
    {
        $credentials = array_except($credentials, ['token', 'password']);

        return User::where($credentials)->first();
    }

    public function createToken($user)
    {
        return $this->tokens->create($user);
    }

    public function deleteToken($user): void
    {
        $this->tokens->destroy($user);
    }

    public function tokenExists($user, $token)
    {
        return $this->tokens->exists($user, $token);
    }

    public function getRepository(): PasswordResetInterface
    {
        return $this->tokens;
    }

    protected function validateReset(array $credentials)
    {
        if (null === ($user = $this->getUser($credentials))) {
            return static::INVALID_USER;
        }

        if (! $this->tokens->exists($user, $credentials['token'])) {
            return static::INVALID_TOKEN;
        }

        return $user;
    }
}
