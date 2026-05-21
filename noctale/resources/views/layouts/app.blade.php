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
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                            <span>Ringkasan Dasbor</span>
                                        </a>
                                        <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.banners.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span>Pengaturan Slider</span>
                                        </a>
                                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.users.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <span>Manajemen User</span>
                                        </a>
                                        <a href="{{ route('admin.novels.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.novels.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            <span>Approval Karya</span>
                                        </a>
                                        <a href="{{ route('admin.genres.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.genres.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span>Kategori Genre</span>
                                        </a>
                                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-red-600 font-bold shadow-md' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            <span>Laporan Tiket</span>
                                        </a>
                                        <div class="pt-4 mt-2 border-t border-gray-700">
                                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-700">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                                <span>Kembali ke Publik</span>
                                            </a>
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
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span id="reportModalTitle">Laporkan Konten</span>
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
