<?php

namespace Tensor;

interface Special
{
    /**
     * Sum the tensor.
     *
     * @return mixed
     */
    public function sum();

    /**
     * Calculate the product of the tensor.
     *
     * @return mixed
     */
    public function product();

    /**
     * Return the minimum of the tensor.
     *
     * @return mixed
     */
    public function min();

    /**
     * Return the maximum of the tensor.
     *
     * @return mixed
     */
    public function max();

    /**
     * Clip the tensor to be between the given minimum and maximum.
     *
     * @param float $min
     * @param float $max
     * @return mixed
     */
    public function clip(float $min, float $max);

    /**
     * Clip the tensor to be lower bounded by a given minimum.
     *
     * @param float $min
     * @return mixed
     */
    public function clipLower(float $min);

    /**
     * Clip the tensor to be upper bounded by a given maximum.
     *
     * @param float $max
     * @return mixed
     */
    public function clipUpper(float $max);
}
