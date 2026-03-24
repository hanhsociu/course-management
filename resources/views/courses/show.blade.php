<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">

                <div class="flex-1">
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $course->title }}</h1>
                    <p class="text-lg text-gray-600 mb-8">{{ $course->description }}</p>

                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Lộ trình học tập</h2>
                    <div class="border rounded-lg divide-y bg-gray-50">
                        @foreach($lessons as $lesson)
                        <div class="p-4 flex justify-between items-center">
                            <span class="text-gray-700">{{ $lesson->order }}. {{ $lesson->title }}</span>
                            @if($lesson->order <= 2) <span
                                class="text-xs font-bold bg-green-100 text-green-700 px-2 py-1 rounded">HỌC THỬ</span>
                                @else
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2V7a5 5 0 00-5-5zM7 7a3 3 0 016 0v2H7V7z">
                                    </path>
                                </svg>
                                @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full lg:w-80">
                    <div class="sticky top-8 p-6 border-2 border-indigo-600 rounded-2xl shadow-xl bg-white">
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($course->price) }}đ</div>
                        <p class="text-sm text-gray-500 mb-6 italic">Truy cập trọn đời • Cập nhật liên tục</p>

                        <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-1">
                                ĐĂNG KÝ NGAY
                            </button>
                        </form>

                        <ul class="mt-6 space-y-3 text-sm text-gray-600">
                            <li class="flex items-center">✅ Hỗ trợ 24/7</li>
                            <li class="flex items-center">✅ Cấp chứng chỉ hoàn thành</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>