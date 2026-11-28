<?php declare(strict_types=1);

namespace Amp\Serialization;

interface Serializer
{
    /**
     * @param mixed $data Serializable PHP value.
     *
     * @throws SerializationException
     */
    public function serialize($data): string;

    /**
     * @return mixed The unserialized data.
     *
     * @throws SerializationException
     */
    public function unserialize(string $data);
}
