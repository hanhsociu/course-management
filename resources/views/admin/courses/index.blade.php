<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản Lý Khóa Học') }}
        </h2>
    </x-slot>

    <livewire:admin.course-manager />

</x-app-layout>