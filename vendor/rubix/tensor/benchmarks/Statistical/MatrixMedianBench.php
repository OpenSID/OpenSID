<?php

namespace Tensor\Benchmarks\Statistical;

use Tensor\Matrix;

/**
 * @Groups({"Statistical"})
 * @BeforeMethods({"setUp"})
 */
class MatrixMedianBench
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
    public function median() : void
    {
        $this->a->median();
    }
}
