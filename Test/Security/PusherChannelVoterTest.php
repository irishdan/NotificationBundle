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
    }

    public function testVote()
    {
        $this->pusherChannel = new PusherChannel('pusher_test_1', '1234567');

        $user = $this->getTestUser();
        $token = $this->getToken();
        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        // Vote should pass
        $vote = $this->voter->vote($token, $this->pusherChannel, ['subscribe']);
        $this->assertEquals(1, $vote);

        // Unsupported action
        // Vote should not pass
        $vote = $this->voter->vote($token, $this->pusherChannel, ['sizzle']);
        $this->assertEquals(-1, $vote);

        // Unsupported entity
        // Vote should not vote
        $vote = $this->voter->vote($token, $user, ['subscribe']);
        $this->assertEquals(0, $vote);

        // Vote should not pass
        $user->setSubscribedChannels([]);
        $vote = $this->voter->vote($token, $this->pusherChannel, ['subscribe']);
        $this->assertEquals(-1, $vote);
    }

    public function testVoteOnNotUsersChannel()
    {
        $this->pusherChannel = new PusherChannel('pusher_test_2', '1234567');

        $user = $this->getTestUser();
        $token = $this->getToken();
        $token->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));

        // Vote should pass
        $vote = $this->voter->vote($token, $this->pusherChannel, ['subscribe']);
        $this->assertEquals(-1, $vote);
    }
}