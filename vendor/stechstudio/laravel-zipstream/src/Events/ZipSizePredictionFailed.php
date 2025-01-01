<?php

namespace STS\ZipStream\Events;

use STS\ZipStream\ZipStreamOld;

class ZipSizePredictionFailed
{
    /** @var ZipStreamOld */
    public $zip;

    /** @var int */
    public $expected;

    /** @var int */
    public $actual;

    /**
     * @param ZipStreamOld $zip
     * @param int $expected
     * @param int $actual
     */
    public function __construct(ZipStreamOld $zip, int $expected, int $actual)
    {
        $this->zip = $zip;
        $this->expected = $expected;
        $this->actual = $actual;
    }
}