<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Học tập</p>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                {{ __('Khóa học của tôi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($courses->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 px-8 py-14 text-center shadow-soft">
                <p class="text-slate-600">Bạn chưa đăng ký khóa học nào.</p>
                <a href="{{ route('catalog') }}"
                    class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 transition hover:text-indigo-500">
                    Khám phá danh mục
                    <span aria-hidden="true">→</span>
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @foreach($courses as $course)
                <article
                    class="flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-soft transition hover:border-indigo-200/70 hover:shadow-card">
                    <div
                        class="relative flex h-40 items-center justify-center overflow-hidden bg-gradient-to-br from-indigo-500 via-violet-600 to-purple-700 text-4xl font-black text-white">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.15),transparent_50%)]">
                        </div>
                        <span class="relative z-10">{{ Str::limit($course->title, 1, '') }}</span>
                    </div>

                    <div class="flex flex-1 flex-col p-6">
                        <h3 class="text-lg font-bold text-slate-900 sm:text-xl">{{ $course->title }}</h3>

                        <div class="mt-4 mb-6">
                            <div class="mb-1.5 flex justify-between text-xs font-medium">
                                <span class="text-slate-500">Tiến độ</span>
                                <span class="tabular-nums text-indigo-600">{{ $course->progress }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 ring-1 ring-slate-200/60">
                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-600 transition-all duration-500"
                                    style="width: {{ $course->progress }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('learning.view', $course->id) }}"
                            class="mt-auto flex w-full items-center justify-center rounded-xl bg-slate-900 py-3 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            Tiếp tục học
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>