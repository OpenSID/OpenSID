<?php

namespace Orchestra\Testbench\Foundation\Console;

use Illuminate\Foundation\Console\VendorPublishCommand as Command;

use function Orchestra\Testbench\transform_realpath_to_relative;

/**
 * @codeCoverageIgnore
 */
class VendorPublishCommand extends Command
{
    /** {@inheritDoc} */
    #[\Override]
    protected function status($from, $to, $type)
    {
        $format = function ($path) use ($type) {
            if ($type === 'directory' && is_link($path)) {
                return $path;
            }

            $realpath = realpath($path);

            if ($realpath !== false) {
                $path = $realpath;
            }

            return match (true) {
                $this->files->exists($path) => $path,
                default => (string) realpath($path),
            };
        };

        $this->components->task(\sprintf(
            'Copying %s [%s] to [%s]',
            $type,
            transform_realpath_to_relative($format($from)),
            transform_realpath_to_relative($format($to)),
        ));
    }
}
