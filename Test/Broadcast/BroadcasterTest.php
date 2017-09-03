<?php

namespace IrishDan\NotificationBundle\Test\Broadcast;

use IrishDan\NotificationBundle\Broadcast\Broadcast;
use IrishDan\NotificationBundle\Broadcast\Broadcaster;
use IrishDan\NotificationBundle\Channel\ChannelInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Test\NotificationTestCase;

class BroadcasterTest extends NotificationTestCase
{
    public function testBroadcast()
    {
        $notifiable = new Broadcast();
        $configuration = [
            'webhook' => 'http://slack.com/',
            'pusher_channel' => 'test_channel',
        ];

        $channel = $this->getMockBuilder(ChannelInterface::class)
            ->setMethods(['channelName', 'setDispatchToEvent', 'formatAndDispatch'])
            ->disableOriginalConstructor()
            ->getMock();
        $channel->expects($this->once())->method('channelName');
        $channel->expects($this->once())->method('setDispatchToEvent');
        $channel->expects($this->once())->method('formatAndDispatch');

        $notification = $this->getMockBuilder(NotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $notification->expects($this->once())->method('setNotifiable');

        $broadcaster = new Broadcaster($notifiable, $channel, $configuration);
        $broadcaster->broadcast($notification);
    }
}