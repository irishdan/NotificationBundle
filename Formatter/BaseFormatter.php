<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\Message;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class BaseFormatter
{
    protected $twig;
    protected $eventDispatcher;

    public function setTemplating(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function setDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function renderTwigTemplate($data, $user, $template)
    {
        return $this->twig->render(
            $template,
            [
                'data' => $data,
                'user' => $user,
            ]
        );
    }

    public function format(NotificationInterface $notification)
    {
        if (!empty($this->twig) && $notification->getTemplate()) {
            $data         = $notification->getDataArray();
            $data['body'] = $this->renderTwigTemplate(
                $data,
                $notification->getNotifiable(),
                $notification->getTemplate()
            );

            $notification->setDataArray($data);
        }
    }

    static protected function createMessage($dispatchData, $messageData, $channel = 'default')
    {
        $message = new Message();

        $message->setChannel($channel);
        $message->setDispatchData($dispatchData);
        $message->setMessageData($messageData);

        return $message;
    }

    static protected function createMessagaData(array $notificationData)
    {
        // Build the message data array.
        $messageData          = [];
        $messageData['body']  = empty($notificationData['body']) ? '' : $notificationData['body'];
        $messageData['title'] = empty($notificationData['title']) ? '' : $notificationData['title'];

        return $messageData;
    }
}