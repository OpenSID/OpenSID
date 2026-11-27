<?php

namespace Tensor;

interface Arithmetic
{
    /**
     * A universal function to multiply this tensor with another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function multiply($b);

    /**
     * A universal function to divide this tensor by another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function divide($b);

    /**
     * A universal function to add this tensor with another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function add($b);

    /**
     * A universal function to subtract this tensor from another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function subtract($b);

    /**
     * A universal function to raise this tensor to the power of another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function pow($b);

    /**
     * A universal function to compute the integer modulus of this tensor and another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function mod($b);
}
