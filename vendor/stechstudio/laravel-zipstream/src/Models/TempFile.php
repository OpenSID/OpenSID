<?php

namespace STS\ZipStream\Models;

use GuzzleHttp\Psr7\Utils;
use STS\ZipStream\OutputStream;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\Exceptions\FilenameMissingException;
use STS\ZipStream\Exceptions\NotWritableException;

class TempFile extends File
{
    protected function getDefaultZipPath(): string
    {
        // For temp files (raw data provided) you MUST specify the zip path
        throw new FilenameMissingException();
    }

    public function calculateFilesize(): int
    {
        // Note: strlen returns actual bytes used, mb_strlen returns number of characters. We want strlen.
        return strlen($this->getSource());
    }

    protected function buildReadableStream(): StreamInterface
    {
        return Utils::streamFor($this->getSource());
    }

    protected function buildWritableStream(): OutputStream
    {
        throw new NotWritableException();
    }

    public function canPredictZipDataSize(): bool
    {
        return true;
    }
}
