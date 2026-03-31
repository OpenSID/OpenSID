<?php

namespace Orchestra\Sidekick\Eloquent\Concerns;

use function Orchestra\Sidekick\laravel_version_compare;

/**
 * Polyfill for Eloquent Model to get previous attributes.
 *
 * @see https://github.com/laravel/framework/pull/55729
 *
 * @codeCoverageIgnore
 */
if (laravel_version_compare('12.15.0', '>=')) {
    trait HasPreviousAttributes
    {
        // ...
    }
} else {
    trait HasPreviousAttributes
    {
        /**
         * The previous state of the changed model attributes.
         *
         * @var array<string, mixed>
         */
        protected $previous = [];

        /** {@inheritDoc} */
        #[\Override]
        public function syncChanges()
        {
            parent::syncChanges();

            $this->previous = array_intersect_key($this->getRawOriginal(), $this->changes);

            return $this;
        }

        /** {@inheritDoc} */
        #[\Override]
        public function discardChanges()
        {
            $this->previous = [];

            return parent::discardChanges();
        }

        /**
         * Get the attributes that were previously original before the model was last saved.
         *
         * @return array<string, mixed>
         */
        public function getPrevious()
        {
            return $this->previous;
        }
    }
}
