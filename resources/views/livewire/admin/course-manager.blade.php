<div class="py-14 bg-gradient-to-b from-indigo-50 to-white min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        {{-- ALERT --}}
        @if (session()->has('message'))
        <div
            class="flex items-center gap-2 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl px-5 py-3 mb-6 shadow animate-fade-in-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 20.944a12.083 12.083 0 01-6.16-10.366L12 14z" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-800">Quản lý khóa học</h2>
            </div>

            <button wire:click="openModal"
                class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-full font-semibold text-sm shadow">
                <span class="text-xl">+</span>
                Thêm khóa học
            </button>
        </div>

        {{-- SEARCH & FILTER --}}
        <div class="flex flex-col md:flex-row md:items-end gap-4 mb-6">
            {{-- Search box --}}
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="search">Tìm kiếm khóa học</label>
                <input
                    id="search"
                    type="text"
                    placeholder="Nhập tên khóa học..."
                    wire:model.debounce.400ms="search"
                    class="w-full border border-slate-300 rounded px-3 py-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition"
                >
            </div>
            {{-- Bộ lọc giá (ví dụ: lọc khoảng giá) --}}
            <div class="flex gap-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="minPrice">Giá từ</label>
                    <input
                        id="minPrice"
                        type="number"
                        wire:model.debounce.400ms="minPrice"
                        class="border border-slate-300 rounded px-2 py-2 w-24 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition"
                        placeholder="Min"
                        min="0"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="maxPrice">Giá đến</label>
                    <input
                        id="maxPrice"
                        type="number"
                        wire:model.debounce.400ms="maxPrice"
                        class="border border-slate-300 rounded px-2 py-2 w-24 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition"
                        placeholder="Max"
                        min="0"
                    >
                </div>
            </div>
            {{-- Nếu có filter theo trạng thái, thêm ở đây --}}
            {{-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Trạng thái</label>
                <select id="status" wire:model="status"
                    class="border border-slate-300 rounded px-2 py-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition">
                    <option value="">Tất cả</option>
                    <option value="active">Đang mở</option>
                    <option value="inactive">Đã ẩn</option>
                </select>
            </div> --}}
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto rounded-2xl shadow border bg-white">
            <table class="w-full min-w-[700px] text-left">
                <thead class="bg-indigo-50 text-xs uppercase text-indigo-700">
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Tên</th>
                        <th class="p-4">Giá</th>
                        <th class="p-4 text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($courses as $course)
                    <tr class="border-b hover:bg-indigo-50">
                        <td class="p-4 text-gray-500">{{ $course->id }}</td>
                        <td class="p-4 font-semibold">{{ $course->title }}</td>
                        <td class="p-4">
                            {{ number_format($course->price, 0, ',', '.') }} đ
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('admin.courses.lessons', $course->id) }}"
                                    class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded text-xs">
                                    Bài học
                                </a>

                                <button wire:click="edit({{ $course->id }})"
                                    class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded text-xs">
                                    Sửa
                                </button>

                                <button onclick="confirm('Xóa khóa học này?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $course->id }})" wire:loading.attr="disabled"
                                    class="bg-rose-100 text-rose-600 px-3 py-1 rounded text-xs">

                                    <span wire:loading.remove wire:target="delete({{ $course->id }})">
                                        Xóa
                                    </span>
                                    <span wire:loading wire:target="delete({{ $course->id }})">
                                        ...
                                    </span>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-400">
                            Không có dữ liệu
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL --}}
        @if($isModalOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black/40"></div>

            <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-lg z-50 relative">

                <button wire:click="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-black">
                    ✕
                </button>

                <h2 class="text-xl font-bold mb-6 text-center">
                    {{ $courseId ? 'Sửa khóa học' : 'Thêm khóa học' }}
                </h2>

                <form wire:submit.prevent="store" class="space-y-4">

                    <div>
                        <label class="block text-sm font-semibold">Tên</label>
                        <input type="text" wire:model="title" class="w-full border rounded px-3 py-2">
                        @error('title')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold">Giá</label>
                        <input type="number" wire:model="price" class="w-full border rounded px-3 py-2">
                        @error('price')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold">Mô tả</label>
                        <textarea wire:model="description" class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-100 rounded">
                            Hủy
                        </button>

                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                            {{ $courseId ? 'Cập nhật' : 'Tạo' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
        @endif

    </div>
</div>
