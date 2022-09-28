<?php


namespace App\Builders;


/**
 * Interface IIosNotificationBuilder
 * @package App\Builders
 */
interface IIosNotificationBuilder
{
    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @param $body
     * @return mixed
     */
    public function setBody($body);

    /**
     * @param $deviceToken
     * @return mixed
     */
    public function setDeviceToken($deviceToken);

    /**
     * @return mixed
     */
    public function getDeviceToken();

    /**
     * @return mixed
     */
    public function getPushType();

    /**
     * @return mixed
     */
    public function getTopic();

    /**
     * @return mixed
     */
    public function getPriority();

    /**
     * @return mixed
     */
    public function getExpiration();

    /**
     * @param $data
     * @return mixed
     */
    public function setSandbox($data);
    /**
     * @return mixed
     */
    public function isSandbox();
}