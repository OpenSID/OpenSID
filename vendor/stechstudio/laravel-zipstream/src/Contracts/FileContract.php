<?php

namespace STS\ZipStream\Contracts;

use Psr\Http\Message\StreamInterface;
use ZipStream\Option\Archive as ArchiveOptions;
use ZipStream\Option\File as FileOptions;

interface FileContract
{
    public function getName(): string;

    public function getSource(): string;

    public function getZipPath(): string;

    public function getFilesize(): int;

    public function getFingerprint(): string;

    public function getReadableStream(): StreamInterface;

    public function getWritableStream(): StreamInterface;
}
