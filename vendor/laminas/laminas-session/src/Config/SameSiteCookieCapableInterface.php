<?php

namespace Laminas\Session\Config;

interface SameSiteCookieCapableInterface
{
    /**
     * @param string $cookieSameSite
     * @return self
     */
    public function setCookieSameSite($cookieSameSite);

    /** @return string */
    public function getCookieSameSite();
}
