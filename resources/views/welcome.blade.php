<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Khám phá các khóa học mới nhất</h1>

            <form method="GET" action="{{ route('catalog') }}" class="mb-8 flex flex-col sm:flex-row gap-3 sm:items-center">
                <label for="catalog-search" class="sr-only">Tìm khóa học</label>
                <input type="search" id="catalog-search" name="q" value="{{ request('q') }}"
                    placeholder="Tìm theo tên hoặc mô tả..."
                    maxlength="255"
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <div class="flex gap-2 shrink-0">
                    <button type="submit"
                        class="inline-flex justify-center items-center px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Tìm kiếm
                    </button>
                    @if(request()->filled('q'))
                    <a href="{{ route('catalog') }}"
                        class="inline-flex justify-center items-center px-5 py-2.5 border border-gray-300 bg-white text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Xóa bộ lọc
                    </a>
                    @endif
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($courses as $course)
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 flex flex-col">

                    <a href="{{ route('courses.show', $course->id) }}" class="block overflow-hidden">
                        <div
                            class="h-48 bg-indigo-500 flex items-center justify-center text-white text-4xl font-bold hover:scale-105 transition duration-500">
                            {{ Str::limit($course->title, 1, '') }}
                        </div>
                    </a>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-center mb-2">
                            @if(!empty($course->has_preview_lessons))
                            <span
                                class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded">HỌC THỬ</span>
                            @else
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded">KHÓA HỌC</span>
                            @endif
                            <span class="text-green-600 font-bold">{{ number_format($course->price) }}đ</span>
                        </div>

                        <a href="{{ route('courses.show', $course->id) }}" class="group">
                            <h3
                                class="text-xl font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition duration-200">
                                {{ $course->title }}
                            </h3>
                        </a>

                        <p class="text-gray-600 text-sm mb-4 flex-1">
                            {{ Str::limit($course->description, 100) }}
                        </p>

                        @guest
                        <a href="{{ route('login') }}"
                            class="flex w-full justify-center bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition shadow-md">
                            Đăng nhập để mua / đăng ký
                        </a>
                        @else
                        @if(auth()->user()->isAdmin() || auth()->user()->enrolledCourses->contains($course->id))
                        <a href="{{ route('learning.view', $course) }}"
                            class="flex w-full justify-center bg-emerald-600 text-white font-bold py-3 rounded-lg hover:bg-emerald-700 transition shadow-md">
                            Vào học
                        </a>
                        @elseif((float) $course->price > 0)
                        <form action="{{ route('courses.checkout', $course) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition shadow-md active:transform active:scale-95">
                                Thanh toán {{ number_format($course->price) }}đ
                            </button>
                        </form>
                        @else
                        <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition shadow-md active:transform active:scale-95">
                                Đăng ký miễn phí
                            </button>
                        </form>
                        @endif
                        @endguest
                    </div>
                </div>
                @empty
                <div class="md:col-span-3 text-center py-16 px-6 bg-white rounded-xl shadow">
                    <p class="text-gray-600 text-lg">
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
            <div class="mt-10 flex justify-center">
                {{ $courses->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>