<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\SampleService;
use App\Exceptions\SampleException;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\NotificationResource;
use App\Services\Notifications\NotificationType;

class NotificationController extends Controller
{
    public function index(Request $request)
    {   
        if (!$request->expectsJson()) {
            return view('notifications');
        }

        $notificationsQuery = auth()->user()->notifications();

        if ($request->read_at === 'unread') {
            $notificationsQuery->whereNull('read_at');
        } else if ($request->read_at === 'read') {
            $notificationsQuery->whereNotNull('read_at');
        }

        if ($request->date_start && $request->date_end) {
            $notificationsQuery->whereBetween('created_at', [
                $request->date_start,
                $request->date_end,
            ]);
        }

        $notificationsQuery = $request->take
                            ? $notificationsQuery->take($request->take)
                            : $notificationsQuery->take(20);

        $notifications = $notificationsQuery->get();

        // return $notifications;

        return NotificationResource::collection($notifications);
    }

    public function markAsReadById(Request $request, string $id)
    {
        $request->validate([
            'read_at' => ['required', 'string', 'date'],
        ]);

        $notification = auth()->user()->notifications()
                            ->where('id', $id)
                            ->first();

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
