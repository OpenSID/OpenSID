<?php

namespace Amp\Parallel\Sync;

final class ExitSuccess implements ExitResult
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }
}
