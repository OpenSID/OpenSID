<?php

namespace Spatie\WebhookClient\Exceptions;

use Exception;

class InvalidMethod extends Exception
{
    public static function make($method): self
    {
        return new static("The method $method is not allowed.");
    }
}
