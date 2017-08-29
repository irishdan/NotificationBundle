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

        $mail = \Swift_Message::newInstance()
            ->setSubject($messageData['title'])
            ->setBody($messageData['body']);

        $mail->setFrom($dispatchData['from']);
        $mail->setTo($dispatchData['to']);

        // Check if its a html email
        if (!empty($messageData['html_email'])) {
            $mail->setContentType('text/html');
        }

        // Add any attachments
        if (!empty($messageData['attachments'])) {
            foreach ($messageData['attachments'] as $path => $filename) {
                $mail->attach(
                    \Swift_Attachment::fromPath($path)->setFilename($filename)
                );
            }
        }

        $sent = $this->mailer->send($mail);

        return !empty($sent);
    }
}