<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bảng Điều Khiển Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Tổng Học Viên</p>
                    <p class="text-2xl font-semibold">{{ $stats->total_students }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Khóa Học</p>
                    <p class="text-2xl font-semibold">{{ $stats->total_courses }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Lượt Đăng Ký</p>
                    <p class="text-2xl font-semibold">{{ $stats->total_enrollments }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Doanh Thu</p>
                    <p class="text-2xl font-semibold">{{ $stats->total_revenue }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold mb-4">5 Lượt đăng ký gần nhất</h3>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 border">Học viên</th>
                                <th class="p-3 border">Khóa học</th>
                                <th class="p-3 border">Ngày đăng ký</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats->recent_enrollments as $enroll)
                            <tr>
                                <td class="p-3 border">{{ $enroll->student_name }}</td>
                                <td class="p-3 border">{{ $enroll->course_title }}</td>
                                <td class="p-3 border">{{ $enroll->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>