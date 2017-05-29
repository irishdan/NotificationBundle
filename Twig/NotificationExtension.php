<?php

namespace NotificationBundle\Twig;

use NotificationBundle\Notification\NotifiableInterface;
use NotificationBundle\NotificationManager;
use NotificationBundle\PusherManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class NotificationExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $pusherManager;
    private $router;
    private $notificationManager;

    public function __construct(PusherManager $pusherManager, Router $router, NotificationManager $notificationManager)
    {
        $this->pusherManager = $pusherManager;
        $this->router = $router;
        $this->notificationManager = $notificationManager;
    }

    public function getFunctions()
    {
        return [
            // Database notifications.
            new \Twig_SimpleFunction('notification_unread_count', [$this, 'getUserUnreadCount']),
            new \Twig_SimpleFunction('notification_read_count', [$this, 'getUserReadCount']),
            new \Twig_SimpleFunction('notification_all_count', [$this, 'getUserAllCount']),
            // Pusher twig functions.
            // Mainly for generating the javascript for each user.
            new \Twig_SimpleFunction('notification_pusher_channel_name', [$this, 'getUserChannelName']),
            new \Twig_SimpleFunction('notification_new_pusher_js', [$this, 'createNewPusherJS'],
                ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('notification_new_pusher_channel_js', [$this, 'createNewPusherChannelJS'],
                ['is_safe' => ['html']]),
        ];
    }

    public function getGlobals()
    {
        return [
            'notification_pusher_auth_endpoint' => $this->getAuthEndpoint(),
            'notification_pusher_auth_key' => $this->pusherManager->getAuthKey(),
            'notification_pusher_app_id' => $this->pusherManager->getAppId(),
            'notification_pusher_event' => $this->pusherManager->getEvent(),
            'notification_pusher_cluster' => $this->pusherManager->getCluster(),
            'notification_pusher_encrypted' => $this->pusherManager->getEncrypted(),
        ];
    }

    public function getUserAllCount(NotifiableInterface $user)
    {
        return $this->notificationManager->notificationCount($user);
    }

    public function getUserUnreadCount(NotifiableInterface $user)
    {
        return $this->notificationManager->unreadNotificationCount($user);
    }

    public function getUserReadCount(NotifiableInterface $user)
    {
        return $this->notificationManager->readNotificationCount($user);
    }

    public function getUserChannelName(NotifiableInterface $user)
    {
        if ($user) {
            return $this->pusherManager->getUserChannelName($user);
        }

        return '';
    }

    public function createNewPusherChannelJS(NotifiableInterface $user)
    {
        $channelName = $this->getUserChannelName($user);

        return "var channel = pusher.subscribe('" . $channelName . "');";
    }

    public function createNewPusherJS()
    {
        return 'var pusher = new Pusher("' . $this->pusherManager->getAuthKey() . '", {
                authEndpoint: "' . $this->getAuthEndpoint() . '",
                cluster: "' . $this->pusherManager->getCluster() . '",
                encrypted: ' . $this->pusherManager->getEncrypted() . '
            });';
    }

    public function getAuthEndpoint()
    {
        $exists = $this->router->getRouteCollection()->get('notification_pusher_auth');
        if (null !== $exists) {
            return $this->router->generate('notification_pusher_auth');
        }

        return '';
    }

    public function getName()
    {
        return 'notification_extension';
    }
}