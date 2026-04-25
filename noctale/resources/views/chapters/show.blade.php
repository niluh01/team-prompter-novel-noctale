<x-app-layout>
    <div x-data="{ 
            fontSize: Alpine.store ? (localStorage.getItem('noctale_fontsize') ? parseInt(localStorage.getItem('noctale_fontsize')) : 18) : 18, 
            theme: localStorage.getItem('noctale_theme') || 'light',
            fontStyle: localStorage.getItem('noctale_font') || 'font-serif',
            showSettings: false
         }" 
         x-init="
            const updateBodyBg = (t) => {
                document.body.style.backgroundColor = t === 'dark' ? '#121212' : (t === 'sepia' ? '#F4ECD8' : '#f9fafb');
            };
            updateBodyBg(theme);
            $watch('fontSize', val => localStorage.setItem('noctale_fontsize', val)); 
            $watch('theme', val => { 
                localStorage.setItem('noctale_theme', val); 
                updateBodyBg(val); 
            }); 
            $watch('fontStyle', val => localStorage.setItem('noctale_font', val));
         "
         :class="{
             'bg-gray-50 text-gray-900': theme === 'light',
             'bg-[#121212] text-gray-300': theme === 'dark',
             'bg-[#F4ECD8] text-[#5C4B51]': theme === 'sepia'
         }"
         class="min-h-screen pt-4 pb-12 transition-colors duration-300">
         
        <!-- Papan Navigasi & Pengaturan Terapung -->
        <div class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-200 py-3 shadow-sm" style="z-index: 9999;"
             :class="{
                'bg-white/80 border-gray-200': theme === 'light',
                'bg-gray-900/90 border-gray-800 text-gray-200': theme === 'dark',
                'bg-[#EFE5CE]/90 border-[#D8C7A8] text-[#5C4B51]': theme === 'sepia'
             }">
            <div class="max-w-4xl mx-auto px-4 flex justify-between items-center h-10">
                <a href="{{ route('novels.show', $novel) }}" class="flex items-center gap-2 hover:opacity-70 transition font-bold" :class="{'text-blue-600': theme === 'light', 'text-blue-400': theme === 'dark', 'text-[#8A6A4B]': theme === 'sepia'}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span class="hidden sm:inline">{{ $novel->title }}</span>
                </a>
                
                <!-- Tombol Pengaturan -->
                <button @click="showSettings = !showSettings" class="px-3 py-1.5 rounded flex items-center gap-2 hover:bg-gray-500/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    <span class="text-sm font-semibold">Tampilan</span>
                </button>
            </div>

            <!-- Panel Pengaturan Tampilan Dropdown -->
            <div x-show="showSettings" x-transition.opacity class="absolute top-16 right-4 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 w-80 p-5 rounded-xl shadow-2xl border" style="z-index: 10000;"
                 @click.away="showSettings = false"
                 :class="{
                    'bg-white border-gray-200': theme === 'light' || theme === 'sepia',
                    'bg-gray-800 border-gray-700 text-gray-200': theme === 'dark'
                 }">
                <!-- Ukuran Teks -->
                <div class="mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 block">Ukuran Teks</span>
                    <div class="flex items-center gap-4 bg-gray-500/5 p-1 rounded-lg">
                        <button @click="fontSize = Math.max(12, fontSize - 2)" class="flex-1 py-1.5 hover:bg-gray-500/10 rounded font-bold text-lg">A-</button>
                        <span class="font-semibold text-sm" x-text="fontSize + 'px'"></span>
                        <button @click="fontSize = Math.min(32, fontSize + 2)" class="flex-1 py-1.5 hover:bg-gray-500/10 rounded font-bold text-lg">A+</button>
                    </div>
                </div>

                <!-- Font Style -->
                <div class="mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 block">Jenis Huruf</span>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="fontStyle = 'font-sans'" class="py-2 border rounded-lg hover:bg-gray-500/5 font-sans" :class="fontStyle === 'font-sans' ? 'border-blue-500 ring-1 ring-blue-500' : 'border-gray-500/20'">Sans-Serif</button>
                        <button @click="fontStyle = 'font-serif'" class="py-2 border rounded-lg hover:bg-gray-500/5 font-serif" :class="fontStyle === 'font-serif' ? 'border-blue-500 ring-1 ring-blue-500' : 'border-gray-500/20'">Serif</button>
                    </div>
                </div>

                <!-- Tema Latar -->
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 block">Warna Latar</span>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="theme = 'light'" class="py-2.5 rounded-lg bg-white border text-gray-900 font-medium" :class="theme === 'light' ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200'">Terang</button>
                        <button @click="theme = 'sepia'" class="py-2.5 rounded-lg bg-[#F4ECD8] border text-[#5C4B51] font-medium" :class="theme === 'sepia' ? 'border-yellow-600 ring-2 ring-yellow-200' : 'border-[#D8C7A8]'">Sepia</button>
                        <button @click="theme = 'dark'" class="py-2.5 rounded-lg bg-gray-900 border text-gray-300 font-medium" :class="theme === 'dark' ? 'border-blue-500 ring-2 ring-blue-900' : 'border-gray-700'">Gelap</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="mb-10 text-center px-4">
                @if($chapter->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $chapter->image) }}" class="w-full max-h-[400px] object-cover rounded-2xl shadow-lg border border-gray-200">
                    </div>
                @endif
                <h1 class="text-2xl sm:text-4xl font-extrabold mt-2 leading-tight">Bab {{ $chapter->chapter_number }}: {{ $chapter->title }}</h1>
            </div>
            
            <!-- Konten Cerita dengan Dinamis Style -->
            <div class="px-6 py-8 sm:px-10 rounded-2xl shadow-sm transition-all duration-300"
                 :class="[
                     fontStyle,
                     {
                         'bg-white border border-gray-100': theme === 'light',
                         'bg-[#1A1A1A] border border-gray-800 shadow-none': theme === 'dark',
                         'bg-[#FBF6EE] border border-[#EBE0C5]': theme === 'sepia'
                     }
                 ]">
                <div :style="`font-size: ${fontSize}px; line-height: 1.8;`" class="prose prose-slate max-w-none dark:prose-invert relative">
                    @auth
                        {!! $chapter->content !!}
                    @else
                        <div class="relative">
                            <div class="mask-content">
                                {!! Str::limit($chapter->content, 600) !!}
                            </div>
                            <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none" 
                                 x-show="theme === 'light'"></div>
                            <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-[#1A1A1A] via-[#1A1A1A]/80 to-transparent pointer-events-none" 
                                 x-show="theme === 'dark'"></div>
                            <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-[#FBF6EE] via-[#FBF6EE]/80 to-transparent pointer-events-none" 
                                 x-show="theme === 'sepia'"></div>
                        </div>

                        <div class="mt-10 p-8 rounded-3xl text-center border-2 border-dashed transition-colors"
                             :class="{
                                'bg-blue-50/50 border-blue-200': theme === 'light',
                                'bg-blue-900/10 border-blue-900/30': theme === 'dark',
                                'bg-orange-100/30 border-orange-200': theme === 'sepia'
                             }">
                            <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg rotate-3 group-hover:rotate-0 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h4 class="text-2xl font-black mb-3" :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Lanjutkan Membaca?</h4>
                            <p class="text-lg opacity-80 mb-8 max-w-md mx-auto">Masuk ke akunmu untuk membuka seluruh isi cerita dan bergabung dengan ribuan pembaca lainnya.</p>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                                <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition shadow-xl shadow-blue-600/20 transform hover:-translate-y-1 active:translate-y-0">
                                    Login Sekarang
                                </a>
                                <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-4 font-bold rounded-2xl border-2 transition transform hover:-translate-y-1 active:translate-y-0"
                                   :class="{
                                       'bg-white text-blue-600 border-blue-600 hover:bg-blue-50': theme === 'light',
                                       'bg-gray-800 text-white border-gray-700 hover:bg-gray-750': theme === 'dark',
                                       'bg-[#EFE5CE] text-[#8A6A4B] border-[#D8C7A8] hover:bg-[#E5DBC0]': theme === 'sepia'
                                   }">
                                    Daftar Gratis
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
            
            <!-- Navigasi Bawah -->
            <div class="mt-12 flex justify-between items-center px-2">
                @if($prevChapter)
                    <a href="{{ route('chapters.show', [$novel, $prevChapter]) }}" class="px-5 py-3 rounded-xl font-bold flex items-center gap-2 transition"
                       :class="theme === 'dark' ? 'bg-gray-800 hover:bg-gray-700 text-gray-300' : 'bg-gray-200 hover:bg-gray-300 text-gray-800'">
                        <span>&larr;</span> Sebelumnya
                    </a>
                @else
                    <div></div>
                @endif
                
                @if($nextChapter)
                    <a href="{{ route('chapters.show', [$novel, $nextChapter]) }}" class="px-5 py-3 rounded-xl font-bold flex items-center gap-2 transition"
                       :class="theme === 'dark' ? 'bg-blue-600 hover:bg-blue-500 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white'">
                        Selanjutnya <span>&rarr;</span>
                    </a>
                @else
                    <div></div>
                @endif
            </div>

            <!-- Bagian Komentar Bab -->
            <div class="mt-20 border-t pt-10" :class="theme === 'dark' ? 'border-gray-800' : 'border-gray-200'">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black flex items-center gap-3">
                        💬 Diskusi Bab
                        <span class="text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded-full">{{ $totalComments }}</span>
                    </h3>
                </div>

                @auth
                    <!-- Form Tambah Komentar -->
                    <form action="{{ route('comments.store', $novel) }}" method="POST" class="mb-10">
                        @csrf
                        <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold flex-shrink-0 border-2 border-white shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <textarea name="content" rows="3" class="w-full border-gray-200 rounded-2xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 resize-none p-4 text-sm transition-all" :class="theme === 'dark' ? 'bg-gray-800 border-gray-700 text-gray-200' : 'bg-white'" placeholder="Apa pendapatmu tentang bab ini? Tulis komentarmu..." required></textarea>
                                <div class="mt-3 text-right">
                                    <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/20 transform hover:-translate-y-0.5">Kirim Komentar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="bg-gray-500/5 p-6 rounded-2xl text-center mb-10 border border-gray-500/10">
                        <p class="text-gray-500 font-medium">Mau ikut berdiskusi? <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">Login</a> sekarang!</p>
                    </div>
                @endauth

                <!-- Daftar Komentar -->
                <div class="space-y-6">
                    @forelse($comments as $comment)
                        @include('novels.partials.comment', ['comment' => $comment, 'isReply' => false, 'novel' => $novel, 'chapter' => $chapter])
                    @empty
                        <div class="text-center py-16 bg-gray-500/5 rounded-3xl border-2 border-dashed border-gray-500/10">
                            <span class="text-4xl mb-4 block">😶</span>
                            <p class="text-gray-500 font-medium">Belum ada komentar di bab ini. Jadilah yang pertama!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>