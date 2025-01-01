<?php

namespace STS\ZipStream\Models;

use Aws;
use Aws\S3\S3Client;
use Aws\S3\S3UriParser;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\OutputStream;

class S3File extends File
{
    protected string $region;

    protected S3Client $client;

    public function calculateFilesize(): int
    {
        return $this->getS3Client()->headObject([
            'Bucket' => $this->getBucket(),
            'Key'    => $this->getKey()
        ])->get('ContentLength');
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

    public function getBucket(): string
    {
        return parse_url($this->getSource(), PHP_URL_HOST);
    }

    public function getKey(): string
    {
        return ltrim(parse_url($this->getSource(), PHP_URL_PATH), "/");
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
