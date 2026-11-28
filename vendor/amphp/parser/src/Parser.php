<?php declare(strict_types=1);

namespace Amp\Parser;

class Parser
{
    /** @var \Generator<array-key, int|string|null, string, void>|null */
    private ?\Generator $generator;

    /** @var list<string> */
    private array $buffers = [];

    private int $bufferLength = 0;

    /** @var int|string|null */
    private $delimiter;

    /**
     * @param \Generator<array-key, int|string|null, string, void> $generator
     *
     * @throws InvalidDelimiterError If the generator yields an invalid delimiter.
     * @throws \Throwable If the generator throws.
     */
    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
        $this->delimiter = $this->filterDelimiter($this->generator->current());

        if (!$this->generator->valid()) {
            $this->generator = null;
        }
    }

    /**
     * Cancels the generator parser and returns any remaining data in the internal buffer. Writing data after calling
     * this method will result in an error.
     */
    final public function cancel(): string
    {
        $buffer = \implode($this->buffers);

        $this->buffers = [];
        $this->generator = null;

        return $buffer;
    }

    /**
     * @return bool True if the parser can still receive more data to parse, false if it has ended and calling push
     *     will throw an exception.
     */
    final public function isValid(): bool
    {
        return $this->generator !== null;
    }

    /**
     * Adds data to the internal buffer and tries to continue parsing.
     *
     * @param string $data Data to append to the internal buffer.
     *
     * @throws InvalidDelimiterError If the generator yields an invalid delimiter.
     * @throws \Error If parsing has already been cancelled.
     * @throws \Throwable If the generator throws.
     */
    final public function push(string $data): void
    {
        if ($this->generator === null) {
            throw new \Error("The parser is no longer writable");
        }

        $length = \strlen($data);
        if ($length === 0) {
            return;
        }

        $this->bufferLength += $length;

        try {
            do {
                if (\is_int($this->delimiter) && $this->bufferLength < $this->delimiter) {
                    return;
                }

                if (!empty($this->buffers)) {
                    $this->buffers[] = $data;
                    $data = \implode($this->buffers);
                    $this->buffers = [];
                }

                if (\is_int($this->delimiter)) {
                    $cutAt = $retainFrom = $this->delimiter;
                } elseif (\is_string($this->delimiter)) {
                    if (($cutAt = \strpos($data, $this->delimiter)) === false) {
                        return;
                    }

                    $retainFrom = $cutAt + \strlen($this->delimiter);
                } else {
                    $cutAt = $retainFrom = $this->bufferLength;
                }

                if ($this->bufferLength > $cutAt) {
                    $send = \substr($data, 0, $cutAt);
                    $data = \substr($data, $retainFrom);
                } else {
                    $send = $data;
                    $data = '';
                }

                $this->bufferLength -= $retainFrom;

                $this->delimiter = $this->filterDelimiter($this->generator->send($send));

                if (!$this->generator->valid()) {
                    $this->generator = null;
                    return;
                }
            } while ($this->bufferLength);
        } catch (\Throwable $exception) {
            $this->generator = null;
            throw $exception;
        } finally {
            if (\strlen($data)) {
                $this->buffers[] = $data;
            }
        }
    }

    /**
     * @param mixed $delimiter Value yielded from Generator.
     * @return int|string|null
     */
    private function filterDelimiter($delimiter)
    {
        \assert($this->generator instanceof \Generator, "Invalid parser state");

        if ($delimiter !== null
            && (!\is_int($delimiter) || $delimiter <= 0)
            && (!\is_string($delimiter) || !\strlen($delimiter))
        ) {
            throw new InvalidDelimiterError(
                $this->generator,
                \sprintf(
                    "Invalid value yielded: Expected NULL, an int greater than 0, or a non-empty string; %s given",
                    \is_object($delimiter) ? \sprintf("instance of %s", \get_class($delimiter)) : \gettype($delimiter),
                )
            );
        }

        return $delimiter;
    }
}
