<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class NotificationRepository implements NotificationInterface
{
    use JsonResponseTrait;

    private Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;   
    }

    public function getAllNotificationOfUser()
    {
        $notifications = $this->notification->where('user_id', auth()->user()->id)->orderByDesc('created_at')->get();
        
        $result = [];

        $count = $this->notification->where('user_id', auth()->user()->id)
        ->whereNull('read_at')
        ->count();

        if(sizeof($notifications) > 0){
            foreach ($notifications as $notification) {
                $notification = [
                    'id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'img' => $notification->img,
                    'detail' => $notification->content,
                    'username'=> $notification->creator->username,
                    'read_at' => $notification->read_at,
                    'comment_id'=> $notification->comment_id,
                    'video_id'=> $notification->video_id,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at,
                    'redirect_url' => $this->getRedirectUrl($notification) 
                ];
                $resultTemp[] =  $notification;
            }
            $result[] = [
                'notifications' => $resultTemp,
                'notifications_unread' => $count,
            ];
            return $this->result($result, 200, true);
        }
        else{
            return new JsonResponse([
                'success' => true,
                'message' => 'No notifications yet',
                'status_code' => 200,
            ],200);
        }
    }

    public function updateStatusNotification()
    {
        $this->notification::where('user_id', auth()->user()->id)->update(['read_at' => Carbon::now()]);
        
        return new JsonResponse([
            'success' => true,
            'message' => 'Update status notification successfully',
            'status_code' => 200,
        ], 200);
    }
}
