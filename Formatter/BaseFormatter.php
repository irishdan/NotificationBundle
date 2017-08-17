<?php

namespace IrishDan\NotificationBundle\Formatter;

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
}