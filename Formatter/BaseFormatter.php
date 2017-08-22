<?php

namespace IrishDan\NotificationBundle\Formatter;

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
}