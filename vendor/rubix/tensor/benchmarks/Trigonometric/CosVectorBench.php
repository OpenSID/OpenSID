<?php

namespace Tensor\Benchmarks\Trigonometric;

use Tensor\Vector;

/**
 * @Groups({"Trigonometric"})
 * @BeforeMethods({"setUp"})
 */
class CosVectorBench
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
    public function cosine() : void
    {
        $this->a->cos();
    }
}
