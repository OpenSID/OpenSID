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

require_once 'donjo-app/libraries/Reset/Interface/Password_reset_interface.php';

class Password_repository implements Password_reset_interface
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

    /**
     * The number of seconds a token should last.
     */
    protected int $expires;

    /**
     * Minimum number of seconds before re-redefining the token.
     */
    protected int $throttle;

    public function __construct(int $expires = 60, int $throttle = 60)
    {
        $this->ci         = get_instance();
        $this->connection = $this->ci->db;
        $this->expires    = $expires * 60;
        $this->throttle   = $throttle;
    }

    /**
     * {@inheritDoc}
     */
    public function create($user): string
    {
        $email = $user->email;

        $this->destroy($user);

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $token = $this->createNewToken();

        $this->connection->set([
            'email'      => $email,
            'token'      => password_hash($token, PASSWORD_BCRYPT),
            'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
        ])->insert('password_resets');

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function createNewToken(): string
    {
        return hash_hmac('sha256', bin2hex(random_bytes(20)), config_item('encryption_key'));
    }

    /**
     * {@inheritDoc}
     */
    public function exists($user, $token): bool
    {
        $record = $this->connection->where('email', $user->email)->get('password_resets')->row();

        $expiredAt = (new DateTime())->sub(DateInterval::createFromDateString("{$this->expires} seconds"))->format('Y-m-d H:i:s');

        return $record && $record->created_at > $expiredAt && password_verify($token, $record->token);
    }

    /**
     * {@inheritDoc}
     */
    public function recentlyCreatedToken($user): bool
    {
        if ($this->throttle <= 0) {
            return false;
        }

        $record = $this->connection->where('email', $user->email)->get('password_resets')->row();

        $expiredAt = (new DateTime())->sub(DateInterval::createFromDateString("{$this->throttle} seconds"))->format('Y-m-d H:i:s');

        return $record && $record->created_at > $expiredAt;
    }

    /**
     * {@inheritDoc}
     */
    public function destroy($user)
    {
        return $this->connection->where('email', $user->email)->delete('password_resets');
    }

    /**
     * {@inheritDoc}
     */
    public function destroyExpired()
    {
        $expiredAt = (new DateTime())->sub(DateInterval::createFromDateString("{$this->expires} seconds"))->format('Y-m-d H:i:s');

        return $this->connection->where('created_at <', $expiredAt)->delete('password_resets');
    }
}
