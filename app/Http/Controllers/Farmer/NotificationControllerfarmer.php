<?php

namespace App\Http\Controllers\Farmer;

use Illuminate\Http\Request;
use App\Models\Notification; 
use App\Http\Controllers\Controller;

class NotificationControllerfarmer extends Controller
{
    /** 
     * Get all notifications for the authenticated farmer.
     */
    public function index()
    {
        $farmerId = auth()->id();

        $notifications = Notification::where('farmer_id', $farmerId)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = Notification::where('farmer_id', $farmerId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Fetch unread notification count (for real-time updates)
     */
    public function getUnreadCount()
    {
        $unreadCount = Notification::where('farmer_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    /**
     * Mark notifications as read.
     */
    public function markAsRead()
    {
        Notification::where('farmer_id', auth()->id())
            ->update(['is_read' => true]);

        return response()->json([
            'success' => 'Notifications marked as read',
            'unread_count' => 0
        ]);
    }

    /**
     * Delete a specific notification.
     */
    public function destroy($id)
    {
        $farmerId = auth()->id();

        $notification = Notification::where('id', $id)
            ->where('farmer_id', $farmerId)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->delete();

        // Get updated unread count after deletion
        $unreadCount = Notification::where('farmer_id', $farmerId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => 'Notification deleted successfully',
            'unread_count' => $unreadCount
        ]);
    }
}
