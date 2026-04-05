<x-app-layout>
    <div class="flex flex-col min-h-screen bg-[#0f172a] text-slate-200 font-sans">
        <div
            class="h-auto min-h-14 bg-gradient-to-r from-indigo-950 via-[#1e293b] to-[#1e293b] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-6 py-3 border-b border-indigo-500/30 shadow-lg z-10">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('courses.show', $course) }}" class="text-slate-400 hover:text-white shrink-0 transition"
                    title="Về trang khóa học">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-widest text-indigo-300 font-bold">Học thử miễn phí</p>
                    <h1 class="font-bold text-sm md:text-base truncate text-white">
                        {{ $course->title }}
                    </h1>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('courses.show', $course) }}"
                    class="text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg transition">
                    Mua / Đăng ký khóa
                </a>
                @guest
                <a href="{{ route('login') }}"
                    class="text-xs font-semibold border border-slate-500 hover:border-slate-400 text-slate-200 px-4 py-2 rounded-lg transition">
                    Đăng nhập
                </a>
                @endguest
            </div>
        </div>

        <div class="flex flex-1 flex-col lg:flex-row overflow-hidden">
            <div class="flex-1 overflow-y-auto bg-[#0f172a]">
                <div class="w-full bg-black flex items-center justify-center shadow-2xl aspect-video max-h-[65vh]">
                    <div
                        class="relative w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-950 border-b border-slate-700 p-6 text-center">
                        <span
                            class="inline-flex items-center gap-2 text-indigo-200 text-xs font-bold uppercase tracking-widest mb-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Trải nghiệm nội dung thật
                        </span>
                        @if(!empty($lesson->video_url))
                        <p class="text-slate-400 text-sm max-w-lg mb-4">Video / liên kết bài học:</p>
                        <a href="{{ $lesson->video_url }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-indigo-900 font-bold text-sm hover:bg-indigo-50 transition shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                    clip-rule="evenodd" />
                            </svg>
                            Mở media bài học
                        </a>
                        @else
                        <p class="text-slate-500 font-mono text-sm">Chưa gắn video — xem nội dung bên dưới</p>
                        @endif
                    </div>
                </div>

                <div class="max-w-4xl mx-auto p-6 md:p-10">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-2 leading-tight">{{ $lesson->title }}
                    </h2>
                    <p class="text-slate-500 text-sm mb-6">Cập nhật: {{ $lesson->updated_at->format('d/m/Y') }}</p>
                    <div class="rounded-xl border border-slate-700/80 bg-slate-900/50 p-4 mb-8 text-slate-300 text-sm">
                        Bạn đang xem <strong class="text-indigo-300">bài học thử</strong>. Đăng ký khóa để mở toàn bộ
                        {{ $totalInCourse }} bài và theo dõi tiến độ.
                    </div>
                    <div class="prose prose-invert max-w-none text-slate-300 border-t border-slate-800 pt-8">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-[340px] bg-[#1e293b] border-t lg:border-t-0 lg:border-l border-slate-700 flex flex-col shadow-xl">
                <div class="p-4 border-b border-slate-700">
                    <h3 class="font-bold text-slate-100 text-xs uppercase tracking-wider">Bài được mở thử</h3>
                    <p class="text-[11px] text-slate-500 mt-1">{{ $previewLessons->count() }} / {{ $totalInCourse }} bài
                    </p>
                </div>
                <div class="flex-1 overflow-y-auto max-h-[50vh] lg:max-h-none bg-[#111827]">
                    <div class="divide-y divide-slate-800">
                        @foreach($previewLessons as $item)
                        <a href="{{ route('courses.preview', [$course, $item]) }}"
                            class="block p-4 transition {{ $lesson->id === $item->id ? 'bg-slate-800 border-l-4 border-indigo-500' : 'hover:bg-slate-800/50 border-l-4 border-transparent' }}">
                            <div class="flex gap-3">
                                <span
                                    class="text-[10px] font-bold text-emerald-400 shrink-0 mt-0.5">THỬ</span>
                                <p
                                    class="text-sm font-medium {{ $lesson->id === $item->id ? 'text-white' : 'text-slate-400' }}">
                                    {{ $item->order }}. {{ $item->title }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @if($totalInCourse > $previewLessons->count())
                <div class="p-4 border-t border-slate-700 bg-slate-900/80">
                    <p class="text-xs text-slate-500 mb-3">
                        +{{ $totalInCourse - $previewLessons->count() }} bài chỉ dành cho học viên đã đăng ký.
                    </p>
                    <a href="{{ route('courses.show', $course) }}"
                        class="block w-full text-center text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-lg transition">
                        Đăng ký để mở khóa
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
