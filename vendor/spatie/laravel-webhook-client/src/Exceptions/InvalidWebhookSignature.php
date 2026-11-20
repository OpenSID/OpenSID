<?php

namespace Spatie\WebhookClient\Exceptions;

use Exception;

class InvalidWebhookSignature extends Exception
{
    public static function make(): self
    {
        return new static('The signature is invalid.');
    }
}
