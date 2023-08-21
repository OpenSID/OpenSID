<?php

namespace Laminas\Session\Service;

// phpcs:disable WebimpressCodingStandard.PHP.CorrectClassNameCase

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Session\Exception\ExceptionInterface as SessionException;
use Laminas\Session\Storage\Factory;
use Laminas\Session\Storage\StorageInterface;

use function is_array;
use function sprintf;

class StorageFactory implements FactoryInterface
{
    /**
     * Create session storage object (v3 usage).
     *
     * Uses "session_storage" section of configuration to seed a StorageInterface
     * instance. That array should contain the key "type", specifying the storage
     * type to use, and optionally "options", containing any options to be used in
     * creating the StorageInterface instance.
     *
     * @param string $requestedName
     * @return StorageInterface
     * @throws ServiceNotCreatedException If session_storage is missing, or the
     *         factory cannot create the storage instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config');
        if (! isset($config['session_storage']) || ! is_array($config['session_storage'])) {
            throw new ServiceNotCreatedException(
                'Configuration is missing a "session_storage" key, or the value of that key is not an array'
            );
        }

        $config = $config['session_storage'];
        if (! isset($config['type'])) {
            throw new ServiceNotCreatedException(
                '"session_storage" configuration is missing a "type" key'
            );
        }
        $type    = $config['type'];
        $options = $config['options'] ?? [];

        try {
            $storage = Factory::factory($type, $options);
        } catch (SessionException $e) {
            throw new ServiceNotCreatedException(sprintf(
                'Factory is unable to create StorageInterface instance: %s',
                $e->getMessage()
            ), $e->getCode(), $e);
        }

        return $storage;
    }

    /**
     * Create and return a storage instance (v2 usage).
     *
     * @param null|string $canonicalName
     * @param string $requestedName
     * @return StorageInterface
     */
    public function createService(
        ServiceLocatorInterface $services,
        $canonicalName = null,
        $requestedName = StorageInterface::class
    ) {
        return $this($services, $requestedName);
    }
}
