<?php

namespace IrishDan\NotificationBundle\Adapter;

use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Message\Message;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BaseMessageAdapter
 *
 * @package IrishDan\NotificationBundle\Adapter
 */
abstract class BaseMessageAdapter
{
    protected $twig;
    protected $eventDispatcher;
    protected $configuration = [];
    protected $channelName;

    /**
     * @param \Twig_Environment $twig
     */
    public function setTemplating(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $data
     * @param $user
     * @param $template
     * @return mixed
     */
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

    /**
     * @param NotificationInterface $notification
     */
    public function format(NotificationInterface $notification)
    {
        // @TODO: $this->channelName
        // $notification->setChannel($this->channelName);

        $templateArray = $notification->getTemplateArray();
        if (!empty($this->twig) && !empty($templateArray)) {
            $data = $notification->getDataArray();

            if ($notification->getUuid()) {
                $data['uuid'] = $notification->getUuid();
            }

            foreach ($templateArray as $key => $template) {
                // Find out if the template exists before trying to render it.
                $exists = $this->twig->getLoader()->exists($template);
                if (!empty($data[$key]) && $exists) {

                    $data[$key] = $this->renderTwigTemplate(
                        $data,
                        $notification->getNotifiable(),
                        $template
                    );
                }
            }

            $notification->setDataArray($data);
        }
    }

    /**
     * @param        $dispatchData
     * @param        $messageData
     * @param string $channel
     * @return Message
     */
    protected static function createMessage($dispatchData, $messageData, $channel = 'default')
    {
        $message = new Message();

        $message->setChannel($channel);
        $message->setDispatchData($dispatchData);
        $message->setMessageData($messageData);

        return $message;
    }

    /**
     * @param array $notificationData
     * @return array
     */
    protected static function createMessagaData(array $notificationData)
    {
        // Build the message data array.
        $messageData = [];
        $messageData['body'] = empty($notificationData['body']) ? '' : $notificationData['body'];
        $messageData['title'] = empty($notificationData['title']) ? '' : $notificationData['title'];
        $messageData['uuid'] = empty($notificationData['uuid']) ? '' : $notificationData['uuid'];

        return $messageData;
    }

    /**
     * @param $shouldImplement
     * @param $type
     * @throws MessageFormatException
     */
    protected static function createFormatterException($shouldImplement, $type)
    {
        $message = sprintf('Notifiable must implement %s interface in order to format as a %s message', $shouldImplement, $type);
        throw new MessageFormatException(
            $message
        );
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param mixed $channeName
     */
    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;
    }
}