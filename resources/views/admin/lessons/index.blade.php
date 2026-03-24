<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.courses') }}" class="hover:text-indigo-600">Khóa học</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-bold">{{ $course->title }}</span>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý bài học') }}
        </h2>
    </x-slot>

    <livewire:admin.lesson-manager :courseId="$course->id" />
</x-app-layout>