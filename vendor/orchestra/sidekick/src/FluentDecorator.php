<?php

namespace Orchestra\Sidekick;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Fluent;
use Illuminate\Support\Traits\ForwardsCalls;
use JsonSerializable;

/**
 * @api
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @implements \Illuminate\Contracts\Support\Arrayable<TKey, TValue>
 * @implements \ArrayAccess<TKey, TValue>
 */
abstract class FluentDecorator implements Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    use ForwardsCalls;

    /**
     * The Fluent instance.
     *
     * @var \Illuminate\Support\Fluent<TKey, TValue>
     */
    protected Fluent $fluent;

    /**
     * Create a new fluent instance.
     *
     * @param  iterable<TKey, TValue>  $attributes
     */
    public function __construct($attributes = [])
    {
        $this->fluent = new Fluent($attributes);
    }

    /**
     * Convert the fluent instance to an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray()
    {
        return $this->fluent->getAttributes();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array<TKey, TValue>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert the fluent instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return (string) json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  TKey  $offset
     */
    public function offsetExists($offset): bool
    {
        return $this->fluent->offsetExists($offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  TKey  $offset
     * @return TValue|null
     */
    public function offsetGet($offset): mixed
    {
        return $this->fluent->offsetGet($offset);
    }

    /**
     * Set the value at the given offset.
     *
     * @param  TKey  $offset
     * @param  TValue  $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->fluent->offsetSet($offset, $value);
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  TKey  $offset
     */
    public function offsetUnset($offset): void
    {
        $this->fluent->offsetUnset($offset);
    }

    /**
     * Handle dynamic calls to the fluent instance to set attributes.
     *
     * @param  TKey  $method
     * @param  array{0: ?TValue}  $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        return $this->forwardDecoratedCallTo($this->fluent, $method, $parameters);
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  TKey  $key
     * @return TValue|null
     */
    public function __get($key)
    {
        /** @phpstan-ignore function.alreadyNarrowedType */
        if (method_exists($this->fluent, 'value')) {
            return $this->fluent->value($key);
        }

        return $this->fluent->get($key);
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param  TKey  $key
     * @param  TValue  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->fluent->offsetSet($key, $value);
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  TKey  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->fluent->offsetExists($key);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param  TKey  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->fluent->offsetUnset($key);
    }
}
