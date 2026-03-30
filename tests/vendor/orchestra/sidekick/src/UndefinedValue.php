<?php

namespace Orchestra\Sidekick;

use JsonSerializable;

/**
 * @api
 */
final class UndefinedValue implements JsonSerializable
{
    /**
     * Determine if value is equivalent to "undefined" or "null".
     *
     * @param  mixed  $value
     */
    public static function equalsTo($value): bool
    {
        return $value instanceof UndefinedValue || \is_null($value);
    }

    /**
     * Get the value for JSON serialization.
     *
     * @return null
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return null;
    }
}
