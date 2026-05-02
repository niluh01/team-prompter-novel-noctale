@props(['novel'])

<a href="{{ route('novels.show', $novel) }}" class="group block relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white border border-gray-100">
    <div class="relative aspect-[2/3] bg-gray-100 overflow-hidden">
        @if($novel->cover)
            <img src="{{ Storage::url($novel->cover) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-xs text-gray-400 font-bold uppercase tracking-widest italic bg-gray-50">No Cover</div>
        @endif
        
        <!-- Badge Status -->
        @if($novel->chapters_count ?? false)
            <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm text-white text-[9px] font-black uppercase px-2 py-1 rounded border border-white/20 shadow-sm">
                {{ $novel->chapters_count }} BAB
            </div>
        @endif
    </div>
    
    <div class="p-3">
        <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors leading-tight" title="{{ $novel->title }}">{{ $novel->title }}</h3>
        
        <div class="flex flex-col gap-1.5 mt-2">
            <div class="flex items-center text-[10px] text-gray-500 font-medium">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                <span class="truncate">{{ $novel->author->name }}</span>
            </div>
            
            <div class="flex items-center justify-between mt-1">
                <div class="flex items-center gap-1 text-[10px] font-bold text-gray-400">
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    {{ number_format($novel->views) }}
                </div>
                
                @if($novel->genres && $novel->genres->count() > 0)
                    <span class="text-[9px] bg-gray-50 text-gray-500 px-1.5 py-0.5 rounded border border-gray-100 font-bold truncate max-w-[60px]">{{ $novel->genres->first()->name }}</span>
                @endif
            </div>
        </div>
    </div>
</a>
