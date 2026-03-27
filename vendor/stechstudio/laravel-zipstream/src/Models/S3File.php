<?php

namespace STS\ZipStream\Models;

use Aws\S3\S3Client;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\OutputStream;

class S3File extends File
{
    protected string $region;

    protected S3Client $client;

    public static function supports(string $source): bool
    {
        return Str::startsWith($source, 's3://');
    }

    public static function supportsDisk(FilesystemAdapter $disk): bool
    {
        return $disk instanceof AwsS3V3Adapter;
    }

    public static function supportsWriting(): bool
    {
        return true;
    }

    public static function fromDisk(FilesystemAdapter $disk, string $source, ?string $zipPath = null): static
    {
        return (new static(
            "s3://" . Arr::get($disk->getConfig(), "bucket") . "/" . $disk->path($source),
            $zipPath
        ))->setS3Client($disk->getClient());
    }

    public function calculateFilesize(): int
    {
        // Use a separate, immediately discarded, stream to get the file size
        // to avoid the main stream from being opened prematurely then timing
        // out before the file contents can be streamed.
        $stream = $this->buildReadableStream();
        $size = $stream->getSize();
        $stream->close();

        return $size;
    }

    public function setS3Client(S3Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getS3Client(): S3Client
    {
        if (!isset($this->client)) {
            $this->client = app('zipstream.s3client');
        }

        return $this->client;
    }

    protected function buildReadableStream(): StreamInterface
    {
        $this->getS3Client()->registerStreamWrapper();

        return Utils::streamFor(fopen($this->getSource(), 'r'));
    }

    protected function buildWritableStream(): OutputStream
    {
        $this->getS3Client()->registerStreamWrapper();

        return new OutputStream(fopen($this->getSource(), 'w'));
    }

    public function canPredictZipDataSize(): bool
    {
        return true;
    }
}
