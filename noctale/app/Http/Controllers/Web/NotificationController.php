<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('user.inbox', compact('notifications'));
    }
    
    public function read($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        return back();
    }

    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return back()->with('success', 'Semua pemberitahuan telah ditandai sebagai dibaca.');
    }
}
