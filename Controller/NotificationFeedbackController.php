<?php

namespace IrishDan\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationFeedbackController extends Controller
{
    /**
     * Route for setting a notification as read
     */
    public function readAction($uuid)
    {
        $databaseNotificationManager = $this->get('notification.database_notification_manager');

        try {
            $databaseNotificationManager->findAndSetAsRead(['uuid' => $uuid]);

            $code = 200;
            $status = 'ok';
            $message = sprintf('Database notification %s marked as read', $uuid);
        } catch (\Exception $e) {
            $code = 500;
            $status = 'error';
            $message = $e->getMessage();
        }

        return new JsonResponse(
            [
                'status' => $status,
                'message' => $message,
            ],
            $code
        );
    }
}
