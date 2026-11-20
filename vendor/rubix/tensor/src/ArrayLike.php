<?php

namespace Tensor;

use IteratorAggregate;
use ArrayAccess;
use Countable;

/**
 * @extends ArrayAccess<int, mixed>
 * @extends IteratorAggregate<mixed>
 */
interface ArrayLike extends ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Return a tuple with the dimensionality of the array-like.
     *
     * @return int[]
     */
    public function shape() : array;

    /**
     * Return the shape of the tensor as a string.
     *
     * @return string
     */
    public function shapeString() : string;

    /**
     * Return the number of elements in the tensor.
     *
     * @return int
     */
    public function size() : int;

    /**
     * Map a function over all of the elements in the tensor.
     *
     * @param callable $callback
     * @return mixed
     */
    public function map(callable $callback);

    /**
     * Reduce the tensor down to a scalar.
     *
     * @param callable $callback
     * @param float $initial
     * @return float
     */
    public function reduce(callable $callback, float $initial = 0.0) : float;

    /**
     * Return the elements of the tensor as an array.
     *
     * @return mixed[]
     */
    public function asArray() : array;
}
