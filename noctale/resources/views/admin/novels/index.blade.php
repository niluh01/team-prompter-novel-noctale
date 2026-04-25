<x-app-layout>
    <div class="space-y-6">
        <h2 class="text-3xl font-extrabold text-gray-900 border-b-2 border-red-500 pb-2 mb-6 inline-block">Manajemen Karya (Approval)</h2>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pending Approval Section -->
        <div class="bg-yellow-50 border border-yellow-200 shadow-md rounded-xl p-6 mb-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-yellow-400 opacity-10 rounded-full -mr-10 -mt-10 blur-xl"></div>
            
            <h3 class="text-xl font-bold text-yellow-800 mb-4 flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                </span>
                Menunggu Persetujuan Publikasi ({{ $pendingNovels->count() }})
            </h3>
            
            @if($pendingNovels->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pendingNovels as $pending)
                        <div class="bg-white border border-yellow-300 rounded-xl overflow-hidden shadow-md flex flex-col hover:shadow-xl transition-all duration-300 group">
                            <!-- Top Section: Cover & Info side-by-side -->
                            <div class="flex p-4 gap-4 bg-white">
                                <!-- Small Cover -->
                                <div class="w-20 h-28 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden shadow-sm border border-gray-200">
                                    @if($pending->cover)
                                        <img src="{{ Storage::url($pending->cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50 text-[10px] uppercase font-black italic">NA</div>
                                    @endif
                                </div>

                                <!-- Metadata -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-black text-gray-900 text-base leading-tight line-clamp-2 mb-1">{{ $pending->title }}</h4>
                                    <div class="text-[10px] font-bold text-blue-600 mb-2 uppercase tracking-wide">Oleh: {{ $pending->author->name }}</div>
                                    
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($pending->genres->take(2) as $genre)
                                            <span class="text-[8px] bg-gray-50 text-gray-500 px-1.5 py-0.5 rounded border border-gray-200 uppercase font-bold">{{ $genre->name }}</span>
                                        @endforeach
                                        <span class="text-[8px] bg-yellow-50 text-yellow-700 px-1.5 py-0.5 rounded border border-yellow-200 font-bold uppercase">{{ $pending->chapters_count ?? 0 }} BAB</span>
                                    </div>
                                    
                                    <p class="text-[10px] text-gray-500 line-clamp-2 italic leading-relaxed">{{ $pending->description }}</p>
                                </div>
                            </div>

                            <!-- Bottom Section: Actions full width -->
                            <div class="flex items-center gap-2 p-3 bg-gray-50 border-t border-yellow-100">
                                <form action="{{ route('admin.novels.update', $pending) }}" method="POST" class="flex-grow">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-sm text-[11px] transition-all uppercase tracking-widest border border-emerald-500 hover:scale-[1.02] active:scale-95">Terima</button>
                                </form>
                                
                                <button type="button" 
                                    onclick="openRejectModal({{ $pending->id }}, '{{ addslashes($pending->title) }}')"
                                    class="flex-grow px-4 py-2 bg-white hover:bg-red-50 text-red-600 font-bold rounded-lg shadow-sm text-[11px] transition-all uppercase tracking-widest border border-red-200 hover:border-red-400">
                                    Tolak / Revisi
                                </button>

                                <a href="{{ route('novels.show', $pending) }}" target="_blank" class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg border border-gray-300 transition shadow-sm" title="Preview Halaman">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-yellow-700 bg-yellow-100/50 rounded-lg border border-yellow-200 border-dashed">
                    Tidak ada novel baru yang menunggu izin perilisan. Anda bisa bersantai!
                </div>
            @endif
        </div>

        <!-- Published Novels Header & Search -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4 mt-12">
            <h3 class="text-xl font-bold text-gray-800 border-l-4 border-emerald-500 pl-3">Daftar Karya Aktif Beredar</h3>
        </div>

        <div class="bg-white shadow-sm border border-gray-100 rounded-2xl mb-6 overflow-hidden">
            <div class="p-4 bg-gray-50/20">
                <form action="{{ route('admin.novels.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
                    <!-- Search Field (Karya/Penulis) -->
                    <div class="relative flex-[4] w-full group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 z-10 group-focus-within:text-red-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full border-gray-200 bg-white rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all font-medium placeholder-gray-400 relative z-0 shadow-inner" 
                               placeholder="Cari judul novel atau nama penulis...">
                    </div>
                    
                    <!-- Genre Filter -->
                    <div class="w-full md:w-56 flex items-center gap-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest hidden lg:inline">Genre:</span>
                        <select name="genre" onchange="this.form.submit()" 
                                class="flex-1 border-gray-200 bg-white rounded-xl py-2.5 px-4 text-sm font-bold focus:ring-4 focus:ring-red-500/10 focus:border-red-500 cursor-pointer shadow-inner appearance-none">
                            <option value="">Semua Aliran</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-black text-white font-black rounded-xl hover:bg-red-600 transition-all shadow-md active:scale-95 text-[10px] uppercase tracking-widest">
                            Cari
                        </button>
                        @if(request('search') || request('genre'))
                            <a href="{{ route('admin.novels.index') }}" 
                               class="px-4 py-2.5 bg-gray-100 text-gray-400 font-bold rounded-xl hover:bg-gray-200 transition-all text-sm flex items-center justify-center border border-gray-200" 
                               title="Reset">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- List Layout (Premium Cards) -->
        <div class="space-y-4">
            @forelse($publishedNovels as $published)
                <div class="bg-white border border-gray-100 rounded-3xl p-5 hover:shadow-2xl hover:shadow-blue-500/5 transition-all duration-500 group relative overflow-hidden flex flex-col md:flex-row items-center gap-6">
                    <!-- Subtle Glow Effect -->
                    <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                    
                    <!-- Cover Art (Left) -->
                    <div class="w-24 h-32 flex-shrink-0 relative">
                        <div class="absolute inset-0 bg-emerald-500 translate-x-1 translate-y-1 rounded-2xl opacity-20 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform duration-500"></div>
                        <div class="relative w-full h-full bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden shadow-lg shadow-gray-200/50">
                            @if($published->cover)
                                <img src="{{ Storage::url($published->cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-gray-300 font-black uppercase italic">No Cover</div>
                            @endif
                        </div>
                    </div>

                    <!-- Details (Center-Left) -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col md:flex-row md:items-center gap-3 mb-3">
                            <h4 class="text-xl font-black text-gray-900 group-hover:text-blue-600 transition-colors truncate max-w-sm">{{ $published->title }}</h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-tighter border border-emerald-100 shadow-sm animate-pulse-slow">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span> Aktif Beredar
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold border-2 border-white shadow-sm ring-1 ring-gray-100">
                                {{ strtoupper(substr($published->author->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-xs font-black text-gray-800">Penulis: <span class="text-blue-600 underline cursor-pointer">{{ $published->author->name }}</span></div>
                                <div class="text-[10px] text-gray-400 font-medium">Terdaftar pada {{ $published->created_at->format('d M Y') }}</div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach($published->genres->take(4) as $genre)
                                <span class="text-[9px] bg-gray-50 text-gray-500 px-3 py-1 border border-gray-100 rounded-lg font-bold shadow-inner">{{ $genre->name }}</span>
                            @endforeach
                            @if($published->genres->count() > 4)
                                <span class="text-[9px] text-gray-300 self-center">+{{ $published->genres->count() - 4 }} Genre lainnya</span>
                            @endif
                        </div>
                    </div>

                    <!-- Stats & Health (Center-Right) -->
                    <div class="flex flex-row md:flex-col gap-4 border-l-0 md:border-l border-gray-100 pl-0 md:pl-8 py-2">
                        <div class="text-center md:text-left">
                            <div class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Total Bab</div>
                            <div class="text-2xl font-black text-blue-600 tracking-tighter">{{ $published->chapters_count ?? 0 }}</div>
                        </div>
                        <div class="text-center md:text-left">
                            <div class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Populasitas</div>
                            <div class="flex items-center gap-1">
                                <span class="text-sm font-black text-gray-700">{{ number_format($published->views) }}</span>
                                <span class="text-[8px] text-gray-400 font-bold">VIEWS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions (Right) -->
                    <div class="flex-shrink-0 flex items-center gap-2 pl-0 md:pl-4">
                        <a href="{{ route('novels.show', $published) }}" target="_blank" class="p-3 bg-gray-50 hover:bg-white text-gray-400 hover:text-blue-600 rounded-2xl border border-gray-100 transition-all shadow-sm hover:shadow-md active:scale-95" title="Preview Halaman">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        <button type="button" 
                                onclick="openForceDeleteModal({{ $published->id }}, '{{ addslashes($published->title) }}')"
                                class="flex items-center justify-center p-3 bg-red-50 hover:bg-black text-red-500 hover:text-white rounded-2xl border border-red-100 transition-all shadow-sm hover:shadow-red-500/20 active:scale-95 group/btn" title="Hapus Paksa">
                            <svg class="w-5 h-5 group-hover/btn:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="px-6 py-20 text-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl">
                    <div class="text-5xl mb-4 opacity-50"><i class="fas fa-book-open"></i></div>
                    <div class="text-xl font-black text-gray-400">Belum ada karya beredar di platform.</div>
                    <p class="text-gray-300 text-sm mt-1">Cek bagian pengajuan untuk menyetujui novel baru.</p>
                </div>
            @endforelse
        </div>

        @if($publishedNovels->hasPages())
            <div class="mt-8">
                {{ $publishedNovels->links() }}
            </div>
        @endif

    </div>

    <!-- Reject Reason Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-[110] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in border border-gray-100">
            <h3 class="text-xl font-black text-gray-900 mb-2 flex items-center gap-2">
                <span class="text-red-500"><i class="fas fa-times"></i></span> Tolak Publikasi
            </h3>
            <p id="rejectModalNovelTitle" class="text-sm text-gray-500 mb-4 font-bold"></p>
            
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="reject">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-[10px]">Alasan Penolakan / Feedback Revisi</label>
                    <textarea name="rejection_reason" rows="4" class="w-full border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 text-sm placeholder-gray-400 shadow-sm" placeholder="Jelaskan alasan penolakan agar penulis bisa merevisi karyanya..." required></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl shadow-lg shadow-red-600/20 transition-all uppercase tracking-widest text-xs">Konfirmasi Tolak</button>
                    <button type="button" onclick="closeRejectModal()" class="px-6 py-3 text-gray-400 font-bold hover:text-gray-600 transition-colors uppercase text-xs">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Force Delete Modal -->
    <div id="forceDeleteModal" class="hidden fixed inset-0 z-[110] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 animate-scale-in border border-red-50">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                <span class="text-3xl text-red-500"><i class="fas fa-exclamation-triangle"></i></span>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-2 text-center uppercase tracking-tighter">Eksekusi Hapus Paksa</h3>
            <p id="deleteModalNovelTitle" class="text-sm text-gray-500 mb-6 font-bold text-center italic"></p>
            
            <form id="forceDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-6">
                    <label class="block text-xs font-black text-gray-400 mb-2 uppercase tracking-widest">Alasan Penghapusan (Akan dikirim ke Penulis)</label>
                    <textarea name="reason" rows="3" class="w-full border-gray-200 bg-gray-50 rounded-2xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500 text-sm placeholder-gray-300 shadow-inner p-4" placeholder="Contoh: Mengandung unsur plagiarisme atau pelanggaran hak cipta..." required></textarea>
                </div>
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-4 bg-black hover:bg-red-600 text-white font-black rounded-2xl shadow-xl shadow-red-600/10 transition-all uppercase tracking-widest text-xs active:scale-95">Konfirmasi & Hapus Permanen</button>
                    <button type="button" onclick="closeForceDeleteModal()" class="w-full py-3 text-gray-400 font-bold hover:text-gray-600 transition-colors uppercase text-[10px] tracking-widest text-center">Batalkan Eksekusi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id, title) {
            const modal = document.getElementById('rejectModal');
            const titleEl = document.getElementById('rejectModalNovelTitle');
            const form = document.getElementById('rejectForm');
            titleEl.textContent = 'Karya: ' + title;
            form.action = `/admin/novels/${id}`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openForceDeleteModal(id, title) {
            const modal = document.getElementById('forceDeleteModal');
            const titleEl = document.getElementById('deleteModalNovelTitle');
            const form = document.getElementById('forceDeleteForm');
            titleEl.textContent = '"' + title + '"';
            form.action = `/admin/novels/${id}`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeForceDeleteModal() {
            document.getElementById('forceDeleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</x-app-layout>
