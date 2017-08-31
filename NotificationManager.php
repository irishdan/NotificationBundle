<?php

namespace IrishDan\NotificationBundle;

use IrishDan\NotificationBundle\Broadcast\Broadcaster;
use IrishDan\NotificationBundle\Exception\BroadcastException;
use IrishDan\NotificationBundle\Notification\DatabaseNotificationInterface;
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
    protected $channelManager;
    /**
     * @var DatabaseNotificationManager
     */
    protected $databaseNotificationManager;
    /**
     * @var
     */
    protected $propertyAccessor;
    /**
     * @var array
     */
    protected $broadcasters = [];

    /**
     * NotificationManager constructor.
     *
     * @param ChannelManager                   $channelManager
     * @param DatabaseNotificationManager|null $databaseNotificationManager
     */
    public function __construct(ChannelManager $channelManager, DatabaseNotificationManager $databaseNotificationManager = null)
    {
        $this->channelManager = $channelManager;
        $this->databaseNotificationManager = $databaseNotificationManager;
    }

    /**
     * @param             $key
     * @param Broadcaster $broadcaster
     */
    public function setBroadcaster($key, Broadcaster $broadcaster)
    {
        $this->broadcasters[$key] = $broadcaster;
    }

    /**
     * @param DatabaseNotificationManager $databaseNotificationManager
     */
    public function setDatabaseNotificationManager(DatabaseNotificationManager $databaseNotificationManager)
    {
        $this->databaseNotificationManager = $databaseNotificationManager;
    }

    /**
     * @param NotificationInterface $notification
     * @param array                 $broadcasters
     * @throws BroadcastException
     */
    public function broadcast(NotificationInterface $notification, array $broadcasters)
    {
        foreach ($broadcasters as $broadcaster) {
            if (empty($this->broadcasters[$broadcaster])) {
                throw new BroadcastException(
                    sprintf('Broadcast channel with key "%s" does not exists', $broadcaster)
                );
            }

            $this->broadcasters[$broadcaster]->broadcast($notification);
        }
    }

    /**
     * @param NotificationInterface $notification
     * @param                       $recipients
     * @param array                 $data
     */
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

    /**
     * @param DatabaseNotificationInterface $notification
     * @return bool
     */
    public function markAsRead(DatabaseNotificationInterface $notification)
    {
        $now = new \DateTime();
        try {
            $this->databaseNotificationManager->setReadAtDate($notification, $now);

            return true;
        } catch (\Exception $exception) {
            // @TODO:
        }
    }

    /**
     * @param NotifiableInterface $user
     * @return bool
     */
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

    /**
     * @param NotifiableInterface $user
     * @return int
     */
    public function allNotificationCount(NotifiableInterface $user)
    {
        return $this->notificationCount($user);
    }

    /**
     * @param NotifiableInterface $user
     * @return int
     */
    public function unreadNotificationCount(NotifiableInterface $user)
    {
        return $this->notificationCount($user, 'unread');
    }

    /**
     * @param NotifiableInterface $user
     * @return int
     */
    public function readNotificationCount(NotifiableInterface $user)
    {
        return $this->notificationCount($user, 'read');
    }

    /**
     * @param NotifiableInterface $user
     * @param string              $status
     * @return int
     */
    public function notificationCount(NotifiableInterface $user, $status = '')
    {
        return $this->databaseNotificationManager->getUsersNotificationCount($user, $status);
    }
}
