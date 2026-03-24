<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Khóa học của tôi') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($courses->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow text-center">
                <p class="text-gray-500 mb-4">Bạn chưa đăng ký khóa học nào.</p>
                <a href="{{ route('catalog') }}" class="text-indigo-600 font-bold hover:underline">Khám phá ngay →</a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
                    <div class="h-40 bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold">
                        {{ Str::limit($course->title, 1, '') }}
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>

                        <div class="mt-2 mb-6">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-500">Tiến độ học tập</span>
                                <span class="text-indigo-600 font-bold">{{ $course->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $course->progress }}%"></div>
                            </div>
                        </div>

                        <a href="{{ route('learning.view', $course->id) }}"
                            class="mt-auto w-full bg-gray-800 text-white text-center font-bold py-3 rounded-lg hover:bg-black transition">
                            Tiếp tục học
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>