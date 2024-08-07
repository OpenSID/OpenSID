<?php

namespace STS\ZipStream\Events;

use STS\ZipStream\Builder;
use ZipStream\ZipStream;

class ZipStreamed
{
    public function __construct(public Builder $builder, public ZipStream $zip, public int $size)
    {

    }
}