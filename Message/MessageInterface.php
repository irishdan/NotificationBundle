<?php

namespace IrishDan\NotificationBundle\Message;

/**
 * Interface MessageInterface
 * Message objects should contain all the information required by a channel to successfully send the message.
 * This information is divided as such:
 * 1 - Message data
 *     Contains the actual contents of the message.
 *     This data is retrieved with the getMessageData method.
 * 2 - Dispatch Data
 *     Contains all of the information to successfully dispatch the message via the channel adapter.
 *     This data is retrieved with the getDispatchData method.
 * 3 - The Channel name
 *     Multiple channels can use the same type of adapter so it is necessary to know what channel the message is intended for
 *    This data is retrieved with the getChannel method.
 *
 * @package IrishDan\NotificationBundle\Message
 */
interface MessageInterface
{
    /**
     * Returns an array of all data needed to deliver the message to the recipient.
     * For example for Email message this would include the to, from and potentially, cc and bcc emails addresses.
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
     * Returns an array of all data needed to generate the message content.
     * For example for Email message this would include the subject, body and any attachments
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