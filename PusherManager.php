<?php

namespace IrishDan\NotificationBundle;

/**
 * Class PusherManager
 *
 * @package NotificationBundle
 */
class PusherManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * PusherManager constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Pusher
     */
    public function getPusherClient()
    {
        $options = [
            'cluster'   => $this->config['cluster'],
            'encrypted' => $this->config['encrypted'],
        ];
        $pusher  = new \Pusher(
            $this->config['auth_key'],
            $this->config['secret'],
            $this->config['app_id'],
            $options
        );

        return $pusher;
    }

    /**
     * @param PusherableInterface $user
     * @return string
     */
    public function getUserChannelName(PusherableInterface $user)
    {
        return $this->config['channel_name'] . $user->getPusherChannelSuffix();
    }

    /**
     * @param $suffix
     *
     * @return string
     */
    public function getChannelName($suffix)
    {
        return $this->config['channel_name'] . $suffix;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->config['event'];
    }

    /**
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->config['auth_key'];
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->config['secret'];
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->config['app_id'];
    }

    /**
     * @return mixed
     */
    public function getCluster()
    {
        return $this->config['cluster'];
    }

    /**
     * @return mixed
     */
    public function getEncrypted()
    {
        return $this->config['encrypted'];
    }
}