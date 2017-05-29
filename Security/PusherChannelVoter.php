<?php

namespace NotificationBundle\Security;


use NotificationBundle\Notification\NotifiableInterface;
use NotificationBundle\PusherChannel;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PusherChannelVoter extends Voter
{
    const SUBSCRIBE = 'subscribe';
    private $pusherEnabled = false;
    private $pusherConfiguration;

    public function __construct($pusherEnabled = false, $pusherConfiguration = [])
    {
        $this->pusherEnabled = $pusherEnabled;
        $this->pusherConfiguration = $pusherConfiguration;
    }

    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof PusherChannel) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$this->pusherEnabled) {
            return false;
        }

        if (empty($this->pusherConfiguration['channel_name'])) {
            return false;
        }

        $user = $token->getUser();
        if (!$user instanceof NotifiableInterface) {
            return false;
        }

        // @TODO: Check if user is subscribed to pusher channel.


        switch ($attribute) {
            case self::SUBSCRIBE:
                // Compare the channel names to determine if this is the current user's channel.
                $channelPrefix = $this->pusherConfiguration['channel_name'];
                $channelSuffix = $user->getId(); // @TODO: use the interface method instead

                if ($channelPrefix . $channelSuffix === $subject->getChannelName()) {
                    return true;
                }

                break;
        }

        return false;
    }
}