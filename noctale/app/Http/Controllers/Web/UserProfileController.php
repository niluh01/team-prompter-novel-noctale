<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $publishedNovelsQuery = $user->novels()
            ->where('publish_status', 'published')
            ->withCount('chapters');
            
        $totalNovels = $publishedNovelsQuery->count();
        $totalViews = $user->novels()->where('publish_status', 'published')->sum('views');
        
        $novels = $publishedNovelsQuery->orderBy('created_at', 'desc')->paginate(12);
        
        return view('profile.show', compact('user', 'totalNovels', 'totalViews', 'novels'));
    }
}
