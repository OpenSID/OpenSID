<?php

namespace Tensor\Benchmarks\LinearAlgebra;

use Tensor\Matrix;

/**
 * @Groups({"LinearAlgebra"})
 * @BeforeMethods({"setUp"})
 */
class MatrixL2NormBench
{
    /**
     * @var Matrix
     */
    protected $a;

    public function setUp() : void
    {
        $this->a = Matrix::uniform(500, 500);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function l2Norm() : void
    {
        $this->a->l2Norm();
    }
}
