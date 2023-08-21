<?php

namespace Laminas\Session;

use Laminas\EventManager\EventManager;
use Laminas\Session\Storage\StorageInterface;
use Laminas\Session\Validator\ValidatorInterface;

use function array_shift;
use function array_unshift;
use function is_array;

class ValidatorChain extends EventManager
{
    /** @var StorageInterface */
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        parent::__construct();

        $this->storage = $storage;
        $validators    = $storage->getMetadata('_VALID');
        if ($validators) {
            foreach ($validators as $validator => $data) {
                $this->attachValidator('session.validate', [new $validator($data), 'isValid'], 1);
            }
        }
    }

    /**
     * Attach a listener to the session validator chain.
     *
     * @param string   $eventName
     * @param int      $priority
     * @return callable
     */
    public function attach($eventName, callable $callback, $priority = 1)
    {
        return $this->attachValidator($eventName, $callback, $priority);
    }

    /**
     * Retrieve session storage object
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Internal implementation for attaching a listener to the
     * session validator chain.
     *
     * @param string   $event
     * @param callable $callback
     * @param int      $priority
     * @return callable
     */
    private function attachValidator($event, $callback, $priority)
    {
        $context = null;
        if ($callback instanceof ValidatorInterface) {
            $context = $callback;
        } elseif (is_array($callback)) {
            $test = array_shift($callback);
            if ($test instanceof ValidatorInterface) {
                $context = $test;
            }
            array_unshift($callback, $test);
        }
        if ($context instanceof ValidatorInterface) {
            $data = $context->getData();
            $name = $context->getName();
            $this->getStorage()->setMetadata('_VALID', [$name => $data]);
        }

        return parent::attach($event, $callback, $priority);
    }
}
