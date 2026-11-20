<?php

namespace Tensor\Benchmarks\Functions;

use Tensor\Matrix;

/**
 * @Groups({"Functions"})
 * @BeforeMethods({"setUp"})
 */
class ExpMatrixBench
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
    public function exp() : void
    {
        $this->a->exp();
    }
}
