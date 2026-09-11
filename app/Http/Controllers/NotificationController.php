<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->customNotifications()->paginate(20);
        
        // Define base prefix (e.g. /doctor, /patient, /admin) securely based on user role
        // For breadcrumbs or back links if needed by the view
        $rolePrefix = 'dashboard';
        if ($user->hasRole('doctor')) $rolePrefix = 'doctor.dashboard';
        if ($user->hasRole('patient')) $rolePrefix = 'patient.dashboard';
        if ($user->isAdmin()) $rolePrefix = 'admin.dashboard';

        return view('notifications.index', compact('notifications', 'rolePrefix'));
    }

    /**
     * Fetch latest notifications via API for the Bell Dropdown
     */
    public function latest(Request $request)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            
            // Using direct model query to avoid any trait/relationship conflicts
            $unreadCount = Notification::where('user_id', $userId)->whereNull('read_at')->count();
            $latestNotifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return response()->json([
                'unread_count' => $unreadCount,
                'notifications' => $latestNotifications
            ]);
        } catch (\Exception $e) {
            \Log::error('Notification API Error: ' . $e->getMessage());
            
            return response()->json([
                'unread_count' => 0,
                'notifications' => [],
                'debug_error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Mark a specific or all notifications as read
     */
    public function markAsRead(Request $request, $id = null)
    {
        $user = Auth::user();
        
        if ($id) {
            $notification = $user->customNotifications()->findOrFail($id);
            $notification->markAsRead();
        } else {
            // Mark all as read
            $user->customNotifications()->unread()->update(['read_at' => now()]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back();
    }
}
