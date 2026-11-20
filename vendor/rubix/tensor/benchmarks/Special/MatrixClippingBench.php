<?php

namespace Tensor\Benchmarks\Special;

use Tensor\Matrix;

/**
 * @Groups({"Special"})
 * @BeforeMethods({"setUp"})
 */
class MatrixClippingBench
{
    /**
     * @var Matrix
     */
    protected $a;

    /**
     * @var Matrix
     */
    protected $kernel;

    public function setUp() : void
    {
        $this->a = Matrix::uniform(1000, 1000);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function clip() : void
    {
        $this->a->clip(0, 1);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function clipUpper() : void
    {
        $this->a->clipUpper(0);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function clipLower() : void
    {
        $this->a->clipLower(0);
    }
}
