<?php

namespace Yajra\DataTables\Html;

use Illuminate\Contracts\Auth\Access\Authorizable;

trait HasAuthorizations
{
    /**
     * Flag to check if user is authorized to use the button.
     */
    protected bool $authorized = true;

    /**
     * Make a button if condition is true.
     */
    public static function makeIf(callable|bool $condition, array|string $options = []): static
    {
        if (is_callable($condition)) {
            $condition = value($condition);
        }

        if ($condition === true) {
            return static::make($options);
        }

        return app(static::class)->authorized(false);
    }

    /**
     * Set authorization status of the button.
     */
    public function authorized(callable|bool $bool): static
    {
        $this->authorized = (bool) value($bool);

        return $this;
    }

    /**
     * Make a button if the user is authorized.
     */
    public static function makeIfCan(string $permission, array|string $options = [], ?Authorizable $user = null): static
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        if ($user instanceof Authorizable && $user->can($permission)) {
            return static::make($options);
        }

        return static::make([])->authorized(false);
    }

    /**
     * Make a button if the user is not authorized.
     */
    public static function makeIfCannot(
        string $permission,
        array|string $options = [],
        ?Authorizable $user = null
    ): static {
        if (is_null($user)) {
            $user = auth()->user();
        }

        if ($user instanceof Authorizable && ! $user->can($permission)) {
            return static::make($options);
        }

        return app(static::class)->authorized(false);
    }

    /**
     * Convert the Fluent instance to an array.
     */
    public function toArray(): array
    {
        if (! $this->isAuthorized()) {
            return [];
        }

        return parent::toArray();
    }

    /**
     * Check if instance is authorized
     */
    public function isAuthorized(): bool
    {
        return $this->authorized;
    }
}
