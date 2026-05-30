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

namespace Ergebnis\Composer\Normalize\Command;

use Composer\Command;
use Composer\Composer;
use Composer\Console\Application;
use Composer\Factory;
use Composer\IO;
use Ergebnis\Composer\Normalize\Exception;
use Ergebnis\Composer\Normalize\Version;
use Ergebnis\Json\Json;
use Ergebnis\Json\Normalizer;
use Ergebnis\Json\Printer;
use Localheinz\Diff;
use Symfony\Component\Console;

/**
 * @internal
 */
final class NormalizeCommand extends Command\BaseCommand
{
    private Diff\Differ $differ;
    private Printer\PrinterInterface $printer;
    private Normalizer\Normalizer $normalizer;
    private Factory $factory;

    public function __construct(
        Factory $factory,
        Normalizer\Normalizer $normalizer,
        Printer\PrinterInterface $printer,
        Diff\Differ $differ
    ) {
        $this->factory = $factory;
        $this->normalizer = $normalizer;
        $this->printer = $printer;
        $this->differ = $differ;

        parent::__construct('normalize');
    }

    protected function configure(): void
    {
        $this->setDescription('Normalizes composer.json according to its JSON schema (https://getcomposer.org/schema.json).');
        $this->setDefinition([
            new Console\Input\InputArgument(
                'file',
                Console\Input\InputArgument::OPTIONAL,
                'Path to composer.json file',
            ),
            new Console\Input\InputOption(
                'diff',
                null,
                Console\Input\InputOption::VALUE_NONE,
                'Show the results of normalizing',
            ),
            new Console\Input\InputOption(
                'dry-run',
                null,
                Console\Input\InputOption::VALUE_NONE,
                'Show the results of normalizing, but do not modify any files',
            ),
            new Console\Input\InputOption(
                'indent-size',
                null,
                Console\Input\InputOption::VALUE_REQUIRED,
                'Indent size (an integer greater than 0); should be used with the --indent-style option',
            ),
            new Console\Input\InputOption(
                'indent-style',
                null,
                Console\Input\InputOption::VALUE_REQUIRED,
                \sprintf(
                    'Indent style (one of "%s"); should be used with the --indent-size option',
                    \implode('", "', \array_keys(Normalizer\Format\Indent::CHARACTERS)),
                ),
            ),
            new Console\Input\InputOption(
                'no-check-lock',
                null,
                Console\Input\InputOption::VALUE_NONE,
                'Do not check if lock file is up to date',
            ),
            new Console\Input\InputOption(
                'no-update-lock',
                null,
                Console\Input\InputOption::VALUE_NONE,
                'Do not update lock file if it exists',
            ),
        ]);
    }

    protected function execute(
        Console\Input\InputInterface $input,
        Console\Output\OutputInterface $output
    ): int {
        $io = $this->getIO();

        $io->write([
            \sprintf(
                'Running %s.',
                Version::long(),
            ),
            '',
        ]);

        $indent = null;

        try {
            $indentFromInput = self::indentFromInput($input);
        } catch (\RuntimeException $exception) {
            $io->writeError(\sprintf(
                '<error>%s</error>',
                $exception->getMessage(),
            ));

            return 1;
        }

        if (null !== $indentFromInput) {
            $indent = $indentFromInput;
        }

        $composerFile = $input->getArgument('file');

        if (!\is_string($composerFile)) {
            $composerFile = Factory::getComposerFile();
        }

        $composer = $this->factory->createComposer(
            $io,
            $composerFile,
        );

        if (!$composer instanceof Composer) {
            throw Exception\ShouldNotHappen::create();
        }

        try {
            $indentFromExtra = self::indentFromExtra($composer->getPackage()->getExtra());
        } catch (\RuntimeException $exception) {
            $io->writeError(\sprintf(
                '<error>%s</error>',
                $exception->getMessage(),
            ));

            return 1;
        }

        if (null !== $indentFromExtra) {
            $indent = $indentFromExtra;
        }

        if (
            null !== $indentFromInput
            && null !== $indentFromExtra
        ) {
            $io->write('<warning>Configuration provided via options and composer extra. Using configuration from composer extra.</warning>');
        }

        if (
            false === $input->getOption('dry-run')
            && !\is_writable($composerFile)
        ) {
            $io->writeError(\sprintf(
                '<error>%s is not writable.</error>',
                $composerFile,
            ));

            return 1;
        }

        $locker = $composer->getLocker();

        if (
            false === $input->getOption('no-check-lock')
            && $locker->isLocked()
            && !$locker->isFresh()
        ) {
            $io->writeError('<error>The lock file is not up to date with the latest changes in composer.json, it is recommended that you run `composer update --lock`.</error>');

            return 1;
        }

        /** @var string $encoded */
        $encoded = \file_get_contents($composerFile);

        $json = Json::fromString($encoded);

        $format = Normalizer\Format\Format::fromJson($json);

        if (null !== $indent) {
            $format = $format->withIndent($indent);
        }

        $normalizer = new Normalizer\ChainNormalizer(
            $this->normalizer,
            new class($this->printer, $format) implements Normalizer\Normalizer {
                private Normalizer\Format\Format $format;
                private Printer\PrinterInterface $printer;

                public function __construct(
                    Printer\PrinterInterface $printer,
                    Normalizer\Format\Format $format
                ) {
                    $this->printer = $printer;
                    $this->format = $format;
                }

                public function normalize(Json $json): Json
                {
                    $encoded = \json_encode(
                        $json->decoded(),
                        $this->format->jsonEncodeOptions()->toInt(),
                    );

                    $printed = $this->printer->print(
                        $encoded,
                        $this->format->indent()->toString(),
                        $this->format->newLine()->toString(),
                    );

                    if (!$this->format->hasFinalNewLine()) {
                        return Json::fromString($printed);
                    }

                    return Json::fromString($printed . $this->format->newLine()->toString());
                }
            },
        );

        try {
            $normalized = $normalizer->normalize($json);
        } catch (Normalizer\Exception\OriginalInvalidAccordingToSchema $exception) {
            $io->writeError('<error>Original composer.json does not match the expected JSON schema:</error>');

            self::showValidationErrors(
                $io,
                ...$exception->errors(),
            );

            return 1;
        } catch (Normalizer\Exception\NormalizedInvalidAccordingToSchema $exception) {
            $io->writeError('<error>Normalized composer.json does not match the expected JSON schema:</error>');

            self::showValidationErrors(
                $io,
                ...$exception->errors(),
            );

            return 1;
        } catch (\RuntimeException $exception) {
            $io->writeError(\sprintf(
                '<error>%s</error>',
                $exception->getMessage(),
            ));

            return 1;
        }

        if ($json->encoded() === $normalized->encoded()) {
            $io->write(\sprintf(
                '<info>%s is already normalized.</info>',
                $composerFile,
            ));

            return 0;
        }

        if (
            true === $input->getOption('diff')
            || true === $input->getOption('dry-run')
        ) {
            $io->writeError(\sprintf(
                '<error>%s is not normalized.</error>',
                $composerFile,
            ));

            $diff = $this->differ->diff(
                $json->encoded(),
                $normalized->encoded(),
            );

            $io->write([
                '',
                '<fg=yellow>---------- begin diff ----------</>',
                self::formatDiff($diff),
                '<fg=yellow>----------- end diff -----------</>',
                '',
            ]);
        }

        if (true === $input->getOption('dry-run')) {
            return 1;
        }

        \file_put_contents(
            $composerFile,
            $normalized->encoded(),
        );

        $io->write(\sprintf(
            '<info>Successfully normalized %s.</info>',
            $composerFile,
        ));

        if (
            true === $input->getOption('no-update-lock')
            || !$locker->isLocked()
        ) {
            return 0;
        }

        $io->write('<info>Updating lock file.</info>');

        $application = new Application();

        $application->setAutoExit(false);

        return self::updateLockerInWorkingDirectory(
            $application,
            $input,
            $output,
            \dirname($composerFile),
        );
    }

    /**
     * @throws \RuntimeException
     */
    private static function indentFromInput(Console\Input\InputInterface $input): ?Normalizer\Format\Indent
    {
        /** @var null|string $indentSize */
        $indentSize = $input->getOption('indent-size');

        /** @var null|string $indentStyle */
        $indentStyle = $input->getOption('indent-style');

        if (
            null === $indentSize
            && null === $indentStyle
        ) {
            return null;
        }

        if (null === $indentSize) {
            throw new \RuntimeException('When using the indent-style option, an indent size needs to be specified using the indent-size option.');
        }

        if (null === $indentStyle) {
            throw new \RuntimeException(\sprintf(
                'When using the indent-size option, an indent style (one of "%s") needs to be specified using the indent-style option.',
                \implode('", "', \array_keys(Normalizer\Format\Indent::CHARACTERS)),
            ));
        }

        if (
            (string) (int) $indentSize !== $indentSize
            || 1 > $indentSize
        ) {
            throw new \RuntimeException(\sprintf(
                'Indent size needs to be an integer greater than 0, but "%s" is not.',
                $indentSize,
            ));
        }

        if (!\array_key_exists($indentStyle, Normalizer\Format\Indent::CHARACTERS)) {
            throw new \RuntimeException(\sprintf(
                'Indent style needs to be one of "%s", but "%s" is not.',
                \implode('", "', \array_keys(Normalizer\Format\Indent::CHARACTERS)),
                $indentStyle,
            ));
        }

        return Normalizer\Format\Indent::fromSizeAndStyle(
            (int) $indentSize,
            $indentStyle,
        );
    }

    /**
     * @throws \RuntimeException
     */
    private static function indentFromExtra(array $extra): ?Normalizer\Format\Indent
    {
        if (!\array_key_exists('composer-normalize', $extra)) {
            return null;
        }

        $configuration = $extra['composer-normalize'];

        $requiredKeys = [
            'indent-size',
            'indent-style',
        ];

        if (!\is_array($configuration)) {
            throw new \RuntimeException(\sprintf(
                'Configuration in composer extra requires keys "%s" with corresponding values."',
                \implode('", "', $requiredKeys),
            ));
        }

        $missingKeys = \array_diff(
            $requiredKeys,
            \array_keys($configuration),
        );

        if ([] !== $missingKeys) {
            throw new \RuntimeException(\sprintf(
                'Configuration in composer extra requires keys "%s" with corresponding values."',
                \implode('", "', $requiredKeys),
            ));
        }

        $extraKeys = \array_diff(
            \array_keys($configuration),
            $requiredKeys,
        );

        if ([] !== $extraKeys) {
            throw new \RuntimeException(\sprintf(
                'Configuration in composer extra does not allow extra keys "%s"."',
                \implode('", "', $extraKeys),
            ));
        }

        $indentSize = $configuration['indent-size'];

        if (!\is_int($indentSize)) {
            throw new \RuntimeException(\sprintf(
                'Indent size needs to be an integer, got %s instead.',
                \gettype($indentSize),
            ));
        }

        if (1 > $indentSize) {
            throw new \RuntimeException(\sprintf(
                'Indent size needs to be an integer greater than 0, but %d is not.',
                $indentSize,
            ));
        }

        $indentStyle = $configuration['indent-style'];

        if (!\is_string($indentStyle)) {
            throw new \RuntimeException(\sprintf(
                'Indent style needs to be a string, got %s instead.',
                \gettype($indentStyle),
            ));
        }

        if (!\array_key_exists($indentStyle, Normalizer\Format\Indent::CHARACTERS)) {
            throw new \RuntimeException(\sprintf(
                'Indent style needs to be one of "%s", but "%s" is not.',
                \implode('", "', \array_keys(Normalizer\Format\Indent::CHARACTERS)),
                $indentStyle,
            ));
        }

        return Normalizer\Format\Indent::fromSizeAndStyle(
            $indentSize,
            $indentStyle,
        );
    }

    private static function showValidationErrors(
        IO\IOInterface $io,
        string ...$errors
    ): void {
        foreach ($errors as $error) {
            $io->writeError(\sprintf(
                '<error>- %s</error>',
                $error,
            ));
        }

        $io->writeError('<warning>See https://getcomposer.org/doc/04-schema.md for details on the schema</warning>');
    }

    private static function formatDiff(string $diff): string
    {
        $lines = \explode(
            "\n",
            $diff,
        );

        $formatted = \array_map(static function (string $line): string {
            $replaced = \preg_replace(
                [
                    '/^(\+.*)$/',
                    '/^(-.*)$/',
                ],
                [
                    '<fg=green>$1</>',
                    '<fg=red>$1</>',
                ],
                $line,
            );

            if (!\is_string($replaced)) {
                throw Exception\ShouldNotHappen::create();
            }

            return $replaced;
        }, $lines);

        return \implode(
            "\n",
            $formatted,
        );
    }

    /**
     * @see https://getcomposer.org/doc/03-cli.md#update
     *
     * @throws \Exception
     */
    private static function updateLockerInWorkingDirectory(
        Console\Application $application,
        Console\Input\InputInterface $input,
        Console\Output\OutputInterface $output,
        string $workingDirectory
    ): int {
        $parameters = [
            'command' => 'update',
            '--ignore-platform-reqs' => true,
            '--lock' => true,
            '--no-autoloader' => true,
            '--working-dir' => $workingDirectory,
        ];

        if ($input->hasParameterOption('--no-ansi')) {
            $parameters[] = '--no-ansi';
        }

        if ($input->hasParameterOption('--no-plugins')) {
            $parameters[] = '--no-plugins';
        }

        if ($input->hasParameterOption('--no-scripts')) {
            $parameters[] = '--no-scripts';
        }

        return $application->run(
            new Console\Input\ArrayInput($parameters),
            $output,
        );
    }
}
