<?php

namespace Laminas\Session\Storage;

use ArrayAccess;
use Countable;
use Serializable;
use Traversable;

/**
 * Session storage interface
 *
 * Defines the minimum requirements for handling userland, in-script session
 * storage (e.g., the $_SESSION superglobal array).
 */
interface StorageInterface extends Traversable, ArrayAccess, Serializable, Countable
{
    /** @return float */
    public function getRequestAccessTime();

    /**
     * @param null|int|string $key
     * @return self
     */
    public function lock($key = null);

    /**
     * @param null|int|string $key
     * @return bool
     */
    public function isLocked($key = null);

    /**
     * @param null|int|string $key
     * @return self
     */
    public function unlock($key = null);

    /** @return self */
    public function markImmutable();

    /** @return bool */
    public function isImmutable();

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $overwriteArray
     * @return self
     */
    public function setMetadata($key, $value, $overwriteArray = false);

    /**
     * @param null|int|string $key
     * @return mixed
     */
    public function getMetadata($key = null);

    /**
     * @param null|int|string $key
     * @return self
     */
    public function clear($key = null);

    /**
     * @param array $array
     * @return self
     */
    public function fromArray(array $array);

    /**
     * @param bool $metadata
     * @return array
     */
    public function toArray($metadata = false);
}
