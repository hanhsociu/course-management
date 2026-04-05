<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center rounded-full bg-gradient-to-tr from-indigo-400 to-purple-500 text-white w-12 h-12 shadow-lg">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14V21"/><path d="M22.5 12.5A9 9 0 0 1 2.5 12.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <h2 class="font-extrabold text-3xl text-indigo-900 tracking-tight">Quản lý người dùng</h2>
            </div>
            <div>
                {{-- Nơi đặt các action nếu muốn thêm --}}
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-tr from-indigo-50 via-white to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('admin.users') }}"
                class="mb-10 flex flex-col rounded-2xl shadow-2xl md:flex-row gap-4 md:gap-8 md:items-end bg-white/90 backdrop-blur-lg p-8 border border-gray-100 transition-all duration-200">
                <div class="flex-1">
                    <label for="q" class="block text-xs font-semibold uppercase tracking-widest text-gray-700 mb-2">Tìm kiếm</label>
                    <div class="relative group">
                        <input type="search" id="q" name="q" value="{{ request('q') }}" maxlength="255"
                            placeholder="Tên hoặc email người dùng..."
                            class="w-full bg-gradient-to-r from-indigo-50 to-white text-gray-700 border border-gray-200 py-3 px-5 pr-12 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300 outline-none shadow transition duration-200 group-hover:border-indigo-300">
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 21l-3.87-3.87" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-64">
                    <label for="role" class="block text-xs font-semibold uppercase tracking-widest text-gray-700 mb-2">Vai trò</label>
                    <select id="role" name="role"
                        class="w-full bg-white border border-gray-200 py-3 px-4 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300 outline-none shadow transition duration-200">
                        <option value="">Tất cả</option>
                        <option value="user" @selected(request('role') === 'user')>Học viên</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    </select>
                </div>
                <div class="flex gap-2 mt-2 md:mt-0">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-tr from-indigo-500 to-indigo-700 hover:from-indigo-700 hover:to-indigo-500 text-white text-sm font-extrabold rounded-xl shadow-xl transition duration-200 focus:outline-none focus:ring-4 focus:ring-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                        Lọc
                    </button>
                    @if(request()->hasAny(['q', 'role']))
                    <a href="{{ route('admin.users') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-600 bg-gradient-to-tr from-white via-gray-50 to-white hover:bg-gray-100 font-semibold rounded-xl shadow-md transition duration-200 focus:outline-none">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        Xóa lọc
                    </a>
                    @endif
                </div>
            </form>

            <div class="bg-white/90 backdrop-blur-lg border border-gray-100 overflow-hidden shadow-xl rounded-2xl transition-all duration-200">
                <div class="p-0 overflow-x-auto">
                    <table class="w-full min-w-[900px] text-sm text-left border-separate [border-spacing:0]">
                        <thead>
                            <tr class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b-2 border-indigo-100">
                                <th class="py-5 px-4 font-extrabold text-indigo-700 uppercase tracking-wider">ID</th>
                                <th class="py-5 px-4 font-extrabold text-indigo-700 uppercase tracking-wider">Tên</th>
                                <th class="py-5 px-4 font-extrabold text-indigo-700 uppercase tracking-wider">Email</th>
                                <th class="py-5 px-4 font-extrabold text-indigo-700 uppercase tracking-wider">Vai trò</th>
                                <th class="py-5 px-4 font-extrabold text-indigo-700 uppercase tracking-wider">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-b last:border-b-0 transition hover:bg-indigo-100/40 group">
                                <td class="py-5 px-4 font-mono text-base text-indigo-500 font-bold align-middle">{{ $user->id }}</td>
                                <td class="py-5 px-4 text-gray-900 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-200 to-purple-200 flex items-center justify-center font-extrabold uppercase text-lg text-indigo-700 shadow">
                                            {{ Str::substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-lg">{{ $user->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-gray-700 align-middle">{{ $user->email }}</td>
                                <td class="py-5 px-4 align-middle">
                                    <span
                                        class="inline-block px-3 py-1 rounded-2xl text-xs font-bold shadow-sm transition
                                            {{ $user->role === 'admin'
                                                ? 'bg-gradient-to-tr from-purple-500 to-indigo-400 text-white'
                                                : 'bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800' }}">
                                        {{ $user->role === 'admin' ? 'Admin' : 'Học viên' }}
                                    </span>
                                </td>
                                <td class="py-5 px-4 text-gray-500 align-middle whitespace-nowrap">
                                    <span class="inline-block rounded bg-indigo-50 px-2 py-1 text-xs font-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-400 font-semibold bg-gradient-to-tr from-white to-indigo-50">Không có người dùng nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                <div class="px-8 pb-8 pt-3 flex justify-center border-t border-gray-100 bg-gradient-to-t from-white to-indigo-50">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
