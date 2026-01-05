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

use OpenSID\MiddlewareInterface;

class AuthenticateSession implements MiddlewareInterface
{
    /**
     * CodeIgniter instance
     */
    protected $ci;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ci = &get_instance();
    }

    /**
     * {@inheritDoc}
     */
    public function run($args)
    {
        $request = request();

        // Jika user tidak login, lanjutkan
        if (! $request->user()) {
            return;
        }

        // Simpan password hash di session jika belum ada
        $authDriver = $this->getAuthDriver();
        if (! $this->ci->session->has_userdata("password_hash_{$authDriver}")) {
            $this->storePasswordHashInSession($request);
        }

        // Validasi password hash dari session
        if ($this->ci->session->userdata("password_hash_{$authDriver}") !== $request->user()->getAuthPassword()) {
            $this->logout();
        }
    }

    /**
     * Simpan password hash user di session.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function storePasswordHashInSession($request)
    {
        if (! $request->user()) {
            return;
        }

        $authDriver = $this->getAuthDriver();
        $this->ci->session->set_userdata([
            "password_hash_{$authDriver}" => $request->user()->getAuthPassword(),
        ]);
    }

    /**
     * Logout user dari aplikasi.
     *
     * @throws Illuminate\Auth\AuthenticationException
     *
     * @return void
     */
    protected function logout()
    {
        $this->ci->session->change_password       = true;
        $this->ci->session->force_change_password = false;
    }

    /**
     * Dapatkan guard instance.
     *
     * @return Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        return auth();
    }

    /**
     * Dapatkan auth driver default.
     *
     * @return string
     */
    protected function getAuthDriver()
    {
        return config('auth.defaults.guard', 'admin');
    }
}
