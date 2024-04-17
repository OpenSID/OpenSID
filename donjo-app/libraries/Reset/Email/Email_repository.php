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

require_once 'donjo-app/libraries/Reset/Interface/Password_interface.php';
require_once 'donjo-app/libraries/Reset/Interface/Password_reset_interface.php';

class Email_repository implements Password_interface
{
    /**
     * The active database connection.
     *
     * @var CI_DB_query_builder
     */
    protected $connection;

    /**
     * Intance class codeigniter.
     *
     * @var CI_Controller
     */
    protected $ci;

    protected Password_reset_interface $tokens;

    public function __construct(Password_reset_interface $token)
    {
        $this->ci         = get_instance();
        $this->connection = $this->ci->db;
        $this->tokens     = $token;

        $this->ci->load->library('email');
        $this->ci->email->initialize(config_email());
    }

    /**
     * {@inheritDoc}
     */
    public function sendResetLink(array $credentials, ?Closure $callback = null): string
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
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
            // Once we have the reset token, we are ready to send the message out to this
            // user with a link to reset their password. We will then redirect back to
            // the current URI having nothing set in the session to indicate errors.
            $this->ci->email->from($this->ci->email->smtp_user, 'OpenSID')
                ->to($user->email)
                ->subject('Setel Ulang Kata Sandi')
                ->set_mailtype('html')
                ->message($this->ci->load->view('autentikasi/notifikasi_lupa_sandi', [
                    'token' => $token,
                    'email' => $user->email,
                ], true));

            if ($this->ci->email->send()) {
                return static::RESET_LINK_SENT;
            }

            throw new Exception($this->ci->email->print_debugger());
        }

        return static::RESET_LINK_SENT;
    }

    /**
     * {@inheritDoc}
     */
    public function sendVerifyLink(array $credentials, ?Closure $callback = null): string
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = $this->getUser($credentials);

        if (null === $user) {
            return static::INVALID_USER;
        }

        if ($callback instanceof Closure) {
            $callback($user);
        } else {
            // We are ready to send verify the message out to this user with a link
            // to their email. We will then redirect back to the current URI
            // having nothing set in the session to indicate errors.
            $this->ci->email->from($this->ci->email->smtp_user, 'OpenSID')
                ->to($user->email)
                ->subject('Verifikasi Alamat Email')
                ->set_mailtype('html')
                ->message($this->ci->load->view('autentikasi/notifikasi_verifikasi_email', [
                    'hash'      => sha1($user->email),
                    'expire'    => strtotime(date('Y-m-d H:i:s') . ' +60 minutes'),
                    'signature' => hash_hmac('sha256', $user->email, config_item('encryption_key')),
                ], true));

            if ($this->ci->email->send()) {
                return static::VERIFY_LINK_SENT;
            }

            throw new Exception($this->ci->email->print_debugger());
        }

        return static::VERIFY_LINK_SENT;
    }

    /**
     * {@inheritDoc}
     */
    public function reset(array $credentials, Closure $callback)
    {
        $user = $this->validateReset($credentials);

        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        if (in_array($user, [static::INVALID_USER, static::INVALID_TOKEN])) {
            return $user;
        }

        $password = $credentials['password'];

        // Once the reset has been validated, we'll call the given callback with the
        // new password. This gives the user an opportunity to store the password
        // in their persistent storage. Then we'll delete the token and return.
        $callback($user, $password);

        $this->tokens->destroy($user);

        return static::PASSWORD_RESET;
    }

    /**
     * {@inheritDoc}
     */
    public function getUser(array $credentials)
    {
        $credentials = except($credentials, ['token', 'password']);

        return $this->connection->where($credentials)->get('user')->row();
    }

    /**
     * {@inheritDoc}
     */
    public function createToken($user)
    {
        return $this->tokens->create($user);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteToken($user): void
    {
        $this->tokens->destroy($user);
    }

    /**
     * {@inheritDoc}
     */
    public function tokenExists($user, $token)
    {
        return $this->tokens->exists($user, $token);
    }

    /**
     * {@inheritDoc}
     */
    public function getRepository(): Password_reset_interface
    {
        return $this->tokens;
    }

    /**
     * Validate a password reset for the given credentials.
     *
     * @return mixed
     */
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
