<div class="py-12">
    <div class="max-max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium">Danh sách bài học ({{ $lessons->count() }})</h3>
            <button wire:click="openModal()"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + Thêm bài mới
            </button>
        </div>

        <div class="bg-white shadow rounded-lg divide-y">
            @forelse($lessons as $lesson)
            <div class="p-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <span
                        class="bg-gray-100 text-gray-600 w-8 h-8 rounded-full flex items-center justify-center mr-4 font-bold">
                        {{ $lesson->order }}
                    </span>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $lesson->title }}</h4>
                        <p class="text-sm text-gray-500">{{ Str::limit($lesson->content, 100) }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button wire:click="edit({{ $lesson->id }})"
                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded">Sửa</button>
                    <button wire:click="delete({{ $lesson->id }})"
                        class="p-2 text-red-600 hover:bg-red-50 rounded">Xóa</button>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-gray-500">Chưa có bài học nào. Hãy thêm bài học đầu tiên!</div>
            @endforelse
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-2xl mx-4">
                <h2 class="text-xl font-bold mb-4">{{ $lessonId ? 'Sửa bài học' : 'Thêm bài học mới' }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Tiêu đề bài học</label>
                        <input type="text" wire:model="title" class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Thứ tự hiển thị</label>
                            <input type="number" wire:model="order" class="w-full border-gray-300 rounded-lg shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nội dung bài học</label>
                        <textarea wire:model="content" rows="6"
                            class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-gray-600">Hủy</button>
                    <button wire:click="store()"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-indigo-700">
                        Lưu bài học
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>