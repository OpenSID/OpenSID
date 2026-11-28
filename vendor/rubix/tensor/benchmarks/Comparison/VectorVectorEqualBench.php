<?php

namespace Tensor\Benchmarks\Comparison;

use Tensor\Vector;

/**
 * @Groups({"Arithmetic"})
 * @BeforeMethods({"setUp"})
 */
class VectorVectorEqualBench
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
        $this->a = Vector::uniform(10000);

        $this->b = Vector::uniform(10000);
    }

    /**
     * @Subject
     * @Iterations(5)
     * @OutputTimeUnit("milliseconds", precision=3)
     */
    public function equal() : void
    {
        $this->a->equal($this->b);
    }
}
