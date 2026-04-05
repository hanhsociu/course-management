<x-app-layout>
    @php
    $hasPreview = $previewLessons->isNotEmpty();
    @endphp

    @if(session('info'))
    <div class="bg-indigo-900 text-indigo-100 text-center text-sm py-3 px-4">
        {{ session('info') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-900 text-red-100 text-center text-sm py-3 px-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-slate-950 text-white">
        <div
            class="relative overflow-hidden border-b border-indigo-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/30 via-purple-900/20 to-slate-950"></div>
            <div class="absolute inset-0 opacity-30"
                style="background-image: radial-gradient(circle at 20% 50%, rgba(99,102,241,0.35) 0%, transparent 45%), radial-gradient(circle at 80% 30%, rgba(168,85,247,0.2) 0%, transparent 40%);">
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-indigo-200 ring-1 ring-white/10">
                        Khóa học online
                    </span>
                    @if($hasPreview)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 ring-1 ring-emerald-400/30">
                        Có bài học thử miễn phí
                    </span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white mb-4 max-w-4xl leading-tight">
                    {{ $course->title }}
                </h1>
                <p class="text-lg text-slate-300 max-w-3xl leading-relaxed mb-8">
                    {{ $course->description }}
                </p>
                <div class="flex flex-wrap gap-6 text-sm text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="text-indigo-400 font-bold text-lg">{{ $lessons->count() }}</span>
                        <span>bài giảng</span>
                    </div>
                    @if($hasPreview)
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400 font-bold text-lg">{{ $previewLessons->count() }}</span>
                        <span>bài xem thử</span>
                    </div>
                    @endif
                    @if($lockedCount > 0)
                    <div class="flex items-center gap-2">
                        <span class="text-slate-500 font-bold text-lg">{{ $lockedCount }}</span>
                        <span>bài sau khi đăng ký</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="py-12 bg-slate-50 text-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-10 lg:gap-14">

                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Nội dung khóa học</h2>
                        <p class="text-gray-600 mb-6">Xem cấu trúc lộ trình. Bài có nhãn <span
                                class="text-emerald-700 font-semibold">Học thử</span> mở được trước khi mua.</p>

                        <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                            @foreach($lessons as $lesson)
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50/80 transition">
                                <div class="flex items-start gap-3 min-w-0">
                                    <span
                                        class="shrink-0 w-9 h-9 rounded-full bg-indigo-50 text-indigo-700 font-bold text-sm flex items-center justify-center">
                                        {{ $lesson->order }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900">{{ $lesson->title }}</p>
                                        <p class="text-sm text-gray-500 line-clamp-2 mt-0.5">
                                            {{ Str::limit(strip_tags($lesson->content), 120) }}</p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-end">
                                    @if($lesson->is_preview)
                                    <a href="{{ route('courses.preview', [$course, $lesson]) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wide text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Học thử
                                    </a>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-gray-400 bg-gray-100 px-3 py-1.5 rounded-lg">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Chỉ sau đăng ký
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="w-full lg:w-96 shrink-0">
                        <div
                            class="sticky top-8 rounded-2xl border-2 border-indigo-200 bg-white p-6 shadow-xl shadow-indigo-100/50">
                            <div class="text-3xl font-black text-gray-900 mb-1">
                                {{ number_format($course->price) }}<span class="text-lg font-bold text-gray-500">đ</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-6">Truy cập nội dung đầy đủ • Cập nhật theo khóa</p>

                            @if($hasPreview)
                            <a href="{{ route('courses.preview', $course) }}"
                                class="mb-3 flex w-full items-center justify-center gap-2 rounded-xl border-2 border-indigo-600 bg-indigo-50 py-3.5 text-sm font-bold text-indigo-800 hover:bg-indigo-100 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                        clip-rule="evenodd" />
                                </svg>
                                Xem học thử miễn phí
                            </a>
                            @endif

                            @auth
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('learning.view', $course) }}"
                                class="flex w-full items-center justify-center bg-violet-600 text-white font-bold py-4 rounded-xl hover:bg-violet-700 shadow-lg transition">
                                Vào phòng học (Admin)
                            </a>
                            @elseif(auth()->user()->enrolledCourses->contains($course->id))
                            <a href="{{ route('learning.view', $course) }}"
                                class="flex w-full items-center justify-center bg-emerald-600 text-white font-bold py-4 rounded-xl hover:bg-emerald-700 shadow-lg transition">
                                Tiếp tục học
                            </a>
                            @elseif((float) $course->price > 0)
                            <form action="{{ route('courses.checkout', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 shadow-lg transition">
                                    Thanh toán PayOS — {{ number_format($course->price) }}đ
                                </button>
                            </form>
                            @else
                            <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 shadow-lg transition">
                                    Đăng ký miễn phí
                                </button>
                            </form>
                            @endif
                            @else
                            <a href="{{ route('login') }}"
                                class="flex w-full items-center justify-center bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 shadow-lg transition">
                                Đăng nhập để đăng ký
                            </a>
                            <p class="mt-3 text-center text-xs text-gray-500">Chưa có tài khoản? <a
                                    href="{{ route('register') }}" class="text-indigo-600 font-semibold underline">Đăng
                                    ký</a></p>
                            @endauth

                            <ul class="mt-6 space-y-2 text-sm text-gray-600 border-t border-gray-100 pt-6">
                                <li class="flex gap-2"><span class="text-emerald-600">✓</span> Xem trước nội dung thật</li>
                                <li class="flex gap-2"><span class="text-emerald-600">✓</span> Lộ trình rõ ràng theo từng
                                    bài</li>
                                <li class="flex gap-2"><span class="text-emerald-600">✓</span> Thanh toán an toàn qua PayOS (VN)</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
