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
        $messageData  = $message->getMessageData();


        $mail = \Swift_Message::newInstance()
                              ->setSubject($messageData['subject'])
                              ->setBody($messageData['body']);

        $mail->setFrom($dispatchData['from']);
        $mail->setTo($dispatchData['to']);

        return $this->mailer->send($mail);
    }
}