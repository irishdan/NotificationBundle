<?php

namespace IrishDan\NotificationBundle\Security;

use IrishDan\NotificationBundle\PusherableInterface;
use IrishDan\NotificationBundle\PusherChannel;
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
        if (!$user instanceof PusherableInterface) {
            return false;
        }

        if (!$user->isSubscribedToChannel('pusher')) {
            return false;
        }

        switch ($attribute) {
            case self::SUBSCRIBE:
                // Compare the channel names to determine if this is the current user's channel.
                $channelPrefix = $this->pusherConfiguration['channel_name'];
                $channelSuffix = $user->getIdentifier();

                if ($channelPrefix . $channelSuffix === $subject->getChannelName()) {
                    return true;
                }

                break;
        }

        return false;
    }
}