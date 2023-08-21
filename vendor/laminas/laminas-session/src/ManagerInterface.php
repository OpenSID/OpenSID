<?php

namespace Laminas\Session;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Session\Config\ConfigInterface as Config;
use Laminas\Session\SaveHandler\SaveHandlerInterface as SaveHandler;
use Laminas\Session\Storage\StorageInterface as Storage;

/**
 * Session manager interface
 */
interface ManagerInterface
{
    /** @return self */
    public function setConfig(Config $config);

    /** @return Config */
    public function getConfig();

    /** @return self */
    public function setStorage(Storage $storage);

    /** @return Storage */
    public function getStorage();

    /** @return self */
    public function setSaveHandler(SaveHandler $saveHandler);

    /** @return SaveHandler */
    public function getSaveHandler();

    /** @return bool */
    public function sessionExists();

    /** @return void */
    public function start();

    /** @return void */
    public function destroy();

    /** @return void */
    public function writeClose();

    /**
     * @param string $name
     * @return self
     */
    public function setName($name);

    /** @return string */
    public function getName();

    /**
     * @param int|string $id
     * @return self
     */
    public function setId($id);

    /** @return int|string */
    public function getId();

    /** @return self */
    public function regenerateId();

    /**
     * @param null|int $ttl
     * @return self
     */
    public function rememberMe($ttl = null);

    /** @return self */
    public function forgetMe();

    /** @return void */
    public function expireSessionCookie();

    /** @return self */
    public function setValidatorChain(EventManagerInterface $chain);

    /** @return EventManagerInterface */
    public function getValidatorChain();

    /** @return bool */
    public function isValid();
}
