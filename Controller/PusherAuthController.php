<?php

namespace IrishDan\NotificationBundle\Controller;

use IrishDan\NotificationBundle\PusherChannel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PusherAuthController extends Controller
{
    /**
     * Routing for private pusher channel authentication.
     */
    public function connectAction(Request $request)
    {
        $pusher = $this->get('notification.pusher_manager')->getPusherClient();

        $channelName = $request->get('channel_name');
        $socketId    = $request->get('socket_id');

        $pusherChannel = new PusherChannel($channelName, $socketId);

        // Check if user should have access.
        $this->denyAccessUnlessGranted('subscribe', $pusherChannel);

        // Creates a json encoded string.
        $responseBody = $pusher->socket_auth($channelName, $socketId);

        return new Response(
            $responseBody,
            '200',
            [
                'Content-Type' => 'application/json',
            ]
        );
    }
}
