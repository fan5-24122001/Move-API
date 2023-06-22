<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationRepository;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getAllNotificationOfUser()
    {
        return $this->notificationRepository->getAllNotificationOfUser();
    }

    public function updateStatusNotification()
    {
        return $this->notificationRepository->updateStatusNotification();
    }
}
