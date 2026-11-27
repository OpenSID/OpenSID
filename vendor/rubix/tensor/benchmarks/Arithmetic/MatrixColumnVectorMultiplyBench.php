<?php

namespace Tensor\Benchmarks\Arithmetic;

use Tensor\Matrix;
use Tensor\ColumnVector;

/**
 * @Groups({"Arithmetic"})
 * @BeforeMethods({"setUp"})
 */
class MatrixColumnVectorMultiplyBench
{
    /**
     * @var Matrix
     */
    protected $a;

    /**
     * @var \Tensor\Vector
     */
    protected $b;

    public function setUp() : void
    {
        $this->a = Matrix::uniform(1000, 1000);

        $this->b = ColumnVector::uniform(1000);
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
