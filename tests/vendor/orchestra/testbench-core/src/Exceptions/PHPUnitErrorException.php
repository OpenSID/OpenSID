<?php

namespace Orchestra\Testbench\Exceptions;

use function Orchestra\Sidekick\phpunit_version_compare;

if (phpunit_version_compare('10', '>=')) {
    class PHPUnitErrorException extends \PHPUnit\Framework\Exception
    {
        /** {@inheritDoc} */
        public function __construct(string $message, int $code, string $file, int $line, ?\Exception $previous = null)
        {
            parent::__construct($message, $code, $previous);

            $this->file = $file;
            $this->line = $line;
        }

        /**
         * Get serializable trace for PHPUnit.
         *
         * @return array
         *
         * @codeCoverageIgnore
         */
        public function getPHPUnitExceptionTrace(): array
        {
            return $this->serializableTrace;
        }
    }
} else {
    class PHPUnitErrorException extends \PHPUnit\Framework\Error\Error
    {
        /**
         * Get serializable trace for PHPUnit.
         *
         * @return array
         *
         * @codeCoverageIgnore
         */
        public function getPHPUnitExceptionTrace(): array
        {
            return $this->getTrace();
        }
    }
}
