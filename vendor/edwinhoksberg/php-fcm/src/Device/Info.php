<?php

namespace Fcm\Device;

use Fcm\Request;

class Info implements Request
{
    /**
     * @var string
     */
    private $deviceId;

    /**
     * @var bool
     */
    private $details;

    /**
     * @param string $deviceId
     * @param bool $details
     */
    public function __construct(string $deviceId, bool $details = false)
    {
        $this->deviceId = $deviceId;
        $this->details = $details;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        $url = "https://iid.googleapis.com/iid/info/{$this->deviceId}";

        if ($this->details) {
            $url .= '?details=true';
        }

        return $url;
    }

    /**
     * @inheritdoc
     */
    public function getBody(): array
    {
        return [];
    }
}
