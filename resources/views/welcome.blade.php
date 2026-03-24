<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Khám phá các khóa học mới nhất</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($courses as $course)
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
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded">PHỔ
                                BIẾN</span>
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

                        <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition shadow-md active:transform active:scale-95">
                                Đăng ký ngay
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>