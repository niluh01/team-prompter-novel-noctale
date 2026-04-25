<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/3">
                    @if($novel->cover)
                        <img src="{{ Storage::url($novel->cover) }}" class="w-full rounded shadow">
                    @else
                        <div class="w-full aspect-[2/3] bg-gray-200 flex items-center justify-center text-gray-500 rounded">No Cover</div>
                    @endif
                </div>
                <div class="w-full md:w-2/3">
                    <h1 class="text-3xl font-bold mb-2">{{ $novel->title }}</h1>
                    <p class="text-gray-600 mb-4">Oleh <a href="{{ route('users.show', $novel->author) }}" class="font-bold hover:text-blue-600 border-b border-dashed border-gray-400 hover:border-blue-600 pb-0.5">{{ $novel->author->name }}</a> &bull; {{ number_format($novel->views) }} Views</p>
                    <div class="mb-6 flex gap-2">
                        @foreach($novel->genres as $genre)
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                    
                    <h3 class="font-semibold text-lg border-b pb-2 mb-2">Sinopsis</h3>
                    <p class="text-gray-700 whitespace-pre-line mb-6">{{ $novel->description }}</p>
                    
                    <!-- Interactive Rating and Bookmark Actions -->
                    @php
                        $avgRating = $novel->reviews()->avg('rating') ?? 0;
                        $totalReviews = $novel->reviews()->count();
                        $userReview = Auth::check() ? $novel->reviews()->where('user_id', Auth::id())->first() : null;
                        $isBookmarked = Auth::check() ? Auth::user()->bookmarks()->where('novel_id', $novel->id)->exists() : false;
                    @endphp
                    
                    <div class="mb-6 flex flex-col sm:flex-row items-center sm:justify-between gap-4 bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm flex-wrap" x-data="{ ratingModal: false }">
                        <div class="flex items-center gap-4 w-full sm:w-auto justify-center sm:justify-start">
                            <div class="whitespace-nowrap">
                                <span class="text-2xl font-bold text-yellow-500"><i class="fas fa-star"></i> {{ number_format($avgRating, 1) }}</span>
                                <span class="text-sm text-gray-500 ml-1 font-medium">({{ $totalReviews }} Penilaian)</span>
                            </div>
                            @auth
                                <button @click="ratingModal = true" class="text-blue-600 hover:text-blue-800 text-sm font-semibold underline transition whitespace-nowrap">
                                    {{ $userReview ? 'Ubah Rating' : 'Beri Rating' }}
                                </button>
                            @endauth
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-2 justify-center sm:justify-end w-full sm:w-auto">
                            @auth
                                <form action="{{ route('bookmarks.toggle', $novel) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 {{ $isBookmarked ? 'bg-gray-200 text-gray-800 hover:bg-gray-300' : 'bg-blue-600 text-white hover:bg-blue-700' }} font-bold rounded-lg transition shadow-sm whitespace-nowrap">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                        {{ $isBookmarked ? 'Tersimpan' : 'Simpan Novel' }}
                                    </button>
                                </form>

                                <button onclick="openReportModal('novel', {{ $novel->id }}, '{{ addslashes($novel->title) }}')" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold rounded-lg transition shadow-sm flex items-center gap-2 whitespace-nowrap">
                                    <i class="fas fa-exclamation-triangle"></i> Laporkan Novel
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-sm hover:bg-blue-700 transition whitespace-nowrap">Login untuk Menyimpan</a>
                            @endauth
                        </div>

                        <!-- Rating Modal -->
                        <div x-show="ratingModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" x-transition>
                            <div @click.away="ratingModal = false" class="bg-white rounded-2xl shadow-2xl p-8 w-96 transform transition-all">
                                <h3 class="text-xl font-bold mb-2 text-center text-gray-800">Beri Nilai Novel Ini</h3>
                                <p class="text-gray-500 text-sm text-center mb-8">Pilih 1 sampai 5 bintang. Penilaian akan langsung disimpan otomatis.</p>
                                
                                <div class="flex justify-center gap-3 mb-8" x-data="{ hoverRating: 0, setRating(star) { document.getElementById('rating-input').value = star; document.getElementById('rating-form').submit(); } }">
                                    <template x-for="star in 5" :key="star">
                                        <svg @click="setRating(star)" 
                                             @mouseenter="hoverRating = star" 
                                             @mouseleave="hoverRating = 0"
                                             :class="{'text-yellow-400': star <= hoverRating || (hoverRating === 0 && star <= {{ optional($userReview)->rating ?? 0 }}), 'text-gray-200': star > hoverRating && (hoverRating !== 0 || star > {{ optional($userReview)->rating ?? 0 }})}"
                                             class="w-12 h-12 cursor-pointer transition-colors duration-200 transform hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </template>
                                </div>
                                <form id="rating-form" action="{{ route('reviews.store', $novel) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="rating" id="rating-input" value="0">
                                </form>
                                <div class="text-center">
                                    <button @click="ratingModal = false" class="px-6 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-bold transition">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="font-semibold text-lg border-b pb-2 mb-2">Daftar Bab</h3>
                    <ul class="space-y-2">
                        @forelse($novel->chapters as $chapter)
                            <li>
                                <a href="{{ route('chapters.show', [$novel, $chapter]) }}" class="text-blue-600 hover:underline flex items-center gap-2 flex-wrap">
                                    <span>Bab {{ $chapter->chapter_number }}: {{ $chapter->title }}</span>
                                    @if($chapter->publish_status === 'draft')
                                        <span class="text-[10px] bg-gray-200 text-gray-600 font-bold px-2 py-0.5 rounded shadow-sm">DRAFT (Hanya Anda)</span>
                                    @elseif($chapter->publish_status === 'scheduled')
                                        <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded shadow-sm">
                                            TERJADWAL: {{ \Carbon\Carbon::parse($chapter->scheduled_at)->format('d M, H:i') }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @empty
                            <p class="text-gray-500 text-sm">Belum ada bab yang dipublikasikan.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
            
            <!-- COMMENTS SECTION -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <h3 class="font-bold text-xl mb-6">Diskusi & Ulasan</h3>

                @auth
                    <!-- Add Comment Form -->
                    <form action="{{ route('comments.store', $novel) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold flex-shrink-0 border border-blue-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <textarea name="content" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 resize-none" placeholder="Tulis komentar atau ulasan Anda tentang cerita ini..." required></textarea>
                                <div class="mt-2 text-right">
                                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">Kirim Komentar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="bg-gray-50 p-4 rounded-lg text-center mb-8 border border-gray-200">
                        <p class="text-gray-600">Silakan <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">Log in</a> atau <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold">Register</a> untuk ikut memberikan ulasan.</p>
                    </div>
                @endauth

                <!-- Comments List -->
                <div class="divide-y divide-gray-50">
                    @php
                        $novelComments = $novel->comments()
                            ->whereNull('chapter_id')
                            ->whereNull('parent_id')
                            ->with(['user', 'likes'])
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp

                    @forelse($novelComments as $comment)
                        @include('novels.partials.comment', ['comment' => $comment, 'isReply' => false, 'novel' => $novel])
                    @empty
                        <p class="text-center text-gray-500 py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100 flex flex-col items-center gap-2">
                             <span class="text-3xl text-gray-300"><i class="fas fa-comment-dots"></i></span>
                             Belum ada diskusi. Mulai ulasan pertama cerita ini!
                        </p>
                    @endforelse
                </div>
            </div>
            </div>
            
        </div>
    </div>
</x-app-layout>