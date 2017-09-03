<?php


namespace IrishDan\NotificationBundle\Test\Entity;

use Doctrine\ORM\Mapping as ORM;
use IrishDan\NotificationBundle\Notification\DatabaseNotificationInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;

class Notification implements DatabaseNotificationInterface
{
    private $id;
    private $uuid;
    private $notifiable;
    private $type;
    private $title;
    private $body;
    private $readAt;
    private $created;
    private $updated;

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    public function getNotifiable()
    {
        return $this->notifiable;
    }

    public function setNotifiable(NotifiableInterface $notifiable)
    {
        $this->notifiable = $notifiable;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getReadAt()
    {
        return $this->readAt;
    }

    public function setReadAt(\DateTime $readAt)
    {
        $this->readAt = $readAt;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function updated()
    {
        $updated = new \DateTime();
        $this->updated = $updated;
    }

    public function prePersist()
    {
        $date = new \DateTime();
        $this->created = $date;
        $this->updated = $date;
    }

    public function markAsRead()
    {
        // TODO: Implement markAsRead() method.
    }

    public function isRead()
    {
        // TODO: Implement isRead() method.
    }

    public function isUnread()
    {
        // TODO: Implement isUnread() method.
    }
}