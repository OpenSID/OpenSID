<?php

namespace Tensor\Benchmarks\Random;

use Tensor\Matrix;

/**
 * @Groups({"Random"})
 */
class GaussianMatrixBench
{
    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function gaussian() : void
    {
        Matrix::gaussian(500, 500);
    }
}
