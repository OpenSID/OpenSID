<?php

namespace Tensor;

interface Comparable
{
    /**
     * A universal function to compute the equality comparison of a tensor and another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function equal($b);

    /**
     * A universal function to compute the not equal comparison of this tensor and another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function notEqual($b);

    /**
     * A universal function to compute the greater than comparison of a tensor and another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function greater($b);

    /**
     * A universal function to compute the greater than or equal to comparison of a tensor and another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function greaterEqual($b);

    /**
     * A universal function to compute the less than comparison of a tensor and another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function less($b);

    /**
     * A universal function to compute the less than or equal to comparison of a tensor and another tensor element-wise.
     *
     * @param mixed $b
     * @return mixed
     */
    public function lessEqual($b);
}
