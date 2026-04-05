<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Quản trị</p>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                {{ __('Bảng điều khiển') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-soft ring-1 ring-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tổng học viên</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900">{{ $stats->total_students }}</p>
                    <div class="mt-3 h-1 w-12 rounded-full bg-blue-500"></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-soft ring-1 ring-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Khóa học</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900">{{ $stats->total_courses }}</p>
                    <div class="mt-3 h-1 w-12 rounded-full bg-emerald-500"></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-soft ring-1 ring-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lượt đăng ký</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900">{{ $stats->total_enrollments }}</p>
                    <div class="mt-3 h-1 w-12 rounded-full bg-amber-500"></div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-soft ring-1 ring-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Doanh thu</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900">{{ $stats->total_revenue }}</p>
                    <div class="mt-3 h-1 w-12 rounded-full bg-rose-500"></div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-soft ring-1 ring-slate-100">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-base font-bold text-slate-900">5 lượt đăng ký gần nhất</h3>
                    <p class="mt-0.5 text-sm text-slate-500">Tổng quan nhanh hoạt động gần đây.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <th class="px-6 py-3">Học viên</th>
                                <th class="px-6 py-3">Khóa học</th>
                                <th class="px-6 py-3">Ngày đăng ký</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($stats->recent_enrollments as $enroll)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-3.5 font-medium text-slate-900">{{ $enroll->student_name }}</td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $enroll->course_title }}</td>
                                <td class="px-6 py-3.5 tabular-nums text-slate-500">{{ $enroll->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>