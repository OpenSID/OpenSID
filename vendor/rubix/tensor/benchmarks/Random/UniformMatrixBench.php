<?php

namespace Tensor\Benchmarks\Random;

use Tensor\Matrix;

/**
 * @Groups({"Random"})
 */
class UniformMatrixBench
{
    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function uniform() : void
    {
        Matrix::uniform(500, 500);
    }
}
