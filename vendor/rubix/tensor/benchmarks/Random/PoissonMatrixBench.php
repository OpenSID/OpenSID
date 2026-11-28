<?php

namespace Tensor\Benchmarks\Random;

use Tensor\Matrix;

/**
 * @Groups({"Random"})
 */
class PoissonMatrixBench
{
    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function poisson() : void
    {
        Matrix::poisson(500, 500);
    }
}
