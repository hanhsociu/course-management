<x-app-layout>
    <div class="py-10 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Danh mục</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Khám phá khóa học</h1>
                <p class="mt-3 text-base text-slate-600 leading-relaxed">Tìm kiếm theo tên hoặc mô tả, xem thử nội dung và đăng ký trong vài bước.</p>
            </div>

            <form method="GET" action="{{ route('catalog') }}"
                class="mb-10 flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white/80 p-4 shadow-soft backdrop-blur-sm sm:flex-row sm:items-center sm:p-2 sm:pr-2">
                <label for="catalog-search" class="sr-only">Tìm khóa học</label>
                <input type="search" id="catalog-search" name="q" value="{{ request('q') }}"
                    placeholder="Tìm theo tên hoặc mô tả..."
                    maxlength="255"
                    class="min-h-[48px] flex-1 rounded-xl border-0 bg-slate-50/90 px-4 text-slate-900 placeholder:text-slate-400 shadow-inner ring-1 ring-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/25 sm:min-h-0">
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <button type="submit"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-indigo-600 px-6 text-sm font-semibold text-white shadow-sm shadow-indigo-500/25 transition hover:bg-indigo-700 hover:shadow-md sm:min-h-[40px]">
                        Tìm kiếm
                    </button>
                    @if(request()->filled('q'))
                    <a href="{{ route('catalog') }}"
                        class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:min-h-[40px]">
                        Xóa bộ lọc
                    </a>
                    @endif
                </div>
            </form>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @forelse($courses as $course)
                <article
                    class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-soft transition duration-300 hover:-translate-y-0.5 hover:border-indigo-200/70 hover:shadow-card">

                    <a href="{{ route('courses.show', $course->id) }}" class="relative block h-48 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-indigo-500 via-violet-600 to-purple-700 transition duration-500 group-hover:scale-105">
                        </div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.2),transparent_45%)]">
                        </div>
                        <span
                            class="relative z-10 flex h-full items-center justify-center text-5xl font-black text-white/95 drop-shadow-sm">
                            {{ Str::limit($course->title, 1, '') }}
                        </span>
                    </a>

                    <div class="flex flex-1 flex-col p-6">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            @if(!empty($course->has_preview_lessons))
                            <span
                                class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-emerald-800 ring-1 ring-emerald-100">Học thử</span>
                            @else
                            <span class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-indigo-800 ring-1 ring-indigo-100">Khóa học</span>
                            @endif
                            <span class="text-sm font-bold text-emerald-600">{{ number_format($course->price) }}đ</span>
                        </div>

                        <a href="{{ route('courses.show', $course->id) }}" class="group/title">
                            <h3
                                class="text-lg font-bold text-slate-900 transition group-hover/title:text-indigo-600 sm:text-xl">
                                {{ $course->title }}
                            </h3>
                        </a>

                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">
                            {{ Str::limit($course->description, 100) }}
                        </p>

                        @guest
                        <a href="{{ route('login') }}"
                            class="mt-5 flex w-full items-center justify-center rounded-xl bg-indigo-600 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-500/25 transition hover:bg-indigo-700">
                            Đăng nhập để mua / đăng ký
                        </a>
                        @else
                        @if(auth()->user()->isAdmin() || auth()->user()->enrolledCourses->contains($course->id))
                        <a href="{{ route('learning.view', $course) }}"
                            class="mt-5 flex w-full items-center justify-center rounded-xl bg-emerald-600 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            Vào học
                        </a>
                        @elseif((float) $course->price > 0)
                        <form action="{{ route('courses.checkout', $course) }}" method="POST" class="mt-5">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl bg-indigo-600 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-500/25 transition hover:bg-indigo-700 active:scale-[0.98]">
                                Thanh toán {{ number_format($course->price) }}đ
                            </button>
                        </form>
                        @else
                        <form action="{{ route('courses.enroll', $course->id) }}" method="POST" class="mt-5">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl bg-indigo-600 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-500/25 transition hover:bg-indigo-700 active:scale-[0.98]">
                                Đăng ký miễn phí
                            </button>
                        </form>
                        @endif
                        @endguest
                    </div>
                </article>
                @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 py-16 text-center md:col-span-2 lg:col-span-3">
                    <p class="text-lg text-slate-600">
                        @if(request()->filled('q'))
                            Không có khóa học nào khớp với “{{ request('q') }}”.
                        @else
                            Hiện chưa có khóa học nào.
                        @endif
                    </p>
                </div>
                @endforelse
            </div>

            @if($courses->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $courses->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>