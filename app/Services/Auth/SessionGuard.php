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

namespace App\Services\Auth;

use CI_Session;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Timebox;
use Symfony\Component\HttpFoundation\Request;

class SessionGuard extends \Illuminate\Auth\SessionGuard
{
    /**
     * Overwrite to ci session used by the guard.
     *
     * @var CI_Session
     */
    protected $session;

    public function __construct(
        $name,
        UserProvider $provider,
        Session $session,
        ?Request $request = null,
        ?Timebox $timebox = null,
    ) {
        parent::__construct(
            $name,
            $provider,
            $session,
            $request,
            $timebox
        );

        $this->session = app('ci')->session;
    }

    public function user()
    {
        if ($this->loggedOut) {
            return;
        }

        // Return the user if already retrieved for the current request
        if (null !== $this->user) {
            return $this->user;
        }

        $id = $this->session->userdata($this->getName());

        // Attempt to retrieve the user by session identifier
        if (null !== $id) {
            $this->user = $this->provider->retrieveById($id);

            if ($this->user) {
                $this->fireAuthenticatedEvent($this->user);

                return $this->user;
            }
        }

        // Attempt to retrieve the user by remember me cookie if session retrieval fails
        if (null === $this->user) {
            $recaller = $this->recaller();

            if (null !== $recaller) {
                $this->user = $this->userFromRecaller($recaller);

                if ($this->user) {
                    $this->updateSession($this->user->getAuthIdentifier());
                    $this->fireLoginEvent($this->user, true);

                    return $this->user;
                }
            }
        }

        return $this->user;
    }

    public function id()
    {
        if ($this->loggedOut) {
            return;
        }

        return $this->user()
            ? $this->user()->getAuthIdentifier()
            : $this->session->userdata($this->getName());
    }

    protected function updateSession($id)
    {
        $this->session->set_userdata($this->getName(), $id);
        $this->session->sess_regenerate(true);
    }

    /**
     * Remove the user data from the session and cookies.
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        $this->session->unset_userdata($this->getName());

        $this->getCookieJar()->unqueue($this->getRecallerName());

        if (null !== $this->recaller()) {
            $this->getCookieJar()->queue(
                $this->getCookieJar()->forget($this->getRecallerName())
            );
        }
    }
}
