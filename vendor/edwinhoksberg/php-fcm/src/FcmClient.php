<?php

namespace Fcm;

use Fcm\Device\Info;
use Fcm\Exception\FcmClientException;
use GuzzleHttp\Client;
use ReflectionClass;

/**
 * @method Info deviceInfo(string $deviceId, bool $details = false)
 *
 * @method Topic\Subscribe topicSubscribe(string $topicName, string $deviceId = '')
 * @method Topic\Unsubscribe topicUnsubscribe(string $topicName, string $deviceId = '')
 *
 * @method DeviceGroup\Create deviceGroupCreate(string $groupName, string $deviceId = '')
 * @method DeviceGroup\Update deviceGroupUpdate(string $notification_key_name, string $notification_key, string $deviceId = '')
 * @method DeviceGroup\Remove deviceGroupRemove(string $notification_key_name, string $notification_key, string $deviceId = '')
 *
 * @method Push\Notification pushNotification(string $title = '', string $body = '', string $recipient = '')
 * @method Push\Data pushData(array $data = [], string $recipient = '')
 */
class FcmClient
{
    /**
     * @var string
     */
    private $apiToken;

    /**
     * @var string
     */
    private $senderId;

    /**
     * @var array
     */
    public $options;

    /**
     * @param string $apiToken
     * @param string $senderId
     * @param array $options
     */
    public function __construct(string $apiToken, string $senderId, array $options = Array())
    {
        $this->apiToken  = $apiToken;
        $this->senderId = $senderId;

        if ( isset( $options["http_errors"] ) ) {
            $options["http_errors"] = (bool)$options["http_errors"];
        } else {
            $options["http_errors"] = false;
        }
        $this->options = $options;
    }

    /**
     * @param string $apiToken
     * @param string $projectId
     * @param array $options
     *
     * @return FcmClient
     */
    public static function create(string $apiToken, string $projectId, array $options = Array()): FcmClient
    {
        return new self($apiToken, $projectId);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function send(Request $request): array
    {
        // Build guzzle api client.
        $client = $this->getGuzzleClient();

        // Generate request url.
        $url = $request->getUrl();

        // Create and send the request.
        $response = $client->post($url, [
            'json' => $request->getBody()
        ]);

        // Decode the response body from json to a plain php array.
        $body = json_decode($response->getBody()->getContents(), true);
        if ($body === null || json_last_error() !== JSON_ERROR_NONE) {
            throw new FcmClientException('Failed to json decode response body: '.json_last_error_msg());
        }

        return $body;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return object
     */
    public function __call(string $name, array $arguments)
    {
        $camelCaseSplit = preg_split('/(?=[A-Z])/', $name);

        // Get all but last item from the split class name array,
        // and concatinate it back together.
        $group = array_slice($camelCaseSplit, 0, -1);
        $group = ucwords(implode('', $group));

        // The classname is always the last item in the group name.
        $class = ucwords(end($camelCaseSplit));

        // Check if the class exists before instantiating it.
        $class = "\Fcm\\{$group}\\{$class}";
        if (!class_exists($class)) {
            throw new FcmClientException('Invalid magic method called: '.$class);
        }

        // Instantiate the class with the correct constructor arguments and return it.
        $class = new ReflectionClass($class);
        return $class->newInstanceArgs($arguments);
    }

    /**
     * @return Client
     */
    public function getGuzzleClient(): Client
    {
        // Create configuration array
        return new Client([
            'headers' => [
                'Authorization' => 'key='.$this->apiToken,
                'project_id' => $this->senderId,
            ],
            'http_errors' => $this->options["http_errors"],
        ]);
    }

    /**
     * @param array $options
     * 
     * @return bool
     */

    public function setOptions(array $options): bool
    {
        $this->options = $options;
        return true;
    }


}
