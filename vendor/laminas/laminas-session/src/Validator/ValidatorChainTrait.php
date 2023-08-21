<?php

namespace Laminas\Session\Validator;

use Laminas\Session\Storage\StorageInterface;
use Laminas\Stdlib\CallbackHandler;

use function array_shift;
use function array_unshift;
use function is_array;

/**
 * Base trait for validator chain implementations
 *
 * @deprecated Use {@see \Laminas\Session\ValidatorChain} directly
 */
trait ValidatorChainTrait
{
    /** @var StorageInterface */
    protected $storage;

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
     * @param  string $event
     * @param  callable $callback
     * @param  int $priority
     * @return CallbackHandler|callable
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
