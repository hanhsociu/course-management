<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6.5m6.5-2.6a8.991 8.991 0 01-13 0"/>
                    </svg>
                    {{ __('Đăng ký khóa học') }}
                </span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8 md:py-12 bg-gradient-to-tr from-indigo-50 via-white to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('admin.enrollments') }}"
                class="mb-6 flex flex-col md:flex-row gap-4 md:items-end bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg border border-gray-100">

                <div class="flex-1">
                    <label for="q" class="block text-xs font-semibold uppercase tracking-wide text-gray-700 mb-1">Tìm học viên</label>
                    <div class="relative">
                        <input type="search" id="q" name="q" value="{{ request('q') }}" maxlength="255"
                            placeholder="Nhập tên hoặc email học viên..."
                            class="w-full text-gray-700 border-2 border-gray-200 bg-gray-50 py-2 px-4 pr-10 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300 transition duration-150 outline-none shadow">
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 21l-3.87-3.87" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="md:w-72">
                    <label for="course_id" class="block text-xs font-semibold uppercase tracking-wide text-gray-700 mb-1">Khóa học</label>
                    <select id="course_id" name="course_id"
                        class="w-full text-gray-700 border-2 border-gray-200 bg-gray-50 py-2 px-3 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300 transition duration-150 outline-none shadow">
                        <option value="">Tất cả khóa</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->id }}" @selected((string)request('course_id') === (string)$c->id)>
                            {{ $c->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow transition duration-150 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Lọc
                    </button>
                    @if(request()->hasAny(['q', 'course_id']))
                        <a href="{{ route('admin.enrollments') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-600 text-sm font-medium hover:bg-gray-100 transition">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Xóa lọc
                        </a>
                    @endif
                </div>
            </form>

            <div class="bg-white/95 overflow-hidden shadow-xl rounded-xl border border-gray-100">
                <div class="p-0 md:p-6 overflow-x-auto">
                    <table class="min-w-full text-left text-[15px] border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-indigo-50 to-white border-b border-gray-200">
                                <th class="p-4 font-bold uppercase tracking-wider text-indigo-900 text-xs">Học viên</th>
                                <th class="p-4 font-bold uppercase tracking-wider text-indigo-900 text-xs">Email</th>
                                <th class="p-4 font-bold uppercase tracking-wider text-indigo-900 text-xs">Khóa học</th>
                                <th class="p-4 font-bold uppercase tracking-wider text-indigo-900 text-xs">Thanh toán</th>
                                <th class="p-4 font-bold uppercase tracking-wider text-indigo-900 text-xs">Ngày đăng ký</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $row)
                            <tr class="border-b transition hover:bg-indigo-50/60">
                                <td class="p-4 font-semibold text-gray-800 flex items-center gap-2">
                                    <div class="bg-indigo-500/10 p-2 rounded-full">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9A3.75 3.75 0 1112 5.25 3.75 3.75 0 0115.75 9zM4.5 19.5A7.5 7.5 0 0119.5 19.5z"/>
                                        </svg>
                                    </div>
                                    {{ $row->user?->name ?? '—' }}
                                </td>
                                <td class="p-4 text-gray-600">
                                    <span class="inline-block bg-gray-100 rounded px-2 py-1 text-xs">
                                        {{ $row->user?->email ?? '—' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center bg-indigo-50 px-2 py-1 rounded text-indigo-700 text-xs font-medium">
                                        <svg class="w-4 h-4 mr-1 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V8.25A2.25 2.25 0 016.25 6h11.5A2.25 2.25 0 0120 8.25V19.5l-8 2-8-2z"/>
                                        </svg>
                                        {{ $row->course?->title ?? '—' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @php
                                        $key = $row->user_id . '-' . $row->course_id;
                                        $paymentStatus = $paymentStatuses[$key] ?? null;
                                    @endphp

                                    @if($paymentStatus === 'paid')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                                            Đã thanh toán
                                        </span>
                                    @elseif($paymentStatus === 'pending')
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200">
                                            Chờ thanh toán
                                        </span>
                                    @elseif($paymentStatus === 'cancelled')
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 ring-1 ring-rose-200">
                                            Đã hủy
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 ring-1 ring-slate-200">
                                            Miễn phí / Admin cấp quyền
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-500 font-mono text-[13px]">
                                    {{ $row->created_at?->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-400 text-base font-semibold bg-indigo-50/60">
                                    <svg class="w-10 h-10 mx-auto mb-3 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m0-4h.01M21 12.72a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Chưa có bản ghi đăng ký nào.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($enrollments->hasPages())
                <div class="px-2 md:px-6 pb-6 flex items-center justify-between">
                    <div class="text-sm text-gray-600 hidden md:block">
                        Trang {{ $enrollments->currentPage() }} / {{ $enrollments->lastPage() }}
                    </div>
                    <div>
                        {{ $enrollments->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
