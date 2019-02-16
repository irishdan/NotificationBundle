<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use Doctrine\ORM\Mapping as ORM;
use IrishDan\NotificationBundle\Notification\DatabaseNotificationInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;

/**
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="NotificationBundle\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Notification implements DatabaseNotificationInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="guid")
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notifiable;

    /**
     * @ORM\Column(type="string", unique=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", unique=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var string
     * @ORM\Column(name="data", type="json_array", nullable=true)
     */
    private $data;

    /**
     * Read at date and time
     *
     * @var \DateTime
     * @ORM\Column(name="read_at", type="datetime", nullable=true)
     */
    private $readAt;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    private $created;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function setNotifiable(NotifiableInterface $notifiable)
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return \DateTime
     */
    public function getReadAt()
    {
        return $this->readAt;
    }

    /**
     * @param \DateTime $readAt
     */
    public function setReadAt(\DateTime $readAt)
    {
        $this->readAt = $readAt;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     * @ORM\PreUpdate
     */
    public function updated()
    {
        $updated = new \DateTime();
        $this->updated = $updated;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $date = new \DateTime();
        $this->created = $date;
        $this->updated = $date;
    }

    public function markAsRead()
    {
        $this->readAt = new \DateTime();
    }

    public function isRead()
    {
        return !empty($this->readAt);
    }
}