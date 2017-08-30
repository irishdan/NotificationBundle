<?php

namespace IrishDan\NotificationBundle\Adapter;

use IrishDan\NotificationBundle\EmailableInterface;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class MailMessageAdapter extends BaseMessageAdapter implements MessageAdapterInterface
{
    const CHANNEL = 'mail';
    protected $mailConfiguration;
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer, array $mailConfiguration = [])
    {
        $this->mailConfiguration = $mailConfiguration;
        $this->mailer = $mailer;
    }

    /**
     * Generates a Message object
     *
     * @param NotificationInterface $notification
     * @return \IrishDan\NotificationBundle\Message\Message
     */
    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var EmailableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof EmailableInterface) {
            $this->createFormatterException(EmailableInterface::class, self::CHANNEL);
        }

        // Build the dispatch data array.
        $dispatchData = [
            'to' => $notifiable->getEmail(),
            'from' => $this->getSender($notification),
            'cc' => '',
            'bcc' => '',
        ];

        $messageData = self::createMessagaData($notification->getDataArray());
        $data = $notification->getDataArray();

        if (!empty($data['html_email'])) {
            $messageData['html_email'] = true;
        }
        // Add any email attachments.
        $messageData['attachments'] = empty($data['attachments']) ? [] : $data['attachments'];

        return self::createMessage($dispatchData, $messageData, self::CHANNEL);
    }

    protected function getSender(NotificationInterface $notification)
    {
        $data = $notification->getDataArray();

        if (!empty($data['from'])) {
            return $data['from'];
        }

        if (!empty($this->mailConfiguration['default_sender'])) {
            return $this->mailConfiguration['default_sender'];
        }

        throw new \LogicException(
            'There is no "from" email address or "default_sender" configured'
        );
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