<?php


namespace App\Builders;

/**
 * Class VoipNotificationBuilder
 * @package App\Builders
 */
class AlertNotificationBuilder implements IIosNotificationBuilder
{

    const APNS_PUSH_TYPE = 'alert';
    const APNS_TOPIC = 'com.visicommedia.manycam';
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
            'alert'             => [
                'title'    => $data['alert_title'] ?? null,
                'subtitle' => $data['alert_subtitle'] ?? null,
                'body'     => $data['alert'] ?? null,
            ],
            'content-available' => $data['content-available'] ?? 0,
            'sound'             => $data['sound'] ?? self::SOUND,
            'category'          => $data['type'] ?? 'CONNECT_AS_SOURCE',
        ];

        unset($data['device_token']);
        unset($data['content-available']);
        unset($data['sound']);
        unset($data['alert_title']);
        unset($data['alert_subtitle']);
        unset($data['alert']);
        unset($data['type']);
        unset($data['sandbox']);
        unset($data['expiration_time']);

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