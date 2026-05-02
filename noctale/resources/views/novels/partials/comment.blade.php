<div class="flex gap-4 {{ $isReply ? 'mt-4 ml-8' : 'mt-8' }}">
    <div class="{{ $isReply ? 'w-8 h-8 text-xs' : 'w-10 h-10' }} rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold flex-shrink-0">
        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
    </div>
    <div class="flex-1">
        <div class="{{ $isReply ? 'bg-white p-3 shadow-sm border-gray-100' : 'bg-gray-50 p-4 border-gray-50' }} rounded-xl border">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-bold text-gray-900 {{ $isReply ? 'text-xs' : 'text-sm' }}">{{ $comment->user->name }}</h4>
                <span class="text-[10px] text-gray-500 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            @if($comment->is_deleted_by_admin)
                <p class="text-gray-400 {{ $isReply ? 'text-[10px]' : 'text-xs' }} italic py-1 border-l-2 border-gray-200 pl-3 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    Komentar ini telah dihapus oleh Admin
                </p>
            @else
                <p class="text-gray-700 {{ $isReply ? 'text-xs' : 'text-sm' }} whitespace-pre-line leading-relaxed">{{ $comment->content }}</p>
            @endif
        </div>
        
        <div class="mt-2 text-[10px] text-gray-500 flex gap-4 ml-1 items-center">
            @auth
                @php $isLiked = $comment->likes()->where('user_id', Auth::id())->exists(); @endphp
                <form action="{{ route('comments.like', $comment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="{{ $isLiked ? 'text-blue-600 font-black' : 'hover:text-blue-600 font-bold' }} flex items-center gap-1 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 {{ $isLiked ? 'fill-current' : '' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>
                        {{ $comment->likes()->count() > 0 ? $comment->likes()->count() : '' }} Suka
                    </button>
                </form>
            @else
                <span class="font-bold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                    </svg>
                    {{ $comment->likes()->count() }} Suka
                </span>
            @endauth

            @if(!$comment->is_deleted_by_admin)
                <button class="hover:text-blue-600 font-bold transition flex items-center gap-1" onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                    Balas
                </button>
            @endif
            
            @auth
                @if(!$comment->is_deleted_by_admin)
                    <button class="hover:text-red-600 font-bold transition flex items-center gap-1" onclick="openReportModal('comment', {{ $comment->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a14.451 14.451 0 012.228-.428 14.452 14.452 0 012.228.428L13 15m0 0l2.77-.693a14.451 14.451 0 012.228-.428 14.452 14.452 0 012.228.428L21 15V6l-2.77.693a14.451 14.451 0 00-2.228.428 14.452 14.452 0 00-2.228-.428L13 6m0 0l-2.77.693a14.451 14.451 0 01-2.228.428 14.452 14.452 0 01-2.228-.428L3 6" />
                        </svg>
                        Lapor
                    </button>
                @endif

                @if(Auth::user()->isAdmin() && !$comment->is_deleted_by_admin)
                    <form action="{{ route('admin.comments.delete', $comment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-700 font-black hover:underline transition uppercase tracking-tighter text-[9px]">Hapus oleh Admin</button>
                    </form>
                @endif
            @endauth
        </div>

        <!-- Reply Form -->
        @auth
        <form action="{{ route('comments.store', $novel) }}" method="POST" id="reply-form-{{ $comment->id }}" class="hidden mt-4 mb-2 flex gap-3 animate-slideDown">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            @if(isset($chapter))
                <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
            @elseif($comment->chapter_id)
                <input type="hidden" name="chapter_id" value="{{ $comment->chapter_id }}">
            @endif
            <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-600 font-bold text-[10px]">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <textarea name="content" rows="1" class="w-full text-xs border-gray-200 rounded-xl shadow-sm focus:border-blue-500 py-2 resize-none transition-all placeholder:text-gray-300" placeholder="Berikan balasan Anda..." required></textarea>
                <div class="mt-1 flex justify-end">
                    <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black rounded-lg hover:bg-blue-700 transition shadow-sm">Kirim Balasan</button>
                </div>
            </div>
        </form>
        @endauth

        <!-- Render Nested Replies Recursively -->
        @php
            $nestedReplies = $comment->replies()->with('user')->orderBy('created_at', 'asc')->get();
        @endphp
        @if($nestedReplies->count() > 0)
            <div class="border-l-2 border-gray-100">
                @foreach($nestedReplies as $child)
                    @include('novels.partials.comment', ['comment' => $child, 'isReply' => true, 'novel' => $novel])
                @endforeach
            </div>
        @endif
    </div>
</div>
