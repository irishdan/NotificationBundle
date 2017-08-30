<?php

namespace IrishDan\NotificationBundle\Message;

/**
 * Interface MessageInterface
 *
 * @package IrishDan\NotificationBundle\Message
 */
interface MessageInterface
{
    /**
     * Returns an array of all data needed to deliver the message to the recipient
     *
     * @return array
     */
    public function getDispatchData();

    /**
     * Dispatch data array setter
     *
     * @param array $data
     * @return void
     */
    public function setDispatchData(array $data);

    /**
     * Returns an array of all data needed to generate the message content
     */
    public function getMessageData();

    /**
     * Message Content data setter.
     *
     * @param array $data
     * @return void
     */
    public function setMessageData(array $data);

    /**
     * Returns the channel that this message is being dispatched on
     * Needed when messages aren't sent synchronously.
     *
     * @return string
     */
    public function getChannel();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getRecipient();
}