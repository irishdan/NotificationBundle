<?php

namespace NotificationBundle\Formatter;

abstract class BaseFormatter
{
    protected $twig;

    public function setTemplating($twig)
    {
        $this->twig = $twig;
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