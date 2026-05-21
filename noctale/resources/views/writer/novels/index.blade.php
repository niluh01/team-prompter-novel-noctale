<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6 bg-white p-5 sm:p-6 rounded-lg shadow-sm border border-gray-100">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Koleksi Karya Anda</h2>
                    <p class="text-gray-500 text-sm mt-1">Buat, terbitkan, dan kontrol semua riwayat novel Anda dari sini.</p>
                </div>
                <a href="{{ route('writer.novels.create') }}" class="self-start sm:self-auto px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition transform hover:-translate-y-0.5 whitespace-nowrap text-sm sm:text-base">
                    + Buat Novel Baru
                </a>
            </div>

            {{-- Tabs Navigation (scrollable on mobile) --}}
            <div class="mb-4 border-b border-gray-200 overflow-x-auto">
                <div class="flex space-x-5 min-w-max">
                    <a href="{{ route('writer.novels.index', ['tab' => 'published']) }}" class="pb-2 text-xs sm:text-sm font-bold whitespace-nowrap {{ $tab === 'published' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Aktif / Publik ({{ $countPublished ?? 0 }})
                    </a>
                    <a href="{{ route('writer.novels.index', ['tab' => 'pending']) }}" class="pb-2 text-xs sm:text-sm font-bold whitespace-nowrap {{ $tab === 'pending' ? 'border-b-2 border-yellow-500 text-yellow-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Menunggu Persetujuan ({{ $countPending ?? 0 }})
                    </a>
                    <a href="{{ route('writer.novels.index', ['tab' => 'draft']) }}" class="pb-2 text-xs sm:text-sm font-bold whitespace-nowrap {{ $tab === 'draft' ? 'border-b-2 border-gray-600 text-gray-800' : 'text-gray-500 hover:text-gray-700' }}">
                        Draft Disembunyikan ({{ $countDraft ?? 0 }})
                    </a>
                    <a href="{{ route('writer.novels.index', ['tab' => 'rejected']) }}" class="pb-2 text-xs sm:text-sm font-bold whitespace-nowrap flex items-center gap-1 {{ $tab === 'rejected' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Perlu Revisi ({{ $countRejected ?? 0 }})</span>
                    </a>
                </div>
            </div>

            {{-- Table (scrollable on mobile) --}}
            <div class="border border-gray-200 bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[520px]">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Cover</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Total Bab</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($novels as $novel)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 w-20">
                                    @if($novel->cover)
                                        <img src="{{ Storage::url($novel->cover) }}" class="w-14 h-[4.5rem] object-cover rounded shadow-sm">
                                    @else
                                        <div class="w-14 h-[4.5rem] bg-gray-200 text-xs text-center flex items-center justify-center text-gray-400 rounded">No Cover</div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-900">{{ $novel->title }}</div>
                                    @if($novel->publish_status === 'rejected' && $novel->rejection_reason)
                                        <div class="mt-2 p-2 bg-red-50 border border-red-100 rounded text-[10px] text-red-700 leading-relaxed italic">
                                            <strong>Feedback Admin:</strong> "{{ $novel->rejection_reason }}"
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 whitespace-nowrap text-gray-700">{{ $novel->chapters_count ?? 0 }} Bab</td>
                                <td class="p-4">
                                    @if($novel->publish_status === 'published')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-[10px] font-bold border border-green-200 whitespace-nowrap">PUBLIK</span>
                                    @elseif($novel->publish_status === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-[10px] font-bold border border-yellow-200 whitespace-nowrap">MENUNGGU</span>
                                    @elseif($novel->publish_status === 'rejected')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-[10px] font-bold border border-red-200 whitespace-nowrap">DITOLAK</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-bold border border-gray-200 whitespace-nowrap">DRAFT</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap space-x-1">
                                    <a href="{{ route('writer.novels.chapters.index', $novel) }}" class="text-green-600 hover:underline font-semibold text-sm">Kelola Bab</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('writer.novels.edit', $novel) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('writer.novels.destroy', $novel) }}" method="POST" class="inline" onsubmit="return confirm('Hapus novel ini beserta seluruh bab?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <p class="text-sm">Belum ada karya novel. Silakan buat yang baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $novels->links() }}</div>
        </div>
    </div>
</x-app-layout>