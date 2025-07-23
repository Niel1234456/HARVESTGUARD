<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // Display the latest notifications
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    // Mark a notification as read
    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
    
        session()->put('notifications_read', true); // Store read status in session
        
        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }
    // Delete a notification
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true, 'message' => 'Notification deleted successfully.']);
    }

    // Clear all notifications
    public function clearAll()
    {
        Notification::truncate();
        return response()->json(['success' => true, 'message' => 'All notifications cleared.']);
    }

    // Delete a specific notification
    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true, 'message' => 'Notification deleted successfully.']);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found.']);
    }
}
