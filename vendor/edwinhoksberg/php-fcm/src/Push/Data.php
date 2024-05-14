<?php

namespace Fcm\Push;

use Fcm\Exception\NotificationException;
use Fcm\Request;

class Data implements Request
{
    use Push;

    /**
     * @param array $data
     * @param string $recipient
     */
    public function __construct(array $data = [], string $recipient = '')
    {
        if (!empty($data)) {
            $this->data = $data;
        }

        if (!empty($recipient)) {
            $this->addRecipient($recipient);
        }
    }

    /**
     * @inheritdoc
     */
    public function getBody(): array
    {
        if (empty($this->data)) {
            throw new NotificationException('Data should not be empty for a Data Notification.');
        }

        if (empty($this->recipients) && empty($this->topics)) {
            throw new NotificationException('Must minimaly specify a single recipient or topic.');
        }

        if (!empty($this->recipients) && !empty($this->topics)) {
            throw new NotificationException('Must either specify a recipient or topic, not more then one.');
        }

        $request = [];

        if (!empty($this->recipients)) {
            if (\count($this->recipients) === 1) {
                $request['to'] = current($this->recipients);
            } else {
                $request['registration_ids'] = $this->recipients;
            }
        }

        if (!empty($this->topics)) {
            $request['condition'] = array_reduce($this->topics, function ($carry, string $topic) {
                $topicSyntax = "'%s' in topics";

                if (end($this->topics) === $topic) {
                    return $carry .= sprintf($topicSyntax, $topic);
                }

                return $carry .= sprintf($topicSyntax, $topic) . '||';
            });
        }

        if (!empty($this->data)) {
            $request['data'] = $this->data;
        }

        return $request;
    }
}
