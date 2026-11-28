<?php

namespace Tensor\Benchmarks\Arithmetic;

use Tensor\Matrix;
use Tensor\Vector;

/**
 * @Groups({"Arithmetic"})
 * @BeforeMethods({"setUp"})
 */
class MatrixVectorMultiplyBench
{
    /**
     * @var Matrix
     */
    protected $a;

    /**
     * @var Vector
     */
    protected $b;

    public function setUp() : void
    {
        $this->a = Matrix::uniform(1000, 1000);

        $this->b = Vector::uniform(1000);
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
