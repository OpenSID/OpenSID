<?php

namespace STS\ZipStream\Models;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Str;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\OutputStream;

class LocalFile extends File
{
    public static function supports(string $source): bool
    {
        return Str::startsWith($source, '/')
            || preg_match('/^\w:[\/\\\\]/', $source)
            || (!str_contains($source, '://') && file_exists($source));
    }

    public static function supportsDisk(FilesystemAdapter $disk): bool
    {
        return $disk->getAdapter() instanceof LocalFilesystemAdapter;
    }

    public static function supportsWriting(): bool
    {
        return true;
    }

    public function calculateFilesize(): int
    {
        return filesize($this->getSource());
    }

    protected function buildReadableStream(): StreamInterface
    {
        return Utils::streamFor(fopen($this->getSource(), 'r'));
    }

    protected function buildWritableStream(): OutputStream
    {
        if(!is_dir(dirname($this->getSource()))) {
            mkdir(dirname($this->getSource()), 0777, true);
        }

        return new OutputStream(fopen($this->getSource(), 'w'));
    }

    public function canPredictZipDataSize(): bool
    {
        return true;
    }
}
