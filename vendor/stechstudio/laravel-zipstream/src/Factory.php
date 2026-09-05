<?php

namespace STS\ZipStream;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use STS\ZipStream\Exceptions\UnsupportedSourceDiskException;
use STS\ZipStream\Models\File;
use STS\ZipStream\Models\HttpFile;
use STS\ZipStream\Models\LocalFile;
use STS\ZipStream\Models\S3File;
use STS\ZipStream\Models\TempFile;

class Factory
{
    protected array $types = [
        S3File::class,
        HttpFile::class,
        LocalFile::class,
    ];

    public function extend(string $fileClass): self
    {
        if (!is_subclass_of($fileClass, File::class)) {
            throw new InvalidArgumentException("[$fileClass] must extend " . File::class);
        }

        if (!in_array($fileClass, $this->types)) {
            array_unshift($this->types, $fileClass);
        }

        return $this;
    }

    public function make(string $source, ?string $zipPath = null): File
    {
        foreach ($this->types as $type) {
            if ($type::supports($source)) {
                return new $type($source, $zipPath);
            }
        }

        return new TempFile($source, $zipPath);
    }

    public function makeFromDisk($disk, string $source, ?string $zipPath = null): File
    {
        if (!$disk instanceof FilesystemAdapter) {
            $disk = Storage::disk($disk);
        }

        foreach ($this->types as $type) {
            if ($type::supportsDisk($disk)) {
                return $type::fromDisk($disk, $source, $zipPath);
            }
        }

        throw new UnsupportedSourceDiskException("Unsupported disk type");
    }

    public function makeWriteable(string $source): File
    {
        foreach ($this->types as $type) {
            if ($type::supportsWriting() && $type::supports($source)) {
                return new $type($source);
            }
        }

        return new LocalFile($source);
    }

    public function makeWriteableFromDisk($disk, string $source): File
    {
        if (!$disk instanceof FilesystemAdapter) {
            $disk = Storage::disk($disk);
        }

        foreach ($this->types as $type) {
            if ($type::supportsWriting() && $type::supportsDisk($disk)) {
                return $type::fromDisk($disk, $source);
            }
        }

        return new LocalFile($disk->path($source));
    }
}
