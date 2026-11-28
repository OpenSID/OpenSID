<?php

namespace Tensor\Benchmarks\Structural;

use Tensor\Matrix;

/**
 * @Groups({"Structural"})
 * @BeforeMethods({"setUp"})
 */
class RepeatMatrixBench
{
    /**
     * @var Matrix
     */
    protected $a;

    public function setUp() : void
    {
        $this->a = Matrix::uniform(100, 100);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function repeat() : void
    {
        $this->a->repeat(100, 100);
    }
}
