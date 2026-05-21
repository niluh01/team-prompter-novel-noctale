<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NovelController extends Controller
{
    /**
     * Show the public novel details.
     */
    public function show(Novel $novel)
    {
        $isAuthor = Auth::id() === $novel->user_id;
        $isLive = $novel->publish_status === 'published';
        
        if (!$isLive && !$isAuthor) {
            abort(404);
        }
        
        // Load relationships conditionally. If author, load all chapters. If not, only published/scheduled ones that are due.
        if ($isAuthor) {
            $novel->load(['author', 'genres', 'chapters' => function ($q) {
                $q->orderBy('chapter_number', 'asc');
            }]);
        } else {
            $novel->load(['author', 'genres', 'chapters' => function ($q) {
                $q->published()->orderBy('chapter_number', 'asc');
            }]);
        }
        
        return view('novels.show', compact('novel'));
    }

    /**
     * Show the writer's list of novels.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'published');
        
        if (!in_array($tab, ['published', 'pending', 'draft', 'rejected'])) {
            $tab = 'published';
        }
        
        $query = $user->novels()->withCount('chapters');
        
        $countPublished = $user->novels()->where('publish_status', 'published')->count();
        $countPending = $user->novels()->where('publish_status', 'pending')->count();
        $countDraft = $user->novels()->where('publish_status', 'draft')->count();
        $countRejected = $user->novels()->where('publish_status', 'rejected')->count();
        
        $novels = $query->where('publish_status', $tab)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());
            
        return view('writer.novels.index', compact('novels', 'tab', 'countPublished', 'countPending', 'countDraft', 'countRejected'));
    }

    /**
     * Show the create novel form.
     */
    public function create()
    {
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('writer.novels.create', compact('genres'));
    }

    /**
     * Store a new novel.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:ongoing,completed,hiatus',
            'publish_status' => 'required|in:draft,published',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        // If author wants to publish it immediately, set status to pending for admin moderation.
        $publishStatus = $request->publish_status;
        if ($publishStatus === 'published') {
            $publishStatus = 'pending';
        }

        $novel = Auth::user()->novels()->create([
            'title' => $request->title,
            'description' => $request->description,
            'cover' => $coverPath,
            'status' => $request->status,
            'publish_status' => $publishStatus,
            'views' => 0,
        ]);

        if ($request->genres) {
            $novel->genres()->attach($request->genres);
        }

        $message = $publishStatus === 'pending' 
            ? 'Novel berhasil diajukan dan sedang menunggu persetujuan Admin!' 
            : 'Novel berhasil disimpan sebagai draf!';

        return redirect()->route('writer.novels.index', ['tab' => $publishStatus])->with('success', $message);
    }

    /**
     * Show the edit novel form.
     */
    public function edit(Novel $novel)
    {
        if ($novel->user_id !== Auth::id()) {
            abort(403);
        }

        $genres = Genre::orderBy('name', 'asc')->get();
        $novel->load('genres');

        return view('writer.novels.edit', compact('novel', 'genres'));
    }

    /**
     * Update the novel.
     */
    public function update(Request $request, Novel $novel)
    {
        if ($novel->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:ongoing,completed,hiatus',
            'publish_status' => 'required|in:draft,published',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $coverPath = $novel->cover;
        if ($request->hasFile('cover')) {
            if ($coverPath) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        $publishStatus = $request->publish_status;
        $rejectionReason = $novel->rejection_reason;
        
        if ($publishStatus === 'published') {
            // If the novel is currently a draft or rejected, transition it to pending
            if (in_array($novel->publish_status, ['draft', 'rejected'])) {
                $publishStatus = 'pending';
                $rejectionReason = null; // Clear old rejection reason
            } else {
                // If it is already pending or published, keep its current status
                $publishStatus = $novel->publish_status;
            }
        }

        $novel->update([
            'title' => $request->title,
            'description' => $request->description,
            'cover' => $coverPath,
            'status' => $request->status,
            'publish_status' => $publishStatus,
            'rejection_reason' => $rejectionReason,
        ]);

        if ($request->genres) {
            $novel->genres()->sync($request->genres);
        } else {
            $novel->genres()->detach();
        }

        $message = 'Novel berhasil diperbarui!';
        return redirect()->route('writer.novels.index', ['tab' => $publishStatus])->with('success', $message);
    }

    /**
     * Delete the novel and its dependencies.
     */
    public function destroy(Novel $novel)
    {
        if ($novel->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete cover image from storage
        if ($novel->cover) {
            Storage::disk('public')->delete($novel->cover);
        }

        // Delete all chapter images
        foreach ($novel->chapters as $chapter) {
            if ($chapter->image) {
                Storage::disk('public')->delete($chapter->image);
            }
        }

        // Detach genres and delete the novel (cascade deletes comments/reviews/chapters in db or Eloquent handles it)
        $novel->genres()->detach();
        $novel->delete();

        return redirect()->route('writer.novels.index')->with('success', 'Novel beserta semua bab di dalamnya berhasil dihapus!');
    }
}
