<?php

namespace IrishDan\NotificationBundle;

/**
 * Trait FullyNotifiableTrait
 * This trait is handy for getting set up quickly.
 *
 * @package IrishDan\NotificationBundle
 */
trait FullyNotifiableTrait
{
    protected $email;
    protected $slackWebhook;
    protected $number;

    /**
     * From EmailableInterface
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->mail;
    }

    /**
     * From SlackableInterface
     *
     * @return string
     */
    public function getSlackWebhook()
    {
        return $this->slackWebhook;
    }

    /**
     * From TextableInterface
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * From PusherableInterface
     *
     * @return string
     */
    public function getPusherChannelSuffix()
    {
        return '_' . $this->id;
    }
}