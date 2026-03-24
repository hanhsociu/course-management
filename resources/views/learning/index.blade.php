<x-app-layout>
    <div class="flex flex-col h-screen bg-[#0f172a] text-slate-200 font-sans">
        <div class="h-14 bg-[#1e293b] flex items-center px-6 justify-between border-b border-slate-700 shadow-xl z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="font-bold text-sm md:text-base truncate max-w-md text-slate-100">
                    {{ $course->title }} <span class="mx-2 text-slate-500">|</span> <span
                        class="font-medium text-slate-400">{{ $lesson->title }}</span>
                </h1>
            </div>
            <a href="{{ route('my-courses') }}"
                class="text-xs font-semibold bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg transition">THOÁT
                HỌC</a>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto bg-[#0f172a] custom-scrollbar">
                <div class="w-full bg-black flex items-center justify-center shadow-2xl aspect-video max-h-[70vh]">
                    <div
                        class="relative w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-800 to-slate-900 border-b border-slate-700">
                        <span class="text-slate-500 font-mono text-sm uppercase tracking-widest">
                            🎥 Player: {{ $lesson->video_url ?? 'Chưa có Media' }}
                        </span>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto p-8 md:p-12">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-3xl font-extrabold text-white mb-2 leading-tight">{{ $lesson->title }}</h2>
                            <p class="text-slate-500 text-sm">Cập nhật: {{ $lesson->updated_at->format('d/m/Y') }}</p>
                        </div>
                        <livewire:learning.mark-complete :lessonId="$lesson->id" :key="'btn-'.$lesson->id" />
                    </div>
                    <div class="prose prose-invert max-w-none text-slate-300 border-t border-slate-800 pt-8">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                </div>
            </div>

            <div class="w-[350px] bg-[#1e293b] border-l border-slate-700 flex flex-col shadow-2xl">
                <div class="p-5 border-b border-slate-700 bg-[#1e293b]/50">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-bold text-slate-100 uppercase tracking-wider text-[10px]">Tiến độ của bạn</h3>
                        <span class="text-indigo-400 font-bold text-xs">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-1.5">
                        <div class="bg-indigo-500 h-1.5 rounded-full transition-all duration-700"
                            style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-2 italic">Đã hoàn thành
                        {{ $completedCount }}/{{ $totalLessons }} bài học</p>
                </div>

                <div class="p-4 font-bold text-slate-100 uppercase tracking-wider text-xs border-b border-slate-700">Nội
                    dung khóa học</div>

                <div class="flex-1 overflow-y-auto custom-scrollbar bg-[#111827]">
                    <div class="divide-y divide-slate-800">
                        @foreach($lessons as $item)
                        <a href="{{ route('learning.view', [$course->id, $item->id]) }}"
                            class="group block p-4 transition-all duration-200 {{ $lesson->id == $item->id ? 'bg-slate-800 border-l-4 border-indigo-500 shadow-inner' : 'hover:bg-slate-800/50' }}">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    @if(auth()->user()->completedLessons->contains($item->id))
                                    <div class="bg-green-500 rounded-full p-0.5">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    @else
                                    <div
                                        class="w-4 h-4 border border-slate-600 rounded-full flex items-center justify-center text-[8px] font-bold text-slate-500">
                                        {{ $item->order }}</div>
                                    @endif
                                </div>
                                <p
                                    class="text-sm font-medium {{ $lesson->id == $item->id ? 'text-white' : 'text-slate-400' }}">
                                    {{ $item->title }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>