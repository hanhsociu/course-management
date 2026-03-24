<div>
    <button wire:click="toggleComplete" class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 shadow-lg 
        {{ $isCompleted 
            ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/50 hover:bg-emerald-500/30' 
            : 'bg-indigo-600 text-white hover:bg-indigo-500 hover:shadow-indigo-500/20 active:scale-95' 
        }}">

        @if($isCompleted)
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>ĐÃ HOÀN THÀNH</span>
        @else
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>HOÀN THÀNH BÀI HỌC</span>
        @endif
    </button>
</div>