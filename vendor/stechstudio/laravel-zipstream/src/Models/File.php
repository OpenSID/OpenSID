<?php

namespace STS\ZipStream\Models;

use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Http\Message\StreamInterface;
use Illuminate\Support\Str;
use STS\ZipStream\Contracts\FileContract;
use STS\ZipStream\Exceptions\UnsupportedSourceDiskException;
use STS\ZipStream\OutputStream;
use ZipStream\CompressionMethod;
use ZipStream\ZipStream;

abstract class File implements FileContract
{
    protected string $source;

    protected string $zipPath;

    protected string $comment = '';

    protected array $options = [];

    protected int $filesize;

    protected StreamInterface $readStream;

    protected OutputStream $writeStream;

    public function __construct(string $source, ?string $zipPath = null, array $options = [])
    {
        $this->source = $source;
        $this->zipPath = $zipPath ?? $this->getDefaultZipPath();
        $this->options = $options;
    }

    public static function make(string $source, ?string $zipPath = null): static
    {
        if (Str::startsWith($source, "s3://")) {
            return new S3File($source, $zipPath);
        }

        if (Str::startsWith($source, "http") && filter_var($source, FILTER_VALIDATE_URL)) {
            return new HttpFile($source, $zipPath);
        }

        if (Str::startsWith($source, "/") || preg_match('/^\w:[\/\\\\]/', $source) || file_exists($source)) {
            return new LocalFile($source, $zipPath);
        }

        return new TempFile($source, $zipPath);
    }

    /**
     * @throws UnsupportedSourceDiskException
     */
    public static function makeFromDisk($disk, string $source, ?string $zipPath = null): static
    {
        if(!$disk instanceof FilesystemAdapter) {
            $disk = Storage::disk($disk);
        }

        if($disk instanceof AwsS3V3Adapter) {
            return S3File::make(
                "s3://" . Arr::get($disk->getConfig(), "bucket") . "/" . $disk->path($source),
                $zipPath
            )->setS3Client($disk->getClient());
        }

        if($disk->getAdapter() instanceof LocalFilesystemAdapter) {
            return new LocalFile(
                $disk->path($source),
                $zipPath
            );
        }

        throw new UnsupportedSourceDiskException("Unsupported disk type");
    }

    public static function makeWriteable(string $source): S3File|LocalFile
    {
        if (Str::startsWith($source, "s3://")) {
            return new S3File($source);
        }

        return new LocalFile($source);
    }

    public static function makeWriteableFromDisk($disk, string $source): S3File|LocalFile
    {
        if(!$disk instanceof FilesystemAdapter) {
            $disk = Storage::disk($disk);
        }

        if($disk instanceof AwsS3V3Adapter) {
            return S3File::make(
                "s3://" . Arr::get($disk->getConfig(), "bucket") . "/" . $disk->path($source)
            )->setS3Client($disk->getClient());
        }

        return new LocalFile(
            $disk->path($source)
        );
    }

    public function getName(): string
    {
        return basename($this->getZipPath());
    }

    public function getSource(): string
    {
        return $this->source;
    }

    protected function getDefaultZipPath(): string
    {
        return basename($this->getSource());
    }

    public function getZipPath(): string
    {
        $path = ltrim(preg_replace('|/{2,}|', '/', $this->zipPath), '/');

        return config('zipstream.ascii_filenames')
            ? Str::ascii($path)
            : $path;
    }

    public function setZipPath(string $zipPath): self
    {
        $this->zipPath = $zipPath;

        return $this;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getReadableStream(): StreamInterface
    {
        if (!isset($this->readStream)) {
            $this->readStream = $this->buildReadableStream();
        }

        return $this->readStream;
    }

    public function getWritableStream(): OutputStream
    {
        if (!isset($this->writeStream)) {
            $this->writeStream = $this->buildWritableStream();
        }

        return $this->writeStream;
    }

    abstract protected function buildReadableStream(): StreamInterface;

    abstract protected function buildWritableStream(): OutputStream;

    public function getFilesize(): int
    {
        if (!isset($this->filesize)) {
            $this->filesize = $this->calculateFilesize();
        }

        return $this->filesize;
    }

    public function setFilesize(int $filesize): self
    {
        $this->filesize = $filesize;

        return $this;
    }

    abstract public function canPredictZipDataSize(): bool;

    abstract protected function calculateFilesize(): int;

    public function getFingerprint(): string
    {
        return md5($this->getSource().$this->getZipPath().$this->getFilesize().$this->getComment());
    }

    public function setOption($name, $value): static
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function getOption($name, $default = null)
    {
        return Arr::get($this->options, $name, $default);
    }

    public function compressionMethod()
    {
        $default = config('zipstream.compression_method') === 'deflate'
            ? CompressionMethod::DEFLATE
            : CompressionMethod::STORE;

        return $this->getOption('compressionMethod', $default);
    }

    public function prepare(ZipStream $zip): void
    {
        $zip->addFileFromCallback(
            fileName: $this->getZipPath(),
            callback: fn () => $this->getReadableStream(),
            comment: $this->getComment(),
            compressionMethod: $this->compressionMethod(),
            exactSize: $this->getFilesize()
        );
    }
}
