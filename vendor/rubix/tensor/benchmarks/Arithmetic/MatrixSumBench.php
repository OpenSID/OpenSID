<?php

namespace Tensor\Benchmarks\Arithmetic;

use Tensor\Matrix;

/**
 * @Groups({"Arithmetic"})
 * @BeforeMethods({"setUp"})
 */
class MatrixSumBench
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
    public function sum() : void
    {
        $this->a->sum();
    }
}
