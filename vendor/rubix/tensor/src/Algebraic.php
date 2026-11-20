<?php

namespace Tensor;

interface Algebraic
{
    /**
     * Take the absolute value of the tensor.
     *
     * @return mixed
     */
    public function abs();

    /**
     * Square the tensor.
     *
     * @return mixed
     */
    public function square();

    /**
     * Return the square root of the tensor.
     *
     * @return mixed
     */
    public function sqrt();

    /**
     * Return the element-wise reciprocal of the tensor.
     *
     * @return mixed
     */
    public function reciprocal();

    /**
     * Return the exponential of the tensor.
     *
     * @return mixed
     */
    public function exp();

    /**
     * Return the exponential of the tensor minus 1.
     *
     * @return mixed
     */
    public function expm1();

    /**
     * Return the logarithm of the tensor in a specified base.
     *
     * @param float $base
     * @return mixed
     */
    public function log(float $base = M_E);

    /**
     * Return the log of 1 plus the tensor i.e. a transform.
     *
     * @return mixed
     */
    public function log1p();

    /**
     * Round the elements in the tensor to a given decimal place.
     *
     * @param int $precision
     * @return self
     */
    public function round(int $precision = 0);

    /**
     * Round the tensor down to the nearest integer.
     *
     * @return mixed
     */
    public function floor();

    /**
     * Round the tensor up to the nearest integer.
     *
     * @return mixed
     */
    public function ceil();

    /**
     * Return the element-wise sign indication.
     *
     * @return mixed
     */
    public function sign();

    /**
     * Negate the tensor i.e take the negative.
     *
     * @return mixed
     */
    public function negate();
}
