<?php

namespace IrishDan\NotificationBundle\Test\Security;

use IrishDan\NotificationBundle\PusherChannel;
use IrishDan\NotificationBundle\Security\PusherChannelVoter;
use IrishDan\NotificationBundle\Test\NotificationTestCase;

class PusherChannelVoterTest extends NotificationTestCase
{
    protected $voter;
    protected $pusherChannel;

    public function setUp()
    {
        $config = $this->getNotificationChannelConfiguration('pusher');

        $this->voter = new PusherChannelVoter(true, $config);
        $this->pusherChannel = new PusherChannel('pusher_test_1', '1234567');
    }

    public function testSupports()
    {
        // $supports = $this->voter->supports('subscribe', $this->pusherChannel);
        $token = $this->getToken();
    }
}