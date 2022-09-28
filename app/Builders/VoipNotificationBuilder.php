<?php


namespace App\Builders;


/**
 * Class VoipNotificationBuilder
 * @package App\Builders
 */
class VoipNotificationBuilder implements IIosNotificationBuilder
{

    const APNS_PUSH_TYPE = 'voip';
    const APNS_TOPIC = 'com.visicommedia.manycam.voip';
    const HIGH_PRIORITY = 10;
    const EXPIRATION = 3;
    const SOUND = 'default';

    /**
     * @var array
     */
    public $body;
    /**
     * @var string
     */
    public $deviceToken;

    /**
     * @var bool
     */
    public $sandbox;

    /**
     * VoipNotificationBuilder constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->setSandbox($data);
        $this->setDeviceToken($data['device_token']);
        $this->setBody($data);
    }

    /**
     * @return mixed
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * @param mixed $deviceToken
     */
    public function setDeviceToken($deviceToken)
    {
        $this->deviceToken = $deviceToken;
    }

    /**
     * @return mixed|void
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $data
     */
    public function setBody($data)
    {
        $this->body['aps'] = [
            'content-available' => $data['content-available'] ?? 0,
            'alert'             => $data['text'] ?? null,
            'sound'             => self::SOUND,
            'category'          => $data['type'] ?? null,
        ];

        $this->body['command'] = $data['command'] ?? null;
        $this->body['user_id'] = $data['user_id'] ?? null;
        $this->body['channel'] = $data['channel'] ?? null;
        $this->body['contact_name'] = $data['contact_name'] ?? null;
        $this->body['username'] = $data['username'] ?? null;

        unset($data['device_token']);
        unset($data['type']);
        unset($data['text']);
        unset($data['content-available']);
        unset($data['sound']);

        $this->body = array_merge($this->body, $data);
    }

    /**
     * @return mixed|string
     */
    public function getPushType()
    {
        return self::APNS_PUSH_TYPE;
    }

    /**
     * @return mixed|string
     */
    public function getTopic()
    {
        return self::APNS_TOPIC;
    }

    /**
     * @return int|mixed
     */
    public function getPriority()
    {
        return self::HIGH_PRIORITY;
    }

    /**
     * @return int|mixed
     */
    public function getExpiration()
    {
        return self::EXPIRATION;
    }

    /**
     * @param $data
     */
    public function setSandbox($data)
    {
        if (!empty($data['sandbox'])) {
            $this->sandbox = $data['sandbox'];
        } else {
            $this->sandbox = false;
        }
    }

    /**
     * @return bool|mixed
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }
}