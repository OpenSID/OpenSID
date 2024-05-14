<?php

namespace Fcm\DeviceGroup;

use Fcm\Request;

class Remove implements Request
{
    use DeviceGroup;

    /**
     * @var string
     */
    private $notification_key_name;

    /**
     * @var string
     */
    private $notification_key;

    /**
     * @param string $notification_key_name
     * @param string $notification_key
     * @param string $deviceId
     */
    public function __construct(string $notification_key_name, string $notification_key, string $deviceId = '')
    {
        $this->notification_key_name = $notification_key_name;
        $this->notification_key = $notification_key;

        if (!empty($deviceId)) {
            $this->addDevice($deviceId);
        }
    }

    /**
     * @inheritdoc
     */
    public function getBody(): array
    {
        return [
            'operation' => 'remove',
            'notification_key_name' => $this->notification_key_name,
            'notification_key' => $this->notification_key,
            'registration_ids' => $this->deviceIds
        ];
    }
}
