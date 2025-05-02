<?php

namespace STS\ZipStream\Models;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\Exceptions\NotWritableException;
use STS\ZipStream\OutputStream;

class HttpFile extends File
{
    private const HEADER_CONTENT_LENGTH = 'content-length';

    private array $headers;

    public function calculateFilesize(): int
    {
        $headers = $this->getHeaders();

        if (!array_key_exists(self::HEADER_CONTENT_LENGTH, $headers)) {
            return false;
        }

        if(is_array($headers[self::HEADER_CONTENT_LENGTH])){
            return end($headers[self::HEADER_CONTENT_LENGTH]);
        }

        return $headers[self::HEADER_CONTENT_LENGTH];
    }

    protected function buildReadableStream(): StreamInterface
    {
        return Utils::streamFor(fopen($this->getSource(), 'r'));
    }

    protected function buildWritableStream(): OutputStream
    {
        throw new NotWritableException();
    }

    public function canPredictZipDataSize(): bool
    {
        return (isset($this->filesize) || array_key_exists(self::HEADER_CONTENT_LENGTH, $this->getHeaders()));
    }

    protected function getHeaders(): array
    {
        if (!isset($this->headers)) {
            $this->headers = array_change_key_case(get_headers($this->getSource(), 1));
        }

        return $this->headers;
    }
}
