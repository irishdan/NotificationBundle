<?php

namespace IrishDan\NotificationBundle\Message;

/**
 * Class DatabaseMessage
 *
 * @package NotificationsBundle\Messages
 */
class DatabaseMessage extends BaseMessage
{
    /**
     * The data that should be stored with the notification.
     *
     * @var array
     */
    private $data = [];
    /**
     * @var
     */
    private $notifiable;
    /**
     * @var
     */
    private $type;
    /**
     * @var
     */
    private $uuid;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getNotifiable()
    {
        return $this->notifiable;
    }

    /**
     * @param mixed $notifiable
     */
    public function setNotifiable($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }
}
