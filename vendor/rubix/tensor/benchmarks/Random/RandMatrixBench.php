<?php

namespace Tensor\Benchmarks\Random;

use Tensor\Matrix;

/**
 * @Groups({"Random"})
 */
class RandMatrixBench
{
    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function rand() : void
    {
        Matrix::rand(500, 500);
    }
}
