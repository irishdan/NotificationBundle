<?php

namespace NotificationBundle\Message;

/**
 * Class MailMessage
 *
 * @package NotificationsBundle\Message
 */
class MailMessage extends BaseMessage
{
    /**
     * The "from" information for the message.
     *
     * @var array
     */
    public $from;
    /**
     * The recipient information for the message.
     *
     * @var array
     */
    public $to;
    /**
     * The "cc" recipients of the message.
     *
     * @var array
     */
    public $cc = [];
    /**
     * The "reply to" information for the message.
     *
     * @var array
     */
    public $replyTo;
    /**
     * The attachments for the message.
     *
     * @var array
     */
    public $attachments = [];
    /**
     * @var
     */
    private $subject;
    /**
     * @var
     */
    private $body;
    /**
     * @var
     */
    private $template;

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Set the from address for the mail message.
     *
     * @param  string $address
     * @return $this
     */
    public function setFrom($address)
    {
        $this->from = $address;

        return $this;
    }

    /**
     * Set the recipient address for the mail message.
     *
     * @param  string|array $address
     * @return $this
     */
    public function setTo($address)
    {
        $this->to = $address;

        return $this;
    }

    /**
     * Set the recipients of the message.
     *
     * @param  string|array $address
     * @return $this
     */
    public function setCc($address)
    {
        $this->cc = $address;

        return $this;
    }

    /**
     * Set the "reply to" address of the message.
     *
     * @param  array|string $address
     * @param null          $name
     * @return $this
     */
    public function setReplyTo($address, $name = null)
    {
        $this->replyTo = [$address, $name];

        return $this;
    }

    /**
     * Attach a file to the message.
     *
     * @param  string $file
     * @param  array  $options
     * @return $this
     */
    public function setAttach($file, array $options = [])
    {
        $this->attachments[] = compact('file', 'options');

        return $this;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @return array
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }
}
