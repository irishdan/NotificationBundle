<?php

namespace IrishDan\NotificationBundle\Repository;

use Doctrine\ORM\EntityRepository;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;


class NotificationRepository extends EntityRepository
{
    public function getNotificationsCount(NotifiableInterface $user, $status = '')
    {
        $dq = $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->andWhere('n.notifiable = ' . $user->getId());

        switch ($status) {
            case 'read':
                $dq->andWhere('n.readAt IS NOT NULL');
                break;
            case 'unread':
                $dq->andWhere('n.readAt IS NULL');
                break;
        }

        $count = $dq->getQuery()->getSingleScalarResult();

        return $count;
    }
}
