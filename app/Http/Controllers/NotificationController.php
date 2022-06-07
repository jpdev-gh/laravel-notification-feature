<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Services\Notifications\NotificationType;

class NotificationController extends Controller
{
    public function index(Request $request)
    {   
        $notificationsQuery = auth()->user()->notifications();

        if ($request->type) {
            $notificationsQuery->where('type', NotificationType::get($request->type));
        }

        $notificationsQuery = $request->take
                            ? $notificationsQuery->take($request->take)
                            : $notificationsQuery->take(20);

        $notifications = $notificationsQuery->get();

        return NotificationResource::collection($notifications);
    }

    public function markAsReadById(Request $request, string $id)
    {
        $notification = auth()->user()->notifications()
                            ->where('id', $id)->first();
        $notification->read_at = $request->read_at;
        $notification->save();

        return new NotificationResource($notification);
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
