<?php declare(strict_types=1);

namespace Amp\Serialization;

final class NativeSerializer implements Serializer
{
    /** @var list<class-string>|null */
    private ?array $allowedClasses;

    /**
     * @param list<class-string>|null $allowedClasses List of allowed class names to be unserialized.
     *  Null for any class.
     */
    public function __construct(?array $allowedClasses = null)
    {
        $this->allowedClasses = $allowedClasses;
    }

    #[\Override]
    public function serialize($data): string
    {
        try {
            return \serialize($data);
        } catch (\Throwable $exception) {
            throw new SerializationException(
                \sprintf('The given data could not be serialized: %s', $exception->getMessage()),
                0,
                $exception
            );
        }
    }

    #[\Override]
    public function unserialize(string $data)
    {
        try {
            $result = \unserialize($data, ['allowed_classes' => $this->allowedClasses ?? true]);

            if ($result === false && $data !== \serialize(false)) {
                throw new SerializationException(
                    'Invalid data provided to unserialize: ' . encodeUnprintableChars($data)
                );
            }
        } catch (\Throwable $exception) {
            throw new SerializationException('Exception thrown when unserializing data', 0, $exception);
        }

        return $result;
    }
}
