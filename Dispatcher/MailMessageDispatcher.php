<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\BaseMessage;

class MailMessageDispatcher implements MessageDispatcherInterface
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function dispatch(BaseMessage $message)
    {
        $mail = \Swift_Message::newInstance()
            ->setSubject($message->getSubject())
            ->setBody($message->getBody());

        $mail->setFrom($message->getFrom());
        $mail->setTo($message->getTo());

        return $this->mailer->send($mail);
    }
}