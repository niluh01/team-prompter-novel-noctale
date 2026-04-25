<x-app-layout>
    <div class="space-y-6">
        <h2 class="text-3xl font-extrabold text-gray-900 border-b-2 border-red-500 pb-2 mb-6 inline-block">Manajemen User (Pengguna)</h2>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Search & Filter Bar (Compact & Professional) -->
        <div class="bg-white shadow-sm border border-gray-100 rounded-2xl mb-6 overflow-hidden">
            <div class="p-4 bg-gray-50/30">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                    <!-- Search Field (Wider + Fixed Icon) -->
                    <div class="relative flex-[3] w-full group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 z-10 group-focus-within:text-red-500 transition-colors">
                            <!-- <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> -->
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full border-gray-200 bg-white rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all font-medium placeholder-gray-400 relative z-0" 
                               placeholder="Cari berdasarkan nama atau email...">
                    </div>
                    
                    <!-- Role Dropdown -->
                    <div class="w-full md:w-48 flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest hidden lg:inline">Role:</span>
                        <select name="role" onchange="this.form.submit()" 
                                class="flex-1 border-gray-200 bg-white rounded-xl py-2.5 px-4 text-sm font-bold focus:ring-4 focus:ring-red-500/10 focus:border-red-500 cursor-pointer">
                            <option value="">Semua Peran</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>👑 Admin</option>
                            <option value="reader" {{ request('role') == 'reader' ? 'selected' : '' }}>👥 Reader</option>
                        </select>
                    </div>

                    <!-- Search Button (Smaller) -->
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="px-4 py-2.5 bg-red-600 text-white font-black rounded-xl hover:bg-black transition-all shadow-md active:scale-95 text-[10px] uppercase tracking-widest">
                            Cari
                        </button>
                        @if(request('search') || request('role'))
                            <a href="{{ route('admin.users.index') }}" 
                               class="px-4 py-2.5 bg-gray-100 text-gray-500 font-bold rounded-xl hover:bg-gray-200 transition-all text-sm flex items-center justify-center border border-gray-200" 
                               title="Reset Pencarian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Akun</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Terdaftar Sejak</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($user->role === 'admin')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-red-100 text-red-800 border border-red-200 shadow-sm">👑 Admin</span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-blue-50 text-blue-800 border border-blue-200">👥 Reader</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d M Y - H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/banned pengguna ini secara absolut? Segala komentar dan histori mereka akan dihilangkan!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-200 shadow-sm transition">Banned / Hapus</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic mr-6">Anda (SuperAdmin)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
