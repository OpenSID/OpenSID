<?php

namespace Tensor\Benchmarks\Random;

use Tensor\Vector;

/**
 * @Groups({"Random"})
 */
class GaussianMVectorBench
{
    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function gaussian() : void
    {
        Vector::gaussian(100000);
    }
}
