<?php

namespace IrishDan\NotificationBundle\Message;

interface MessageInterface
{
    /**
     * Returns an array of all data needed to deliver the message to the recipient
     */
    public function getDispatchData();

    // @TODO: Will be needed by the formatter
    public function setDispatchData(array $data);

    /**
     * Returns an array of all data needed to generate the message content
     */
    public function getMessageData();

    // @TODO: Will be needed by the formatter
    public function setMessageData(array $data);

    // @TODO: Needed when messages aren't sent synchronously
    public function getChannel();
}