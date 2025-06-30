<?php

namespace Fcm\Topic;

use Fcm\Exception\TopicException;

trait Topic
{
    /**
     * @var array
     */
    private $devices = [];

    /**
     * @param string|array $deviceId
     *
     * @return self
     */
    public function addDevice($deviceId): self
    {
        if (empty($deviceId)) {
            throw new TopicException('Device id is empty');
        }

        if (\is_string($deviceId)) {
            $this->devices[] = $deviceId;
        }

        if (\is_array($deviceId)) {
            $this->devices = array_merge($this->devices, $deviceId);
        }

        return $this;
    }
}
