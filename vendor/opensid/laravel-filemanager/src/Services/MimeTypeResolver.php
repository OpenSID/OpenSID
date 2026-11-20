<?php

namespace OpenSID\LaravelFilemanager\Services;

use League\MimeTypeDetection\ExtensionMimeTypeDetector;
use League\MimeTypeDetection\FinfoMimeTypeDetector;

/**
 * Thin wrapper over league/mime-type-detection (ships with league/flysystem,
 * already a Laravel dependency) — replaces rfm's hand-rolled mime table
 * (include/mime_type_lib.php).
 */
class MimeTypeResolver
{
    protected FinfoMimeTypeDetector $contentDetector;

    protected ExtensionMimeTypeDetector $extensionDetector;

    public function __construct()
    {
        $this->contentDetector = new FinfoMimeTypeDetector;
        $this->extensionDetector = new ExtensionMimeTypeDetector;
    }

    public function detectFromPath(string $absolutePath): ?string
    {
        return $this->contentDetector->detectMimeTypeFromFile($absolutePath);
    }

    public function guessExtension(string $mimeType): ?string
    {
        return $this->extensionDetector->lookupExtension($mimeType);
    }
}
