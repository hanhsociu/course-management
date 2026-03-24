<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm" role="alert">
            {{ session('message') }}
        </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Quản lý nội dung khóa học</h2>
            <button wire:click="openModal()"
                class="bg-indigo-600 text-white px-5 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
                + Thêm mới
            </button>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 uppercase text-xs font-bold text-gray-600">
                        <th class="p-4 border-b">ID</th>
                        <th class="p-4 border-b">Tên khóa học</th>
                        <th class="p-4 border-b">Giá tiền</th>
                        <th class="p-4 border-b text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($courses as $course)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 border-b text-sm text-gray-500">{{ $course->id }}</td>
                        <td class="p-4 border-b font-medium">{{ $course->title }}</td>
                        <td class="p-4 border-b">{{ number_format($course->price, 0, ',', '.') }} đ</td>
                        <td class="p-4 border-b text-center space-x-3">
                            <a href="{{ route('admin.courses.lessons', $course->id) }}" wire:navigate
                                class="text-green-600 hover:text-green-900 font-bold mr-2">
                                Bài học
                            </a>

                            <button wire:click="edit({{ $course->id }})" class="text-indigo-600 ...">Sửa</button>
                            <button wire:click="delete({{ $course->id }})" class="text-red-600 ...">Xóa</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
            <div class="bg-white p-8 rounded-xl shadow-2xl z-50 w-full max-w-lg mx-4">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">
                    {{ $courseId ? 'Chỉnh sửa khóa học' : 'Tạo khóa học mới' }}
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tên khóa học</label>
                        <input type="text" wire:model="title"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Giá tiền (VNĐ)</label>
                        <input type="number" wire:model="price"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mô tả khóa học</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <button wire:click="closeModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Hủy bỏ</button>
                    <button wire:click="store()"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-indigo-700 transition">
                        {{ $courseId ? 'Lưu thay đổi' : 'Tạo ngay' }}
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>