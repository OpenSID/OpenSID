<?php

namespace Tensor\Benchmarks\Decompositions;

use Tensor\Matrix;

/**
 * @Groups({"Decompositions"})
 * @BeforeMethods({"setUp"})
 */
class CholeskyBench
{
    /**
     * @var Matrix
     */
    protected $a;

    public function setUp() : void
    {
        $this->a = Matrix::rand(500, 500);
    }

    /**
     * @Skip
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function cholesky() : void
    {
        $this->a->cholesky();
    }
}
