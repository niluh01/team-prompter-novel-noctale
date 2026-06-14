<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-logo1 class="block h-20 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>

                    <!-- Nav Jelajahi Dropdown -->
                    @php $navGenres = \App\Models\Genre::orderBy('name', 'asc')->get(); @endphp
                    <div class="hidden sm:flex sm:items-center ml-2">
                        <x-dropdown align="left" width="w-[750px]">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out h-full mt-1 cursor-pointer">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        <span>Jelajahi</span>
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="p-6 flex gap-8">
                                    <!-- Sisi Kiri: Menu Utama (Sidebar) -->
                                    <div class="w-[200px] flex-shrink-0">
                                        <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest px-3 py-1.5 mb-4 bg-blue-50 rounded-lg flex items-center gap-1.5 w-max">
                                            <svg class="w-3 h-3 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                            <span>Utama</span>
                                        </div>
                                        <div class="space-y-1">
                                            <a href="{{ route('novels.index', ['sort' => 'popular']) }}" class="group flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition duration-300">
                                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 7 10c0-2 .5-3 2.5-4.5 0 0-1 4 2 5.5s3.5-1.5 3.5-1.5c-1.5 2.5-1 4.5.5 5.5 1.5.5 2.5-2 2.5-2a4.5 4.5 0 01-.343 6.157z"></path></svg>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-sm text-gray-800">Terpopuler</span>
                                                    <span class="text-[10px] text-gray-400">Paling hits</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('novels.index', ['sort' => 'latest']) }}" class="group flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition duration-300">
                                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-sm text-gray-800">Paling Baru</span>
                                                    <span class="text-[10px] text-gray-400">Update rutin</span>
                                                </div>
                                            </a>
                                            <a href="{{ route('novels.index', ['sort' => 'all']) }}" class="group flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition duration-300">
                                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-sm text-gray-800">Koleksi</span>
                                                    <span class="text-[10px] text-gray-400">Cek Semua</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 border-l border-gray-100 pl-8">
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest py-2 mb-4 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-450 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Kategori Genre</span>
                                        </div>
                                        <div class="gap-x-6 gap-y-2 max-h-[350px] overflow-x-auto overflow-y-hidden pb-4 pr-2 custom-scrollbar" style="display: grid; grid-template-rows: repeat(5, minmax(0, 1fr)); grid-auto-flow: column;">
                                            @forelse($navGenres as $g)
                                                <a href="{{ route('novels.index', ['genre' => $g->id]) }}" 
                                                    class="text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 py-2 px-3 rounded-lg transition duration-200 flex items-center justify-between group whitespace-nowrap min-w-[150px]">
                                                    <span>{{ $g->name }}</span>
                                                    <span class="opacity-0 group-hover:opacity-100 transition-opacity text-blue-400 text-xs">→</span>
                                                </a>
                                            @empty
                                                <div class="col-span-5 px-4 py-10 text-center text-xs text-gray-400 italic bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                                                    Belum ada genre tersedia
                                                </div>
                                            @endforelse
                                        </div>
                                        
                                        @if($navGenres->count() > 0)
                                        <div class="mt-4 pt-3 border-t border-gray-50 flex justify-end">
                                            <a href="{{ route('novels.index') }}" class="text-[10px] font-bold text-blue-600 hover:underline flex items-center gap-1">
                                                Lihat Selengkapnya 
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <x-nav-link :href="route('history.index')" :active="request()->routeIs('history.*')" class="ml-4">
                        {{ __('Riwayat') }}
                    </x-nav-link>
                    
                    @auth
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="ml-6">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Search Bar (Middle) -->
            <div class="hidden sm:flex flex-1 items-center justify-center px-4">
                <form action="{{ route('novels.index') }}" method="GET" class="w-full max-w-md">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari novel berdasarkan judul..." class="w-full border-gray-300 rounded-full shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 pl-4 py-2 pr-10 text-sm">
                        <button type="submit" class="absolute right-0 top-0 mt-2.5 mr-3 text-gray-500 hover:text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 gap-2">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('dashboard')">
                            {{ __('Dasbor Utama') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Kelola Profil') }}
                        </x-dropdown-link>
                        @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')" class="text-red-600 font-bold bg-red-50 hover:bg-red-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    {{ __('Area Super Admin') }}
                                </div>
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
                <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endauth
            </div>

            <!-- Mobile Quick Actions & Hamburger -->
            <div class="-me-2 flex items-center sm:hidden gap-1">
                <!-- Home -->
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition p-1.5 rounded-lg {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50' }}" title="Beranda">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </a>
                
                <!-- Jelajahi Dropdown -->
                <div x-data="{ openGenre: false }" class="relative flex items-center">
                    <button @click="openGenre = !openGenre" @click.outside="openGenre = false" class="text-gray-500 hover:text-blue-600 transition p-1.5 rounded-lg hover:bg-gray-50 focus:outline-none" title="Jelajahi Kategori">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                    
                    <div x-show="openGenre" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden" 
                         style="display: none; position: fixed; top: 60px; left: 50%; transform: translateX(-50%); width: 92vw; max-width: 360px; z-index: 100;">
                        <div class="p-3 border-b border-gray-100" style="background: linear-gradient(135deg, #eff6ff, #fff);">
                            <span class="text-xs font-black text-gray-500 tracking-wider uppercase flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                Kategori Genre
                            </span>
                        </div>
                        <div class="p-3 overflow-y-auto" style="max-height: 55vh;">
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px;">
                                @forelse($navGenres as $g)
                                    <a href="{{ route('novels.index', ['genre' => $g->id]) }}" 
                                       title="{{ $g->name }}"
                                       style="font-size: 10px; padding: 8px 4px; border-radius: 10px; border: 1px solid #e5e7eb; text-align: center; text-decoration: none; color: #374151; font-weight: 600; display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; transition: all 0.15s ease;">
                                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width: 100%;">{{ $g->name }}</span>
                                    </a>
                                @empty
                                    <span style="grid-column: span 4; text-align: center; font-size: 12px; color: #9ca3af; font-style: italic; padding: 16px 0;">Belum ada genre tersedia</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="border-t border-gray-100 text-center" style="background: #f9fafb; padding: 12px;">
                            <a href="{{ route('novels.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">Eksplorasi Semua Novel &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <button @click="open = true;" class="text-gray-500 hover:text-blue-600 transition p-1.5 rounded-lg hover:bg-gray-50" title="Pencarian">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                
                @auth
                <!-- History -->
                <a href="{{ route('history.index') }}" class="text-gray-500 hover:text-blue-600 transition p-1.5 rounded-lg {{ request()->routeIs('history.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50' }}" title="Riwayat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </a>
                @endauth
                
                <!-- Burger Menu -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-1.5 rounded-xl text-gray-500 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:bg-blue-50 focus:text-blue-600 transition duration-150 ease-in-out border border-transparent shadow-sm mx-1">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- FB-Style Responsive Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="sm:hidden fixed inset-0 overflow-y-auto" 
         style="display: none; z-index: 9999; background-color: rgba(240, 242, 245, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
         
         <!-- Head Area -->
         <div class="flex items-center justify-between px-4 py-3 sticky top-0 shadow-sm border-b border-gray-200/50" style="z-index: 10000; background-color: rgba(240, 242, 245, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
             <h2 class="text-2xl font-bold text-gray-900">Menu Profil</h2>
             <div class="flex items-center gap-2">
                 <button @click="open = false" class="p-2 bg-white rounded-full text-gray-800 hover:bg-gray-200 shadow-sm transition">
                     <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                 </button>
             </div>
         </div>

         <div class="p-4 pt-1 space-y-4 relative" style="z-index: 9999;">
             <!-- Profile Card -->
             @auth
             <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 bg-white p-4 rounded-xl shadow-sm active:bg-gray-50 transition border border-gray-100">
                 @if(Auth::user()->avatar)
                     <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-12 h-12 rounded-full object-cover border border-gray-200">
                 @else
                     <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 font-black flex items-center justify-center text-xl">
                         {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                     </div>
                 @endif
                 <div class="flex-1">
                     <div class="font-bold text-gray-900 leading-tight text-lg">{{ Auth::user()->name }}</div>
                     <div class="text-[13px] text-gray-500 mt-1">Lihat dan kelola profil Anda</div>
                 </div>
             </a>
             @else
             <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-600 mb-3 font-semibold">Gunakan seluruh fitur platform dengan masuk ke akun Anda.</p>
                <div class="flex gap-2">
                    <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition">Log In</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-lg transition">Daftar</a>
                </div>
             </div>
             @endauth
             
             <!-- Mobile Search Bar -->
             <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 relative">
                 <form action="{{ route('novels.index') }}" method="GET" class="w-full">
                     <div class="relative flex items-center rounded-full overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 transition-all border border-gray-100 shadow-inner" style="background-color: #f3f4f6;">
                         <button type="submit" class="pl-3 text-gray-500">
                             <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                         </button>
                         <input type="text" name="search" placeholder="Cari novel atau cerita..." class="w-full bg-transparent border-0 py-2.5 pl-2 pr-4 text-sm text-gray-700 focus:ring-0 placeholder-gray-500 font-medium">
                     </div>
                 </form>
             </div>

             <!-- Grid Mengelola Akun & Dashboard Shortcuts -->
             @auth
             <div class="grid grid-cols-2 gap-3">
                 <!-- Profil Utama -->
                 <a href="{{ route('dashboard') }}" class="bg-white/90 p-4 rounded-xl shadow-sm flex flex-col items-start gap-2 hover:bg-gray-50 active:bg-gray-100 transition border border-gray-100 backdrop-blur-sm">
                     <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shadow-sm">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                     </div>
                     <span class="font-bold text-gray-800 text-[14px]">Profil Utama</span>
                 </a>
                 
                 <!-- Novel Baru / Manajemen -->
                 <a href="{{ route('writer.novels.index') }}" class="bg-white/90 p-4 rounded-xl shadow-sm flex flex-col items-start gap-2 hover:bg-gray-50 active:bg-gray-100 transition border border-gray-100 backdrop-blur-sm">
                     <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 shadow-sm">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                     </div>
                     <span class="font-bold text-gray-800 text-[14px]">Cerita Baru</span>
                 </a>
                 
                 <!-- Kotak Masuk -->
                 <a href="{{ route('inbox.index') }}" class="bg-white/90 p-4 rounded-xl shadow-sm flex flex-col items-start gap-2 hover:bg-gray-50 active:bg-gray-100 transition border border-gray-100 backdrop-blur-sm">
                     <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 shadow-sm relative">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                         <!-- Notification Badge -->
                         <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                         </span>
                     </div>
                     <span class="font-bold text-gray-800 text-[14px]">Kotak Masuk</span>
                 </a>

                 <!-- Koleksi Bookmark -->
                 <a href="{{ route('bookmarks.index') }}" class="bg-white/90 p-4 rounded-xl shadow-sm flex flex-col items-start gap-2 hover:bg-gray-50 active:bg-gray-100 transition border border-gray-100 backdrop-blur-sm">
                     <div class="w-8 h-8 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-500 shadow-sm">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                     </div>
                     <span class="font-bold text-gray-800 text-[14px]">Novel Tersimpan</span>
                 </a>
                 
                 <!-- Statistik Aktivitas (Full Width) -->
                 <a href="{{ route('statistics.index') }}" class="col-span-2 bg-white/90 p-4 rounded-xl shadow-sm flex flex-row items-center gap-3 hover:bg-gray-50 active:bg-gray-100 transition border border-gray-100 backdrop-blur-sm">
                     <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500 shadow-sm">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                     </div>
                     <span class="font-bold text-gray-800 text-[14px]">Statistik Aktivitas</span>
                 </a>
             </div>
             @endauth

             <!-- Settings & Admin (Pengaturan & Privasi Style) -->
             @auth
             <div class="bg-transparent mt-2">
                 <div class="px-2 py-2 mb-1 flex justify-between items-center text-gray-700">
                     <span class="font-semibold text-base py-1">Pengaturan & Bantuan</span>
                     <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                 </div>
                 
                 <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                     <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50 active:bg-gray-100 transition">
                         <div class="text-gray-500">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                         </div>
                         <span class="font-semibold text-gray-800 text-[15px]">Kelola Akun</span>
                     </a>

                     @if(Auth::user()->role === 'admin')
                         <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50 active:bg-gray-100 transition">
                             <div class="text-red-500">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                             </div>
                             <span class="font-semibold text-red-600 text-[15px]">Area Super Admin</span>
                         </a>
                     @endif

                     <form method="POST" action="{{ route('logout') }}" class="block w-full">
                         @csrf
                         <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 active:bg-gray-100 transition text-left cursor-pointer">
                             <div class="text-gray-500">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                             </div>
                             <span class="font-semibold text-gray-800 text-[15px]">Keluar</span>
                         </button>
                     </form>
                 </div>
             </div>
             @endauth

             <div class="h-8"></div>
         </div>
    </div>
</nav>
