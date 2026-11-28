<?php

namespace Tensor\Benchmarks\Functions;

use Tensor\Vector;

/**
 * @Groups({"Functions"})
 * @BeforeMethods({"setUp"})
 */
class SumVectorBench
{
    /**
     * @var Vector
     */
    protected $a;

    public function setUp() : void
    {
        $this->a = Vector::uniform(100000);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function sum() : void
    {
        $this->a->sum();
    }
}
