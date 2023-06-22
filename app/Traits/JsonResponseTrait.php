<?php 

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

trait JsonResponseTrait
{
    protected function result($data, $status, $success)
    {
        return new JsonResponse(
            [
                'success' => $success,
                'data' => $data,
                'status_code' => $status,
            ], $status
        );
    }

    private function getRedirectUrl($notification)
    {
        // Xử lý và trả về địa chỉ URL mới dựa trên loại thông báo và dữ liệu

        if ($notification->type === 'follow') {
            return '/offline-channel/'.$notification->creator_id;
        } elseif ($notification->type === 'comment') {
            return '/detail-video/'.$notification->video_id;
        } elseif ($notification->type === 'reply') {
            return '/detail-video/'.$notification->video_id;
        }

        return null;
    }
}

