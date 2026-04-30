<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Novel;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'novel_id' => 'nullable|exists:novels,id',
            'comment_id' => 'nullable|exists:comments,id',
            'reported_user_id' => 'nullable|exists:users,id',
            'reason' => 'required|string|max:1000',
        ]);

        $report = Report::create([
            'user_id' => auth()->id(),
            'novel_id' => $request->novel_id,
            'comment_id' => $request->comment_id,
            'reported_user_id' => $request->reported_user_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        $targetTitle = 'Konten';
        $ownerId = null;

        if ($request->novel_id) {
            $novel = Novel::find($request->novel_id);
            $targetTitle = 'Karya: ' . $novel->title;
            $ownerId = $novel->user_id;
        } elseif ($request->comment_id) {
            $comment = \App\Models\Comment::find($request->comment_id);
            $targetTitle = 'Komentar: "' . \Illuminate\Support\Str::limit($comment->content, 30) . '"';
            $ownerId = $comment->user_id;
        } elseif ($request->reported_user_id) {
            $user = User::find($request->reported_user_id);
            $targetTitle = 'Akun Pengguna: ' . $user->name;
            $ownerId = $user->id;
        }

        // Notify Owner (if any and not the reporter)
        if ($ownerId && $ownerId !== auth()->id()) {
            Notification::create([
                'user_id' => $ownerId,
                'title' => '⚠️ Peringatan Laporan',
                'message' => "Konten Anda ($targetTitle) telah dilaporkan. Alasan: " . $request->reason . ". Mohon segera tinjau kembali konten tersebut.",
                'is_read' => false,
            ]);
        }

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => '🚨 Laporan Baru (Review Required)',
                'message' => "Ada laporan baru untuk ($targetTitle) | Keluhan: " . $request->reason,
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Laporan berhasil terkirim. Admin akan segera meninjau laporan Anda.');
    }
}