<?php

namespace STS\ZipStream;

use GuzzleHttp\Psr7\StreamDecoratorTrait;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

class OutputStream implements StreamInterface
{
    use StreamDecoratorTrait;

    protected StreamInterface $stream;

    protected ?StreamInterface $cached = null;

    public function __construct($stream)
    {
        $this->stream = Utils::streamFor($stream);
    }

    public function cacheTo(StreamInterface $stream): self
    {
        $this->cached = $stream;

        return $this;
    }

    public function write($string): int
    {
        $result = $this->stream->write($string);

        $this->cached()?->write($string);

        return $result;
    }

    public function close(): void
    {
        $this->stream->close();

        $this->cached()?->close();
    }

    public function cached(): ?StreamInterface
    {
        return $this->cached;
    }
}