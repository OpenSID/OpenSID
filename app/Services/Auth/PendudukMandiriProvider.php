<?php

namespace App\Services\Auth;

use Closure;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class PendudukMandiriProvider extends EloquentUserProvider
{
    public function __construct(
        HasherContract $hasher,
        $model,
        protected $belongsTo
    )
    {
        parent::__construct($hasher, $model);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereHas($this->belongsTo, function ($query) use ($key, $value) {
                    $query->whereIn($key, $value);
                });
            } elseif ($value instanceof Closure) {
                $value($query);
            } else {
                $query->whereRelation($this->belongsTo, $key, $value);
            }
        }

        return $query->first();
    }
}
