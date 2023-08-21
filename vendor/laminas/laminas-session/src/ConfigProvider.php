<?php

namespace Laminas\Session;

use Zend\Session\Config\ConfigInterface;
use Zend\Session\Storage\StorageInterface;

class ConfigProvider
{
    /**
     * Retrieve configuration for laminas-session.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Retrieve dependency config for laminas-session.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'abstract_factories' => [
                Service\ContainerAbstractServiceFactory::class,
            ],
            'aliases'            => [
                SessionManager::class => ManagerInterface::class,

                // Legacy Zend Framework aliases
                \Zend\Session\SessionManager::class   => SessionManager::class,
                ConfigInterface::class                => Config\ConfigInterface::class,
                \Zend\Session\ManagerInterface::class => ManagerInterface::class,
                StorageInterface::class               => Storage\StorageInterface::class,
            ],
            'factories'          => [
                Config\ConfigInterface::class   => Service\SessionConfigFactory::class,
                ManagerInterface::class         => Service\SessionManagerFactory::class,
                Storage\StorageInterface::class => Service\StorageFactory::class,
            ],
        ];
    }
}
