<?php declare(strict_types=1);

namespace Amp\Serialization;

final class PassthroughSerializer implements Serializer
{
    #[\Override]
    public function serialize($data): string
    {
        if (!\is_string($data)) {
            throw new SerializationException('Serializer implementation only allows strings');
        }

        return $data;
    }

    #[\Override]
    public function unserialize(string $data): string
    {
        return $data;
    }
}
