<?php

namespace STS\ZipStream\Events;

use STS\ZipStream\Builder;
use ZipStream\ZipStream;

class ZipStreaming
{
    public function __construct(public Builder $builder, public ZipStream $zip, public $size = null)
    {

    }
}