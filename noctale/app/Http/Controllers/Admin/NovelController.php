<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\Notification;
use Illuminate\Http\Request;

class NovelController extends Controller
{
    public function index(Request $request)
    {
        $pendingNovels = Novel::where('publish_status', 'pending')
            ->with(['author', 'genres', 'chapters'])
            ->withCount('chapters')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $query = Novel::where('publish_status', 'published')
            ->with(['author', 'genres'])
            ->withCount('chapters');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('author', function($aq) use ($request) {
                      $aq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', function($gq) use ($request) {
                $gq->where('genres.id', $request->genre);
            });
        }

        $publishedNovels = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $genres = \App\Models\Genre::orderBy('name')->get();
        
        return view('admin.novels.index', compact('pendingNovels', 'publishedNovels', 'genres'));
    }
    
    public function update(Request $request, Novel $novel)
    {
        $action = $request->input('action');
        
        if ($action === 'approve') {
            $novel->update([
                'publish_status' => 'published',
                'rejection_reason' => null
            ]);
            
            Notification::create([
                'user_id' => $novel->user_id,
                'title' => 'Novel Diterima!',
                'message' => 'Selamat, karya Anda yang berjudul "' . $novel->title . '" telah lolos moderasi Admin dan resmi didistribusikan di beranda utamal.',
                'is_read' => false,
            ]);
            
            return back()->with('success', 'Novel disetujui untuk rilis publik!');
        }
        
        if ($action === 'reject') {
            $reason = $request->input('rejection_reason', 'Konten tidak memenuhi standar komunitas kami.');
            
            $novel->update([
                'publish_status' => 'rejected',
                'rejection_reason' => $reason
            ]);
            
            Notification::create([
                'user_id' => $novel->user_id,
                'title' => 'Publikasi Ditolak',
                'message' => 'Maaf, pengajuan rilis novel "' . $novel->title . '" ditolak oleh Admin dengan alasan: ' . $reason,
                'is_read' => false,
            ]);
            
            return back()->with('error', 'Novel ditolak dengan alasan: ' . $reason);
        }

        return back();
    }
    public function destroy(Request $request, Novel $novel)
    {
        $reason = $request->input('reason', 'Karya Anda telah dihapus oleh Admin karena melanggar ketentuan komunitas kami.');
        
        Notification::create([
            'user_id' => $novel->user_id,
            'title' => '⚠️ Pemberitahuan: Karya Dihapus',
            'message' => 'Pemberitahuan resmi: Karya Anda yang berjudul "' . $novel->title . '" telah dihapus dari sistem oleh Admin dengan alasan: ' . $reason,
            'is_read' => false,
        ]);

        $novel->delete();
        return back()->with('success', 'Karya tersebut telah dimusnahkan dan penulis telah dikirimi nota pemberitahuan.');
    }
}
