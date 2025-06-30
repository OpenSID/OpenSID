<?php

namespace Fcm\DeviceGroup;

trait DeviceGroup
{
    /**
     * @var array
     */
    private $deviceIds = [];

    /**
     * @param string|array $deviceId
     *
     * @return self
     */
    public function addDevice($deviceId): self
    {
        if (\is_string($deviceId)) {
            $this->deviceIds[] = $deviceId;
        }

        if (\is_array($deviceId)) {
            $this->deviceIds = array_merge($this->deviceIds, $deviceId);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return 'https://android.googleapis.com/gcm/notification';
    }
}
