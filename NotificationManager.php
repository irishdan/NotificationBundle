<?php

namespace IrishDan\NotificationBundle;

use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class NotificationManager
 * This the primary public service.
 * From here all notifications can be dispatched.
 * Essentially is just a wrapper around the ContainerManager and DatabaseNotificationManager
 *
 * @package NotificationBundle
 */
class NotificationManager
{
    /**
     * @var ChannelManager
     */
    private $channelManager;
    /**
     * @var DatabaseNotificationManager
     */
    private $databaseNotificationManager;
    protected $propertyAccessor;

    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }

    public function setDatabaseNotificationManager(DatabaseNotificationManager $databaseNotificationManager)
    {
        $this->databaseNotificationManager = $databaseNotificationManager;
    }

    public function broadcast(NotificationInterface $notification, array $broadcasters = null)
    {

    }

    public function send(NotificationInterface $notification, $recipients, array $data = [])
    {
        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        if (!empty($data)) {
            if (empty($this->propertyAccessor)) {
                $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
            }

            $dataArray = $notification->getDataArray();
            foreach ($data as $key => $value) {
                $this->propertyAccessor->setValue($dataArray, '[' . $key . ']', $value);
            }

            $notification->setDataArray($dataArray);
        }

        $this->channelManager->send($recipients, $notification);
    }

    public function markAsRead(NotificationInterface $notification)
    {
        $now = new \DateTime();
        try {
            $this->databaseNotificationManager->setReadAtDate($notification, $now);

            return true;
        } catch (\Exception $exception) {
            // @TODO:
        }
    }

    public function markAllAsRead(NotifiableInterface $user)
    {
        $now = new \DateTime();
        try {
            $this->databaseNotificationManager->setUsersNotificationsAsRead($user, $now);

            return true;
        } catch (\Exception $exception) {
            // @TODO:
        }
    }

    public function allNotificationCount(NotifiableInterface $user)
    {
        return $this->notificationCount($user);
    }

    public function unreadNotificationCount(NotifiableInterface $user)
    {
        return $this->notificationCount($user, 'unread');
    }

    public function readNotificationCount(NotifiableInterface $user)
    {
        return $this->notificationCount($user, 'read');
    }

    public function notificationCount(NotifiableInterface $user, $status = '')
    {
        // try {

        return $this->databaseNotificationManager->getUsersNotificationCount($user, $status);
        // } catch (\Exception $exception) {
        //     // @TODO:
        // }
    }
}
