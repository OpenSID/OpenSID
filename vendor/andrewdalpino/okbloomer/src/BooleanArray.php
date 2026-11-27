<?php

namespace OkBloomer;

use OkBloomer\Exceptions\InvalidArgumentException;
use ArrayAccess;
use Countable;

use function chr;
use function ord;
use function ceil;
use function str_repeat;

/**
 * Boolean Array
 *
 * A fixed array data structure that efficiently stores boolean values as bits of a byte array.
 *
 * @internal
 *
 * @category    Data Structures
 * @package     Scienide/OkBloomer
 * @author      Andrew DalPino
 *
 * @implements ArrayAccess<int, bool>
 */
class BooleanArray implements ArrayAccess, Countable
{
    /**
     * The number of bits in one byte.
     *
     * @var int
     */
    protected const ONE_BYTE = 8;

    /**
     * The number of elements in the array.
     *
     * @var int
     */
    protected int $size;

    /**
     * A byte array (string) containing the bits of the bitmap.
     *
     * @var string
     */
    protected string $bitmap;

    /**
     * @param int $size
     * @throws \OkBloomer\Exceptions\InvalidArgumentException
     */
    public function __construct(int $size)
    {
        if ($size < 0) {
            throw new InvalidArgumentException('size must be'
                . " greater than 0, $size given.");
        }

        $numBytes = (int) ceil($size / self::ONE_BYTE);

        $this->size = $size;
        $this->bitmap = str_repeat(chr(0), $numBytes);
    }

    /**
     * Return the size of the boolean array.
     *
     * @return int
     */
    public function size() : int
    {
        return $this->size;
    }

    /**
     * @param int $offset
     * @param bool $value
     * @throws \OkBloomer\Exceptions\InvalidArgumentException
     */
    public function offsetSet($offset, $value) : void
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidArgumentException("Item at offset $offset not found.");
        }

        $byteOffset = intdiv($offset, self::ONE_BYTE);

        $byte = ord($this->bitmap[$byteOffset]);

        $position = 2 ** ($offset % self::ONE_BYTE);

        if ($value) {
            $byte |= $position;
        } else {
            $byte &= 0xFF ^ $position;
        }

        $this->bitmap[$byteOffset] = chr($byte);
    }

    /**
     * Does a given row exist in the dataset.
     *
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset) : bool
    {
        if ($offset < 0 or $offset >= $this->size) {
            return false;
        }

        return true;
    }

    /**
     * Return a row from the dataset at the given offset.
     *
     * @param int $offset
     * @throws \OkBloomer\Exceptions\InvalidArgumentException
     * @return bool
     */
    public function offsetGet($offset) : bool
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidArgumentException("Item at offset $offset not found.");
        }

        $byteOffset = intdiv($offset, self::ONE_BYTE);

        $byte = ord($this->bitmap[$byteOffset]);

        $position = 2 ** ($offset % self::ONE_BYTE);

        $bit = $position & $byte;

        return (bool) $bit;
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset) : void
    {
        $this->offsetSet($offset, false);
    }

    /**
     * The number of elements that are stored in the array.
     *
     * @return int
     */
    public function count() : int
    {
        return $this->size;
    }
}
