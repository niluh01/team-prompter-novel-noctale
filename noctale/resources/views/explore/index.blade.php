<x-app-layout>
    <!-- Stylish Hero Banner -->
    <div class="relative bg-gradient-to-br from-[#121315] via-[#1a1b1e] to-[#0d0e10] pt-28 pb-32 overflow-hidden shadow-2xl">
        <!-- Ambient Glowing Background -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-blue-600 rounded-full mix-blend-screen filter blur-[120px] opacity-20"></div>
            <div class="absolute top-20 right-0 w-[400px] h-[400px] bg-red-600 rounded-full mix-blend-screen filter blur-[120px] opacity-20"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-white/10 text-gray-300 border border-white/20 text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-md">
                Jelajahi Perpustakaan
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-white mb-6 tracking-tight drop-shadow-2xl">
                @if(request('search'))
                    Pencarian untuk: <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">"{{ request('search') }}"</span>
                @elseif(isset($genreModel))
                    Koleksi Genre: <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-yellow-500">{{ $genreModel->name }}</span>
                @else
                    {{ $title }}
                @endif
            </h1>
            <p class="text-gray-400 max-w-3xl mx-auto text-base md:text-xl font-medium leading-relaxed">
                Temukan ribuan cerita menarik dari berbagai genre dan penulis berbakat. Terhanyutlah dalam dunia fiksi yang tak terbatas hari ini.
            </p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-gray-50 min-h-screen relative pb-16 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Filter List -->
            <div class="relative z-20 mb-12">
                
                <!-- Wrap-around Mixed Pills -->
                <div style="max-width: 700px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 8px;">
                    <!-- ALL (Reset Genre) -->
                    <a href="{{ request()->fullUrlWithoutQuery('genre') }}" 
                       style="white-space: nowrap; padding: 8px 18px; border-radius: 12px; font-size: 13px; transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); text-decoration: none; border: 1px solid {{ !request('genre') ? '#111827' : '#e5e7eb' }}; background: {{ !request('genre') ? '#111827' : '#fff' }}; color: {{ !request('genre') ? '#fff' : '#374151' }}; font-weight: {{ !request('genre') ? '700' : '500' }};">
                        All
                    </a>
                    
                    <!-- Sorters -->
                    <a href="{{ route('novels.index', array_merge(request()->query(), ['sort' => 'popular'])) }}" 
                       style="white-space: nowrap; padding: 8px 18px; border-radius: 12px; font-size: 13px; transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); text-decoration: none; border: 1px solid {{ request('sort') == 'popular' ? '#111827' : '#e5e7eb' }}; background: {{ request('sort') == 'popular' ? '#111827' : '#fff' }}; color: {{ request('sort') == 'popular' ? '#fff' : '#374151' }}; font-weight: {{ request('sort') == 'popular' ? '700' : '500' }};">
                        Paling Populer
                    </a>
                    <a href="{{ route('novels.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" 
                       style="white-space: nowrap; padding: 8px 18px; border-radius: 12px; font-size: 13px; transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); text-decoration: none; border: 1px solid {{ (request('sort') == 'latest' || !request('sort')) ? '#111827' : '#e5e7eb' }}; background: {{ (request('sort') == 'latest' || !request('sort')) ? '#111827' : '#fff' }}; color: {{ (request('sort') == 'latest' || !request('sort')) ? '#fff' : '#374151' }}; font-weight: {{ (request('sort') == 'latest' || !request('sort')) ? '700' : '500' }};">
                        Terbaru
                    </a>

                    <!-- Genres -->
                    @foreach($genres as $g)
                        <a href="{{ route('novels.index', array_merge(request()->query(), ['genre' => $g->id])) }}" 
                           style="white-space: nowrap; padding: 8px 18px; border-radius: 12px; font-size: 13px; transition: all 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,0.05); text-decoration: none; border: 1px solid {{ request('genre') == $g->id ? '#111827' : '#e5e7eb' }}; background: {{ request('genre') == $g->id ? '#111827' : '#fff' }}; color: {{ request('genre') == $g->id ? '#fff' : '#374151' }}; font-weight: {{ request('genre') == $g->id ? '700' : '500' }};">
                            {{ $g->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-12">
                @forelse($novels as $novel)
                    <x-novel-card :novel="$novel" />
                @empty
                    <div class="col-span-full text-center py-20">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <h3 class="text-xl font-bold text-gray-500">Tidak ada novel</h3>
                        <p class="text-gray-400 mt-2">Belum ada karya yang cocok dengan pencarian atau rentang filter ini.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8 flex justify-center">
                {{ $novels->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
