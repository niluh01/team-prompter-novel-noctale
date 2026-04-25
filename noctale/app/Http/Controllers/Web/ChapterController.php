<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function index(Request $request, Novel $novel)
    {
        if ($novel->user_id !== Auth::id()) abort(403);
        
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        $query = $novel->chapters()->orderBy('chapter_number', 'asc');

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($status === 'draft') {
            $query->where('publish_status', 'draft');
        } elseif ($status === 'published') {
            $query->where('publish_status', 'published');
        } elseif ($status === 'scheduled') {
            $query->where('publish_status', 'scheduled');
        }

        $chapters = $query->paginate(20)->withQueryString();
        
        return view('writer.chapters.index', compact('novel', 'chapters', 'status', 'search'));
    }

    public function create(Novel $novel)
    {
        if ($novel->user_id !== Auth::id()) abort(403);
        $nextNumber = $novel->chapters()->max('chapter_number') + 1;
        return view('writer.chapters.create', compact('novel', 'nextNumber'));
    }

    public function store(Request $request, Novel $novel)
    {
        if ($novel->user_id !== Auth::id()) abort(403);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'chapter_number' => 'required|integer|min:1',
            'publish_status' => 'required|in:published,draft,scheduled',
            'scheduled_at' => 'required_if:publish_status,scheduled|nullable|date|after:now',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chapters', 'public');
        }

        $novel->chapters()->create([
            'title' => $request->title,
            'image' => $imagePath,
            'content' => $request->content,
            'chapter_number' => $request->chapter_number,
            'views' => 0,
            'publish_status' => $request->publish_status,
            'scheduled_at' => $request->publish_status === 'scheduled' ? $request->scheduled_at : null,
        ]);

        return redirect()->route('writer.novels.chapters.index', $novel)->with('success', 'Bab berhasil ditambahkan!');
    }

    // Public: Read chapter
    public function show(Novel $novel, Chapter $chapter)
    {
        // Cek akses: draft/scheduled (di masa depan) cuma bisa dilihat pembuatnya
        $isAuthor = Auth::id() === $novel->user_id;
        $isLive = $chapter->publish_status === 'published' || ($chapter->publish_status === 'scheduled' && $chapter->scheduled_at <= now());
        
        if (!$isLive && !$isAuthor) abort(404);

        $chapter->increment('views');
        
        if (Auth::check()) {
            \App\Models\ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'novel_id' => $novel->id],
                ['chapter_id' => $chapter->id, 'last_read_at' => now()]
            );
        }
        
        // Cuma ambil next/prev dari bab yg published
        $prevChapter = Chapter::where('novel_id', $novel->id)->published()->where('chapter_number', '<', $chapter->chapter_number)->orderBy('chapter_number', 'desc')->first();
        $nextChapter = Chapter::where('novel_id', $novel->id)->published()->where('chapter_number', '>', $chapter->chapter_number)->orderBy('chapter_number', 'asc')->first();

        // Ambil komentar bab ini
        $comments = $chapter->comments()
            ->with(['user', 'likes', 'replies.user'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalComments = $chapter->comments()->count();

        // Truncate content for guests to prevent inspection of full content
        if (!Auth::check() && !$isAuthor) {
            $chapter->content = \Illuminate\Support\Str::limit($chapter->content, 600);
        }

        return view('chapters.show', compact('novel', 'chapter', 'prevChapter', 'nextChapter', 'comments', 'totalComments'));
    }

    public function edit(Novel $novel, Chapter $chapter)
    {
        if ($novel->user_id !== Auth::id()) abort(403);
        return view('writer.chapters.edit', compact('novel', 'chapter'));
    }

    public function update(Request $request, Novel $novel, Chapter $chapter)
    {
        if ($novel->user_id !== Auth::id()) abort(403);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'chapter_number' => 'required|integer|min:1',
            'publish_status' => 'required|in:published,draft,scheduled',
            'scheduled_at' => 'required_if:publish_status,scheduled|nullable|date|after:now',
        ]);

        $imagePath = $chapter->image;
        if ($request->hasFile('image')) {
            if ($imagePath) \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('chapters', 'public');
        }

        $chapter->update([
            'title' => $request->title,
            'image' => $imagePath,
            'content' => $request->content,
            'chapter_number' => $request->chapter_number,
            'publish_status' => $request->publish_status,
            'scheduled_at' => $request->publish_status === 'scheduled' ? $request->scheduled_at : null,
        ]);

        return redirect()->route('writer.novels.chapters.index', $novel)->with('success', 'Bab berhasil diperbarui!');
    }

    public function destroy(Novel $novel, Chapter $chapter)
    {
        if ($novel->user_id !== Auth::id()) abort(403);
        if ($chapter->image) \Illuminate\Support\Facades\Storage::disk('public')->delete($chapter->image);
        $chapter->delete();
        return redirect()->route('writer.novels.chapters.index', $novel)->with('success', 'Chapter berhasil dihapus!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('chapters/content', 'public');
            $url = asset('storage/' . $path);
            return response()->json([
                'location' => $url,
                'url' => $url
            ]);
        }

        return response()->json(['error' => 'Gagal mengunggah gambar'], 500);
    }
}
