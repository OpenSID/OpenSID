<?php

namespace STS\ZipStream\Models;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\OutputStream;

class LocalFile extends File
{
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
