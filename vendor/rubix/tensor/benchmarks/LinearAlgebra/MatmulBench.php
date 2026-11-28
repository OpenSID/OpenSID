<?php

namespace Tensor\Benchmarks\LinearAlgebra;

use Tensor\Matrix;

/**
 * @Groups({"LinearAlgebra"})
 * @BeforeMethods({"setUp"})
 */
class MatmulBench
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
    public function matmul() : void
    {
        $this->a->matmul($this->b);
    }
}
