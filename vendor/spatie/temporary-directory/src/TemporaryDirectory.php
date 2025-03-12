<?php

namespace Spatie\TemporaryDirectory;

use FilesystemIterator;
use Spatie\TemporaryDirectory\Exceptions\InvalidDirectoryName;
use Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists;
use Throwable;

class TemporaryDirectory
{
    protected string $location;

    protected string $name = '';

    protected bool $forceCreate = false;

    protected bool $deleteWhenDestroyed = false;

    public function __construct(string $location = '')
    {
        $this->location = $this->sanitizePath($location);
    }

    public static function make(string $location = ''): self
    {
        return (new self($location))->create();
    }

    public function create(): self
    {
        if (empty($this->location)) {
            $this->location = $this->getSystemTemporaryDirectory();
        }

        if (empty($this->name)) {
            $this->name = mt_rand().'-'.str_replace([' ', '.'], '', microtime());
        }

        if ($this->forceCreate && file_exists($this->getFullPath())) {
            $this->deleteDirectory($this->getFullPath());
        }

        if ($this->exists()) {
            throw PathAlreadyExists::create($this->getFullPath());
        }

        mkdir($this->getFullPath(), 0777, true);

        return $this;
    }

    public function force(): self
    {
        $this->forceCreate = true;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $this->sanitizeName($name);

        return $this;
    }

    public function location(string $location): self
    {
        $this->location = $this->sanitizePath($location);

        return $this;
    }

    public function path(string $pathOrFilename = ''): string
    {
        if (empty($pathOrFilename)) {
            return $this->getFullPath();
        }

        $path = $this->getFullPath().DIRECTORY_SEPARATOR.trim($pathOrFilename, '/');

        $directoryPath = $this->removeFilenameFromPath($path);

        if (! file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        return $path;
    }

    public function empty(): self
    {
        $this->deleteDirectory($this->getFullPath());

        mkdir($this->getFullPath(), 0777, true);

        return $this;
    }

    public function delete(): bool
    {
        return $this->deleteDirectory($this->getFullPath());
    }

    public function exists(): bool
    {
        return file_exists($this->getFullPath());
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function getFullPath(): string
    {
        return $this->location.(! empty($this->name) ? DIRECTORY_SEPARATOR.$this->name : '');
    }

    protected function isValidDirectoryName(string $directoryName): bool
    {
        return strpbrk($directoryName, '\\/?%*:|"<>') === false;
    }

    protected function getSystemTemporaryDirectory(): string
    {
        return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
    }

    protected function sanitizePath(string $path): string
    {
        $path = rtrim($path);

        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    protected function sanitizeName(string $name): string
    {
        if (! $this->isValidDirectoryName($name)) {
            throw InvalidDirectoryName::create($name);
        }

        return trim($name);
    }

    protected function removeFilenameFromPath(string $path): string
    {
        if (! $this->isFilePath($path)) {
            return $path;
        }

        return substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR));
    }

    protected function isFilePath(string $path): bool
    {
        return str_contains($path, '.');
    }

    protected function deleteDirectory(string $path): bool
    {
        try {
            if (is_link($path)) {
                return unlink($path);
            }

            if (! file_exists($path)) {
                return true;
            }

            if (! is_dir($path)) {
                return unlink($path);
            }

            foreach (new FilesystemIterator($path) as $item) {
                if (! $this->deleteDirectory((string) $item)) {
                    return false;
                }
            }

            /*
             * By forcing a php garbage collection cycle using gc_collect_cycles() we can ensure
             * that the rmdir does not fail due to files still being reserved in memory.
             */
            gc_collect_cycles();

            return rmdir($path);
        } catch (Throwable) {
            return false;
        }
    }

    public function deleteWhenDestroyed(bool $deleteWhenDestroyed = true): self
    {
        $this->deleteWhenDestroyed = $deleteWhenDestroyed;

        return $this;
    }

    public function __destruct()
    {
        if ($this->deleteWhenDestroyed) {
            $this->delete();
        }
    }
}
