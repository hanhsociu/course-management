<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý người dùng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('admin.users') }}"
                class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-end bg-white p-4 rounded-lg shadow-sm">
                <div class="flex-1">
                    <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Tìm theo tên / email</label>
                    <input type="search" id="q" name="q" value="{{ request('q') }}" maxlength="255"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="sm:w-48">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                    <select id="role" name="role"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tất cả</option>
                        <option value="user" @selected(request('role') === 'user')>Học viên</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    </select>
                </div>
                <button type="submit"
                    class="inline-flex justify-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                    Lọc
                </button>
                @if(request()->hasAny(['q', 'role']))
                <a href="{{ route('admin.users') }}"
                    class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Xóa lọc
                </a>
                @endif
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse min-w-[640px]">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3 font-semibold text-gray-700">ID</th>
                                <th class="p-3 font-semibold text-gray-700">Tên</th>
                                <th class="p-3 font-semibold text-gray-700">Email</th>
                                <th class="p-3 font-semibold text-gray-700">Vai trò</th>
                                <th class="p-3 font-semibold text-gray-700">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $user->id }}</td>
                                <td class="p-3 font-medium">{{ $user->name }}</td>
                                <td class="p-3 text-gray-600">{{ $user->email }}</td>
                                <td class="p-3">
                                    <span
                                        class="px-2 py-0.5 rounded text-xs font-bold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $user->role === 'admin' ? 'Admin' : 'Học viên' }}
                                    </span>
                                </td>
                                <td class="p-3 text-gray-600">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">Không có người dùng nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                <div class="px-6 pb-6">{{ $users->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
