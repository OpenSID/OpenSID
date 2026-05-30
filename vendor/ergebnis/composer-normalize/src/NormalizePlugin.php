<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/composer-normalize
 */

namespace Ergebnis\Composer\Normalize;

use Composer\Composer;
use Composer\Factory;
use Composer\IO;
use Composer\Plugin;
use Ergebnis\Json\Normalizer;
use Ergebnis\Json\Printer;
use Localheinz\Diff;

final class NormalizePlugin implements
    Plugin\Capability\CommandProvider,
    Plugin\Capable,
    Plugin\PluginInterface
{
    public function activate(
        Composer $composer,
        IO\IOInterface $io
    ): void {
    }

    public function deactivate(
        Composer $composer,
        IO\IOInterface $io
    ): void {
    }

    public function uninstall(
        Composer $composer,
        IO\IOInterface $io
    ): void {
    }

    public function getCapabilities(): array
    {
        return [
            Plugin\Capability\CommandProvider::class => self::class,
        ];
    }

    public function getCommands(): array
    {
        return [
            new Command\NormalizeCommand(
                new Factory(),
                new Normalizer\Vendor\Composer\ComposerJsonNormalizer(\sprintf(
                    'file://%s',
                    \realpath(__DIR__ . '/../resource/schema.json'),
                )),
                new Printer\Printer(),
                new Diff\Differ(new Diff\Output\StrictUnifiedDiffOutputBuilder([
                    'fromFile' => 'original',
                    'toFile' => 'normalized',
                ])),
            ),
        ];
    }
}
