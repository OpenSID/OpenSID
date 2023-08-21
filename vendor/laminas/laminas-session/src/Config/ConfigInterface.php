<?php

namespace Laminas\Session\Config;

/**
 * Standard session configuration
 */
interface ConfigInterface
{
    /**
     * @param array<string, mixed> $options
     * @return void
     */
    public function setOptions($options);

    /** @return array<string, mixed> */
    public function getOptions();

    /**
     * @param string $option
     * @param mixed $value
     * @return void
     */
    public function setOption($option, $value);

    /**
     * @param string $option
     * @return mixed
     */
    public function getOption($option);

    /**
     * @param string $option
     * @return bool
     */
    public function hasOption($option);

    /** @return array */
    public function toArray();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /** @return string */
    public function getName();

    /**
     * @param string $savePath
     * @return void
     */
    public function setSavePath($savePath);

    /** @return string */
    public function getSavePath();

    /**
     * @param int $cookieLifetime
     * @return void
     */
    public function setCookieLifetime($cookieLifetime);

    /** @return int */
    public function getCookieLifetime();

    /**
     * @param string $cookiePath
     * @return void
     */
    public function setCookiePath($cookiePath);

    /** @return string */
    public function getCookiePath();

    /**
     * @param string $cookieDomain
     * @return void
     */
    public function setCookieDomain($cookieDomain);

    /** @return string */
    public function getCookieDomain();

    /**
     * @param bool $cookieSecure
     * @return void
     */
    public function setCookieSecure($cookieSecure);

    /** @return bool */
    public function getCookieSecure();

    /**
     * @param bool $cookieHttpOnly
     * @return void
     */
    public function setCookieHttpOnly($cookieHttpOnly);

    /** @return bool */
    public function getCookieHttpOnly();

    /**
     * @param bool $useCookies
     * @return void
     */
    public function setUseCookies($useCookies);

    /** @return bool */
    public function getUseCookies();

    /**
     * @param int $rememberMeSeconds
     * @return void
     */
    public function setRememberMeSeconds($rememberMeSeconds);

    /** @return int */
    public function getRememberMeSeconds();
}
