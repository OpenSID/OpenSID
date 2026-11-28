<?php

namespace Tensor\Benchmarks\LinearAlgebra;

use Tensor\Vector;

/**
 * @Groups({"LinearAlgebra"})
 * @BeforeMethods({"setUp"})
 */
class VectorDotProductBench
{
    /**
     * @var Vector
     */
    protected $a;

    /**
     * @var Vector
     */
    protected $b;

    public function setUp() : void
    {
        $this->a = Vector::uniform(10000000);

        $this->b = Vector::uniform(10000000);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function dot() : void
    {
        $this->a->dot($this->b);
    }
}
