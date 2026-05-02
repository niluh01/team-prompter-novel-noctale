<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen {{ request()->routeIs('chapters.show') ? 'bg-transparent transition-colors duration-300' : 'bg-gray-100' }}" id="app-wrapper">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @if(request()->routeIs('admin.*'))
                    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Admin Sidebar -->
                            <div class="w-full md:w-1/4">
                                <div class="bg-gray-800 p-4 shadow sm:rounded-lg text-white border border-gray-700">
                                    <div class="mb-4 text-center">
                                        <div class="w-16 h-16 bg-gray-900 rounded-full mx-auto mb-2 flex items-center justify-center text-xl font-bold text-red-500 shadow-inner border border-gray-700">
                                            A
                                        </div>
                                        <h3 class="font-bold text-gray-100">Super Admin</h3>
                                        <span class="text-[10px] text-red-400 uppercase tracking-widest font-semibold flex items-center justify-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                            Sistem Aktif
                                        </span>
                                    </div>
                                    <nav class="space-y-1 border-t border-gray-700 pt-4">
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">📊 Ringkasan Dasbor</a>
                                        <a href="{{ route('admin.banners.index') }}" class="block px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.banners.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">🖼️ Pengaturan Slider</a>
                                        <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.users.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">👥 Manajemen User</a>
                                        <a href="{{ route('admin.novels.index') }}" class="block px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.novels.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">📚 Approval Karya</a>
                                        <a href="{{ route('admin.genres.index') }}" class="block px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.genres.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">🏷️ Kategori Genre</a>
                                        <a href="{{ route('admin.reports.index') }}" class="block px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">🚨 Laporan Tiket</a>
                                        <div class="pt-4 mt-2 border-t border-gray-700">
                                            <a href="{{ route('home') }}" class="block px-4 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-700">🏠 Kembali ke Publik</a>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                            <!-- Admin Content -->
                            <div class="w-full md:w-3/4">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                @elseif(request()->routeIs('dashboard', 'profile.*', 'writer.*', 'inbox.*', 'bookmarks.*', 'statistics.*'))
                    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                        <style>
                            @media (max-width: 768px) {
                                .hide-on-mobile { display: none !important; }
                            }
                        </style>
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Sidebar -->
                            <div class="hide-on-mobile w-full md:w-1/4">
                                <div class="bg-white p-4 shadow sm:rounded-lg">
                                    <div class="mb-4 text-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-2 flex items-center justify-center text-xl font-bold text-blue-600 shadow-sm border border-blue-200">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <h3 class="font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                                        <span class="text-xs text-gray-500 uppercase tracking-wider">{{ Auth::user()->role ?? 'PEMBACA' }}</span>
                                    </div>
                                    <nav class="space-y-1 border-t border-gray-100 pt-4">
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded text-sm text-gray-700 hover:bg-gray-50 border-l-4 {{ request()->routeIs('dashboard') ? 'border-blue-500 bg-blue-50 font-bold text-blue-700' : 'border-transparent' }}">Profil Utama</a>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded text-sm text-gray-700 hover:bg-gray-50 border-l-4 {{ request()->routeIs('profile.edit') ? 'border-blue-500 bg-blue-50 font-bold text-blue-700' : 'border-transparent' }}">Manage Profile</a>
                                        <a href="{{ route('writer.novels.index') }}" class="block px-4 py-2 rounded text-sm text-gray-700 hover:bg-gray-50 border-l-4 {{ request()->routeIs('writer.*') ? 'border-blue-500 bg-blue-50 font-bold text-blue-700' : 'border-transparent' }}">Cerita / Novel Baru</a>
                                        @php
                                            $unreadCount = Auth::check() ? \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count() : 0;
                                        @endphp
                                        <a href="{{ route('inbox.index') }}" class="block px-4 py-2 rounded text-sm text-gray-700 hover:bg-gray-50 border-l-4 {{ request()->routeIs('inbox.*') ? 'border-blue-500 bg-blue-50 font-bold text-blue-700' : 'border-transparent' }} flex justify-between">Kotak Masuk 
                                            @if($unreadCount > 0)<span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>@endif
                                        </a>
                                        <a href="{{ route('bookmarks.index') }}" class="block px-4 py-2 rounded text-sm text-gray-700 hover:bg-gray-50 border-l-4 {{ request()->routeIs('bookmarks.*') ? 'border-blue-500 bg-blue-50 font-bold text-blue-700' : 'border-transparent' }}">Novel Tersimpan</a>
                                        <a href="{{ route('statistics.index') }}" class="block px-4 py-2 rounded text-sm text-gray-700 hover:bg-gray-50 border-l-4 {{ request()->routeIs('statistics.*') ? 'border-blue-500 bg-blue-50 font-bold text-blue-700' : 'border-transparent' }}">Statistik Aktivitas</a>
                                    </nav>
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="w-full md:w-3/4">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                @else
                    {{ $slot }}
                @endif
            </main>
        </div>

        @auth
        <!-- Global Report Modal (Reuseable) -->
        <div id="globalReportModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" style="z-index: 99999;">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full relative p-6 transform transition-all scale-100">
                <button onclick="closeReportModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-100 rounded-full p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <span class="text-red-500">🚨</span> <span id="reportModalTitle">Laporkan Konten</span>
                </h3>
                <p id="reportModalDesc" class="text-xs text-gray-500 mb-4 bg-red-50 border border-red-100 p-2 rounded leading-relaxed italic">Laporan Anda akan ditinjau oleh Admin untuk menjaga komunitas tetap aman.</p>
                
                <form action="{{ route('report.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="novel_id" id="report_novel_id">
                    <input type="hidden" name="comment_id" id="report_comment_id">
                    <input type="hidden" name="reported_user_id" id="report_user_id">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Alasan Pelaporan</label>
                        <textarea name="reason" rows="4" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 resize-none text-sm" placeholder="Tuliskan alasan detail pelaporan Anda..." required></textarea>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <button type="submit" class="w-full py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-600/20">Kirim Laporan Resmi</button>
                        <button type="button" onclick="closeReportModal()" class="w-full py-2 text-gray-500 font-semibold hover:text-gray-800 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openReportModal(type, id, title = 'Konten') {
                const modal = document.getElementById('globalReportModal');
                const titleEl = document.getElementById('reportModalTitle');
                
                // Reset hidden fields
                document.getElementById('report_novel_id').value = '';
                document.getElementById('report_comment_id').value = '';
                document.getElementById('report_user_id').value = '';
                
                if(type === 'novel') {
                    document.getElementById('report_novel_id').value = id;
                    titleEl.innerText = 'Laporkan Novel: ' + title;
                } else if(type === 'comment') {
                    document.getElementById('report_comment_id').value = id;
                    titleEl.innerText = 'Laporkan Komentar';
                } else if(type === 'user') {
                    document.getElementById('report_user_id').value = id;
                    titleEl.innerText = 'Laporkan Pengguna: ' + title;
                }
                
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeReportModal() {
                const modal = document.getElementById('globalReportModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
        @endauth
    </body>
</html>
