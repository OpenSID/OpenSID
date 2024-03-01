<?php

namespace Fcm\Topic;

use Fcm\Request;

class Subscribe implements Request
{
    use Topic;

    /**
     * @var string
     */
    private $topicName;

    /**
     * @param string $topicName
     * @param string $deviceId
     */
    public function __construct(string $topicName, string $deviceId = '')
    {
        $this->topicName = $topicName;

        if (!empty($deviceId)) {
            $this->addDevice($deviceId);
        }
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return 'https://iid.googleapis.com/iid/v1:batchAdd';
    }

    /**
     * @inheritdoc
     */
    public function getBody(): array
    {
        return [
            'to' => "/topics/{$this->topicName}",
            'registration_tokens' => $this->devices,
        ];
    }
}
