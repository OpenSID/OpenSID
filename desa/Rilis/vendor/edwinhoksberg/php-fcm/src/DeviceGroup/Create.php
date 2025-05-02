<?php

namespace Fcm\DeviceGroup;

use Fcm\Request;

class Create implements Request
{
    use DeviceGroup;

    /**
     * @var string
     */
    private $groupName;

    /**
     * @param string $groupName
     * @param string $deviceId
     */
    public function __construct(string $groupName, string $deviceId = '')
    {
        $this->groupName = $groupName;

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
            'operation' => 'create',
            'notification_key_name' => $this->groupName,
            'registration_ids' => $this->deviceIds
        ];
    }
}
