<?php

namespace Laminas\Session\Validator;

use Laminas\EventManager\EventManager;
use Laminas\Session\Storage\StorageInterface;
use Laminas\Stdlib\CallbackHandler;

/**
 * Abstract validator chain for validating sessions (for use with laminas-eventmanager v3)
 *
 * @deprecated Use {@see \Laminas\Session\ValidatorChain} directly
 */
abstract class AbstractValidatorChainEM3 extends EventManager
{
    use ValidatorChainTrait;

    /**
     * Construct the validation chain
     *
     * Retrieves validators from session storage and attaches them.
     *
     * Duplicated in ValidatorChainEM2 to prevent trait collision with parent.
     */
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
     * @param string $eventName
     * @param int $priority
     * @return CallbackHandler
     */
    public function attach($eventName, callable $callback, $priority = 1)
    {
        return $this->attachValidator($eventName, $callback, $priority);
    }
}
