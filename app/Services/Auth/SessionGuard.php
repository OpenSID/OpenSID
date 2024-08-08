<?php

namespace App\Services\Auth;

use Illuminate\Support\Timebox;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Request;

class SessionGuard extends \Illuminate\Auth\SessionGuard
{
    /**
     * Overwrite to ci session used by the guard.
     *
     * @var \CI_Session
     */
    protected $session;

    public function __construct(
        $name,
        UserProvider $provider,
        Session $session,
        Request $request = null,
        Timebox $timebox = null,
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
        if (!is_null($this->user)) {
            return $this->user;
        }

        $id = $this->session->userdata($this->getName());

        // Attempt to retrieve the user by session identifier
        if (!is_null($id)) {
            $this->user = $this->provider->retrieveById($id);

            if ($this->user) {
                $this->fireAuthenticatedEvent($this->user);
                return $this->user;
            }
        }

        // Attempt to retrieve the user by remember me cookie if session retrieval fails
        if (is_null($this->user)) {
            $recaller = $this->recaller();

            if (!is_null($recaller)) {
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

        if (! is_null($this->recaller())) {
            $this->getCookieJar()->queue(
                $this->getCookieJar()->forget($this->getRecallerName())
            );
        }
    }
}
