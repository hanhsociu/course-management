<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <p class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-1">Quản trị hệ thống</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-2">
                <span class="bg-gradient-to-r from-indigo-600 via-violet-500 to-emerald-400 bg-clip-text text-transparent">Bảng điều khiển</span>
                <svg class="w-6 h-6 text-indigo-400 opacity-70" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M17 3v2a7 7 0 010 14v2"></path><path d="M7 21v-2a7 7 0 010-14V3"></path></svg>
            </h2>
        </div>
    </x-slot>

    <div class="py-10 sm:py-14 bg-gradient-to-br from-indigo-50/60 via-white to-white min-h-[80vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-10">
            <!-- Stats Cards -->
            <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-white shadow-xl ring-1 ring-indigo-50 p-6 hover:scale-[1.015] transition-transform group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 bg-indigo-100 rounded-full p-2">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422A12.083 12.083 0 01 12 20.944a12.083 12.083 0 01-6.16-10.366L12 14z"></path></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Tổng học viên</p>
                    <p class="text-4xl font-black tabular-nums text-slate-900 group-hover:text-indigo-600 transition">{{ $stats->total_students }}</p>
                    <div class="mt-4 h-1.5 w-14 rounded-full bg-gradient-to-r from-indigo-500 to-blue-400"></div>
                </div>
                <div class="rounded-2xl bg-white shadow-xl ring-1 ring-emerald-50 p-6 hover:scale-[1.015] transition-transform group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 bg-emerald-100 rounded-full p-2">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M8 15l4-4 4 4"></path></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Khóa học</p>
                    <p class="text-4xl font-black tabular-nums text-slate-900 group-hover:text-emerald-600 transition">{{ $stats->total_courses }}</p>
                    <div class="mt-4 h-1.5 w-14 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                </div>
                <div class="rounded-2xl bg-white shadow-xl ring-1 ring-amber-50 p-6 hover:scale-[1.015] transition-transform group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 bg-amber-100 rounded-full p-2">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="8"/></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Lượt đăng ký</p>
                    <p class="text-4xl font-black tabular-nums text-slate-900 group-hover:text-amber-500 transition">{{ $stats->total_enrollments }}</p>
                    <div class="mt-4 h-1.5 w-14 rounded-full bg-gradient-to-r from-amber-400 to-yellow-400"></div>
                </div>
                <div class="rounded-2xl bg-white shadow-xl ring-1 ring-rose-50 p-6 hover:scale-[1.015] transition-transform group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 bg-rose-100 rounded-full p-2">
                        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 12.414C12.633 11.633 11.367 11.633 10.586 12.414L6.343 16.657" /></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Doanh thu</p>
                    <p class="text-4xl font-black tabular-nums text-slate-900 group-hover:text-rose-500 transition">{{ $stats->total_revenue }}</p>
                    <div class="mt-4 h-1.5 w-14 rounded-full bg-gradient-to-r from-rose-400 to-pink-400"></div>
                </div>
            </div>

            <!-- Recent Enrollments Table -->
            <div class="overflow-hidden rounded-2xl border-none shadow-xl bg-white/95 ring-1 ring-indigo-50">
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/70">
                    <div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                            <span>Đăng ký mới nhất</span>
                        </h3>
                        <p class="mt-0.5 text-sm text-slate-500">Tổng quan nhanh hoạt động gần đây.</p>
                    </div>
                    <a href="{{ route('admin.enrollments') }}" class="text-xs rounded-full px-3 py-1.5 bg-indigo-50 text-indigo-500 hover:bg-indigo-100 hover:text-indigo-700 transition font-semibold">Xem tất cả</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm font-medium">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-indigo-600">
                                <th class="px-6 py-3">Học viên</th>
                                <th class="px-6 py-3">Khóa học</th>
                                <th class="px-6 py-3">Ngày đăng ký</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($stats->recent_enrollments as $enroll)
                            <tr class="hover:bg-indigo-50/40 transition group">
                                <td class="px-6 py-3.5 font-semibold text-slate-900 flex items-center gap-2">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 font-bold ring-2 ring-indigo-100 text-xs uppercase">
                                        {{ mb_substr($enroll->student_name, 0, 1) }}
                                    </div>
                                    <span>{{ $enroll->student_name }}</span>
                                </td>
                                <td class="px-6 py-3.5 text-slate-600 truncate max-w-xs">
                                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $enroll->course_title }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 tabular-nums text-slate-500">
                                    {{ \Carbon\Carbon::parse($enroll->created_at)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center px-6 py-8 text-slate-400">Chưa có đăng ký gần đây.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
