<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center gap-3 mb-6 border-b-2 border-red-500 pb-2 inline-block">
            <h2 class="text-3xl font-extrabold text-gray-900">Keluhan & Laporan Komunitas</h2>
            <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full font-bold shadow-sm">Tiket Isu</span>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="bg-white shadow rounded-xl p-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Konten Dilaporkan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alasan Detail</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Eksekusi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reports as $report)
                        <tr class="hover:bg-red-50 transition items-start">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->created_at->format('d M Y - H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{{ $report->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($report->novel)
                                    <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full mb-1 inline-block border border-blue-100">📖 NOVEL</span>
                                    <span class="font-bold text-gray-900 block">{{ $report->novel->title }}</span>
                                @elseif($report->comment)
                                    <span class="text-xs font-bold text-orange-700 bg-orange-50 px-2 py-0.5 rounded-full mb-1 inline-block border border-orange-100">💬 KOMENTAR</span>
                                    <span class="text-gray-900 block line-clamp-2 italic">"{{ $report->comment->content }}"</span>
                                    <span class="text-[10px] text-gray-400">Oleh: {{ $report->comment->user->name }}</span>
                                @elseif($report->reportedUser)
                                    <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full mb-1 inline-block border border-purple-100">👤 PENGGUNA</span>
                                    <span class="font-bold text-gray-900 block">{{ $report->reportedUser->name }}</span>
                                @else
                                    <span class="text-gray-400 italic font-bold">Konten telah dihapus/tidak ditemukan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-sm">
                                <span class="bg-yellow-50 text-yellow-800 p-2 block rounded border border-yellow-100 shadow-inner">
                                    "{{ $report->reason }}"
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex flex-col gap-2 items-end">
                                    @if($report->novel)
                                        <a href="{{ route('novels.show', $report->novel) }}" target="_blank" class="text-blue-600 hover:underline font-bold text-xs">Cek Novel →</a>
                                    @elseif($report->comment && $report->comment->novel_id)
                                        <a href="{{ route('novels.show', $report->comment->novel_id) }}" target="_blank" class="text-blue-600 hover:underline font-bold text-xs">Cek Komentar di Novel →</a>
                                    @elseif($report->reportedUser)
                                        <a href="{{ route('users.show', $report->reportedUser) }}" target="_blank" class="text-blue-600 hover:underline font-bold text-xs">Cek Profil →</a>
                                    @endif
                                    <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Selesaikan/hapus kasus laporan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-emerald-600 hover:text-white font-bold px-3 py-1 bg-emerald-50 hover:bg-emerald-600 border border-emerald-200 rounded text-xs transition">Tandai Selesai</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">Situasi aman. Tidak ada laporan keluhan dari pengguna saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
