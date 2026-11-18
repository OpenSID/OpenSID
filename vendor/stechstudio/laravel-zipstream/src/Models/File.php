<?php

namespace STS\ZipStream\Models;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\Contracts\FileContract;
use STS\ZipStream\Factory;
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

    protected OutputStream $writeStream;

    public function __construct(string $source, ?string $zipPath = null, array $options = [])
    {
        $this->source = $source;
        $this->zipPath = $zipPath ?? $this->getDefaultZipPath();
        $this->options = $options;
    }

    public static function supports(string $source): bool
    {
        return false;
    }

    public static function supportsDisk(FilesystemAdapter $disk): bool
    {
        return false;
    }

    public static function supportsWriting(): bool
    {
        return false;
    }

    public static function fromDisk(FilesystemAdapter $disk, string $source, ?string $zipPath = null): static
    {
        return new static($disk->path($source), $zipPath);
    }

    public static function make(string $source, ?string $zipPath = null): File
    {
        return app(Factory::class)->make($source, $zipPath);
    }

    public static function makeFromDisk($disk, string $source, ?string $zipPath = null): File
    {
        return app(Factory::class)->makeFromDisk($disk, $source, $zipPath);
    }

    public static function makeWriteable(string $source): File
    {
        return app(Factory::class)->makeWriteable($source);
    }

    public static function makeWriteableFromDisk($disk, string $source): File
    {
        return app(Factory::class)->makeWriteableFromDisk($disk, $source);
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
        return $this->buildReadableStream();
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
