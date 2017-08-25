<?php

namespace IrishDan\NotificationBundle\Twig;

use IrishDan\NotificationBundle\DatabaseNotificationManager;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\PusherManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class NotificationExtension
 *
 * @package IrishDan\NotificationBundle\Twig
 */
class NotificationExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $pusherManager;
    protected $router;
    protected $databaseNotificationManager;
    protected $availableChannels = [];

    public function __construct(
        PusherManager $pusherManager,
        Router $router,
        DatabaseNotificationManager $databaseNotificationManager,
        array $availableChannels
    ) {
        $this->pusherManager = $pusherManager;
        $this->router = $router;
        $this->databaseNotificationManager = $databaseNotificationManager;
        $this->availableChannels = $availableChannels;
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
            new \Twig_SimpleFunction('notification_pusher_channel_name',
                [$this, 'getUserChannelName']),
            new \Twig_SimpleFunction('notification_new_pusher_js', [$this, 'createNewPusherJS'],
                ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('notification_new_pusher_channel_js',
                [$this, 'createNewPusherChannelJS'],
                ['is_safe' => ['html']]),
        ];
    }

    public function getGlobals()
    {
        if ($this->channelEnabled()) {
            return [
                'notification_pusher_auth_endpoint' => $this->getAuthEndpoint(),
                'notification_pusher_auth_key' => $this->pusherManager->getAuthKey(),
                'notification_pusher_app_id' => $this->pusherManager->getAppId(),
                'notification_pusher_event' => $this->pusherManager->getEvent(),
                'notification_pusher_cluster' => $this->pusherManager->getCluster(),
                'notification_pusher_encrypted' => $this->pusherManager->getEncrypted(),
            ];
        }

        return [];
    }

    protected function channelEnabled($channel = 'pusher')
    {
        return in_array($channel, $this->availableChannels);
    }

    public function getUserAllCount(NotifiableInterface $user)
    {
        if ($this->channelEnabled('database')) {
            return $this->databaseNotificationManager->getUsersNotificationCount($user, '');
        }

        return '';
    }

    public function getUserUnreadCount(NotifiableInterface $user)
    {
        if ($this->channelEnabled('database')) {
            return $this->databaseNotificationManager->getUsersNotificationCount($user, 'unread');
        }

        return '';
    }

    public function getUserReadCount(NotifiableInterface $user)
    {
        if ($this->channelEnabled('database')) {
            return $this->databaseNotificationManager->getUsersNotificationCount($user, 'read');
        }

        return '';
    }

    public function getUserChannelName(NotifiableInterface $user)
    {
        if ($this->channelEnabled()) {
            if ($user) {
                return $this->pusherManager->getUserChannelName($user);
            }
        }

        return '';
    }

    public function createNewPusherChannelJS(NotifiableInterface $user)
    {
        if ($this->channelEnabled()) {
            $channelName = $this->getUserChannelName($user);

            return "var channel = pusher.subscribe('" . $channelName . "');";
        }

        return '';
    }

    public function createNewPusherJS()
    {
        if ($this->channelEnabled()) {
            return 'var pusher = new Pusher("' . $this->pusherManager->getAuthKey() . '", {
                authEndpoint: "' . $this->getAuthEndpoint() . '",
                cluster: "' . $this->pusherManager->getCluster() . '",
                encrypted: ' . $this->pusherManager->getEncrypted() . '
            });';
        } else {
            return 'console.log("Pusher channel is not enabled")';
        }
    }

    public function getAuthEndpoint()
    {
        if ($this->channelEnabled()) {
            $exists = $this->router->getRouteCollection()->get('notification.pusher_auth');
            if (null !== $exists) {
                return $this->router->generate('notification.pusher_auth');
            }
        }

        return '';
    }

    public function getName()
    {
        return 'notification_extension';
    }
}