<?php

namespace Tensor\Benchmarks\Structural;

use Tensor\Matrix;

/**
 * @Groups({"Structural"})
 * @BeforeMethods({"setUp"})
 */
class AugmentMatrixBelowBench
{
    /**
     * @var Matrix
     */
    protected $a;

    /**
     * @var Matrix
     */
    protected $b;

    public function setUp() : void
    {
        $this->a = Matrix::uniform(500, 500);

        $this->b = Matrix::uniform(500, 500);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function augmentBelow() : void
    {
        $this->a->augmentBelow($this->b);
    }
}
