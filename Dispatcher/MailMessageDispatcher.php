<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\MessageInterface;

class MailMessageDispatcher implements MessageDispatcherInterface
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function dispatch(MessageInterface $message)
    {
        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData = $message->getMessageData();

        // @TODO: Need to allow for more advanced mail.
        // @TODO: Should be able to handle attachments
        $mail = \Swift_Message::newInstance()
            ->setSubject($messageData['subject'])
            ->setBody($messageData['body']);

        $mail->setFrom($dispatchData['from']);
        $mail->setTo($dispatchData['to']);

        $sent = $this->mailer->send($mail);

        return ! empty($sent);
    }
}