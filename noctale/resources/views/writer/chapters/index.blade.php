<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight line-clamp-1">Kelola Bab: {{ $novel->title }}</h2>
            <a href="{{ route('writer.novels.chapters.create', $novel) }}" class="self-start sm:self-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 whitespace-nowrap transition">
                + Tambah Bab Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats & Search --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Total Bab: <span class="font-semibold text-gray-800">{{ $novel->chapters()->count() }}</span></div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="text-sm text-gray-500">Total Views: <span class="font-semibold text-gray-800">{{ number_format($novel->chapters()->sum('views')) }} kali</span></div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center sm:col-span-2 md:col-span-1">
                    <form action="{{ route('writer.novels.chapters.index', $novel) }}" method="GET" class="w-full flex">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul bab..." class="w-full border-gray-300 rounded-l-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-gray-100 border border-l-0 border-gray-300 px-3 rounded-r-lg hover:bg-gray-200">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabs (scrollable on mobile) --}}
            <div class="mb-4 border-b border-gray-200 overflow-x-auto">
                <div class="flex space-x-5 min-w-max">
                    <a href="{{ route('writer.novels.chapters.index', $novel) }}?status=all&search={{ $search }}" class="pb-2 px-1 text-sm whitespace-nowrap {{ $status === 'all' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">Semua Bab</a>
                    <a href="{{ route('writer.novels.chapters.index', $novel) }}?status=published&search={{ $search }}" class="pb-2 px-1 text-sm whitespace-nowrap {{ $status === 'published' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">Publis</a>
                    <a href="{{ route('writer.novels.chapters.index', $novel) }}?status=draft&search={{ $search }}" class="pb-2 px-1 text-sm whitespace-nowrap {{ $status === 'draft' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">Draft</a>
                    <a href="{{ route('writer.novels.chapters.index', $novel) }}?status=scheduled&search={{ $search }}" class="pb-2 px-1 text-sm whitespace-nowrap {{ $status === 'scheduled' ? 'border-b-2 border-blue-600 text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700' }}">Terjadwal</a>
                </div>
            </div>

            {{-- Table (scrollable on mobile) --}}
            <div class="border border-gray-200 bg-white rounded-lg shadow-sm overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[540px]">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Bab Ke</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Bab</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center whitespace-nowrap">Status</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Total Views</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chapters as $chapter)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700">{{ $chapter->chapter_number }}</td>
                                <td class="p-4 text-gray-900">{{ $chapter->title }}</td>
                                <td class="p-4 text-center">
                                    @if($chapter->publish_status === 'published')
                                        <span class="bg-green-100 text-green-800 text-[10px] px-2 py-1 rounded-full shadow-sm font-bold border border-green-200 whitespace-nowrap">Publis</span>
                                    @elseif($chapter->publish_status === 'scheduled')
                                        <span class="bg-blue-100 text-blue-800 text-[10px] px-2 py-1 rounded-full shadow-sm font-bold border border-blue-200 whitespace-nowrap">Terjadwal</span>
                                        <div class="text-[10px] text-gray-500 mt-1 whitespace-nowrap">{{ \Carbon\Carbon::parse($chapter->scheduled_at)->format('d M Y, H:i') }}</div>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 text-[10px] px-2 py-1 rounded-full shadow-sm font-bold border border-gray-200 whitespace-nowrap">Draft</span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-600 whitespace-nowrap">{{ $chapter->views }} kali dibaca</td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('writer.novels.chapters.edit', [$novel, $chapter]) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit Bab">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('writer.novels.chapters.destroy', [$novel, $chapter]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus bab ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus Bab">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    <p class="text-3xl mb-2"><i class="fas fa-file-alt"></i></p>
                                    <p class="text-sm">Belum ada bab yang dirilis.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>