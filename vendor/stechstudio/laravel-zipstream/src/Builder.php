<?php

namespace STS\ZipStream;

use Closure;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use STS\ZipStream\Contracts\FileContract;
use STS\ZipStream\Events\ZipStreamed;
use STS\ZipStream\Events\ZipStreaming;
use STS\ZipStream\Models\File;
use STS\ZipStream\Models\TempFile;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipStream\OperationMode;
use ZipStream\ZipStream;

class Builder implements Responsable
{
    protected string $outputName;

    protected string $comment = '';

    protected int $bytesSent = 0;

    protected Collection $meta;

    protected Queue $queue;

    protected OutputStream $outputStream;

    protected StreamInterface $cacheOutputStream;

    protected Closure $afterProcessing;

    public function __construct(array $files = [])
    {
        $this->queue = new Queue();

        foreach ($files as $key => $value) {
            if (is_string($key)) {
                $this->add($key, $value);
            } else {
                $this->add($value);
            }
        }
    }

    public function create(?string $name = null, array $files = []): self
    {
        return (new self($files))->setName($name);
    }

    public function setName(?string $name): self
    {
        $this->outputName = Str::finish($name, ".zip");

        return $this;
    }

    public function has($zipPath): bool
    {
        return $this->queue->has(trim($zipPath, '/'));
    }

    public function add($source, ?string $zipPath = null): self
    {
        if (!$source instanceof FileContract) {
            $source = File::make($source, $zipPath);
        }

        $this->queue->addItem($source);

        return $this;
    }

    public function addFromDisk($disk, $source, ?string $zipPath): self
    {
        return $this->add(File::makeFromDisk($disk, $source, $zipPath));
    }

    public function addRaw($content, string $zipPath): self
    {
        return $this->add(new TempFile($content, $zipPath));
    }

    public function setMeta(array $meta): self
    {
        $this->meta = collect($meta);

        return $this;
    }

    public function getMeta(): Collection
    {
        return $this->meta ?? collect();
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

    public function cache($output): self
    {
        $this->cacheOutputStream = match (true) {
            $output instanceof StreamInterface => $output,
            $output instanceof FileContract    => $output->getWritableStream(),
            is_string($output)                 => File::makeWriteable($output)->getWritableStream(),
            default                            => throw new InvalidArgumentException('Invalid cache output provided'),
        };

        return $this;
    }

    public function cacheToDisk($disk, $output): self
    {
        return $this->cache(File::makeWriteableFromDisk($disk, $output));
    }

    public function saveTo($output): int
    {
        $this->outputStream = match (true) {
            $output instanceof OutputStream    => $output,
            $output instanceof StreamInterface => new OutputStream($output),
            $output instanceof FileContract    => $output->getWritableStream(),
            is_string($output)                 => File::makeWriteable(Str::finish($output, "/").$this->getOutputName())->getWritableStream(),
            default                            => throw new InvalidArgumentException('Invalid output provided'),
        };

        return $this->process();
    }

    public function saveToDisk($disk, $path): int
    {
        return $this->saveTo(File::makeWriteableFromDisk($disk, Str::finish($path, "/").$this->getOutputName()));
    }

    public function process(): int
    {
        $zip = $this->prepare();

        if ($this->canPredictZipSize()) {
            $size = $zip->finish();
            header('Content-Length: '.$size);
            header('X-Accel-Buffering: no');

            event(new ZipStreaming($this, $zip, $size));

            $zip->executeSimulation();
        } else {
            event(new ZipStreaming($this, $zip));

            $size = $zip->finish();
        }

        if (isset($this->cacheOutputStream)) {
            $this->cacheOutputStream->close();
        }

        $this->bytesSent = $size;

        event(new ZipStreamed($this, $zip, $size));

        if (isset($this->afterProcessing)) {
            ($this->afterProcessing)($this, $zip, $size);
        }

        return $size;
    }

    public function then(Closure $callback): self
    {
        $this->afterProcessing = $callback;

        return $this;
    }

    public function canPredictZipSize(): bool
    {
        return config('zipstream.predict_size')
            && config('zipstream.compression_method') === 'store'
            && $this->queue->every->canPredictZipDataSize();
    }

    public function getFingerprint(): string
    {
        return md5(
            $this->queue->map->getFingerprint()->sort()->implode('')
            . $this->getOutputName()
            . $this->getComment()
            . serialize($this->getMeta()->sort()->toArray())
        );
    }

    public function getOutputName(): string
    {
        return $this->outputName ?? 'download.zip';
    }

    public function getFinalSize(): int
    {
        return $this->bytesSent;
    }

    public function response(): StreamedResponse
    {
        return new StreamedResponse(function () {
            $this->process();
        }, 200);
    }

    public function toResponse($request): StreamedResponse
    {
        return $this->response();
    }

    protected function prepare(): ZipStream
    {
        $zip = new ZipStream(
            operationMode: $this->canPredictZipSize() ? OperationMode::SIMULATE_STRICT : OperationMode::NORMAL,
            comment: $this->getComment(),
            outputStream: $this->getOutputStream(),
            outputName: $this->getOutputName(),
            flushOutput: true,
        );

        $this->queue->each->prepare($zip);

        return $zip;
    }

    protected function getOutputStream(): StreamInterface
    {
        if (!isset($this->outputStream)) {
            $this->outputStream = new OutputStream(fopen('php://output', 'wb'));
        }

        if (isset($this->cacheOutputStream)) {
            $this->outputStream->cacheTo($this->cacheOutputStream);
        }

        return $this->outputStream;
    }
}