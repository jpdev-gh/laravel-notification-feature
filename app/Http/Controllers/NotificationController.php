<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Exceptions\Sample;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function markAsReadById(string $id)
    {
        $notification = auth()->user()->notifications()
                            ->where('id', $id)
                            ->first();

        $notification->update(['read_at' => Carbon::now()]);
        
        return new NotificationResource($notification);
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
