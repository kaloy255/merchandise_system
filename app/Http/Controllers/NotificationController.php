<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated admin user.
     */
    public function index()
    {
        $notifications = Auth::user()->adminNotifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => Auth::user()->unreadNotificationsCount()
        ]);
    }
    
    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Check if the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->adminNotifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
    
    /**
     * Create a new notification for admin users when an order is placed.
     */
    public static function createOrderNotification(Order $order)
    {
        // Find admin users
        $admins = User::where('is_admin', true)->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'order',
                'content' => "New order #{$order->order_number} has been placed by {$order->user->name}",
                'related_id' => $order->id,
            ]);
        }
    }
}
