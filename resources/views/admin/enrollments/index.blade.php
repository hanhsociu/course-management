<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Đăng ký khóa học') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('admin.enrollments') }}"
                class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-end bg-white p-4 rounded-lg shadow-sm">
                <div class="flex-1">
                    <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Tìm học viên (tên / email)</label>
                    <input type="search" id="q" name="q" value="{{ request('q') }}" maxlength="255"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="sm:w-72">
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Khóa học</label>
                    <select id="course_id" name="course_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả khóa</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->id }}" @selected((string)request('course_id') === (string)$c->id)>
                            {{ $c->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="inline-flex justify-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                    Lọc
                </button>
                @if(request()->hasAny(['q', 'course_id']))
                <a href="{{ route('admin.enrollments') }}"
                    class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Xóa lọc
                </a>
                @endif
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse min-w-[720px]">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3 font-semibold text-gray-700">Học viên</th>
                                <th class="p-3 font-semibold text-gray-700">Email</th>
                                <th class="p-3 font-semibold text-gray-700">Khóa học</th>
                                <th class="p-3 font-semibold text-gray-700">Ngày đăng ký</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $row)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium">{{ $row->user?->name ?? '—' }}</td>
                                <td class="p-3 text-gray-600">{{ $row->user?->email ?? '—' }}</td>
                                <td class="p-3">{{ $row->course?->title ?? '—' }}</td>
                                <td class="p-3 text-gray-600">
                                    {{ $row->created_at?->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500">Chưa có bản ghi đăng ký nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($enrollments->hasPages())
                <div class="px-6 pb-6">{{ $enrollments->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
