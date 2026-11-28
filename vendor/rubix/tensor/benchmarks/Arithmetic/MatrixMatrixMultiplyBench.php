<?php

namespace Tensor\Benchmarks\Arithmetic;

use Tensor\Matrix;

/**
 * @Groups({"Arithmetic"})
 * @BeforeMethods({"setUp"})
 */
class MatrixMatrixMultiplyBench
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
        $this->a = Matrix::uniform(1000, 1000);

        $this->b = Matrix::uniform(1000, 1000);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function multiply() : void
    {
        $this->a->multiply($this->b);
    }
}
