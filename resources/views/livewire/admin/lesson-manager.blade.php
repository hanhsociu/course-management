<div class="py-14 bg-gradient-to-b from-indigo-50 to-white min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-100 p-3 rounded-xl shadow-sm flex items-center justify-center">
                    <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422A12.083 12.083 0 0112 20.944a12.083 12.083 0 01-6.16-10.366L12 14z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800">Danh sách bài học <span class="font-normal text-base text-slate-500">({{ $lessons->count() }})</span></h3>
            </div>
            <button wire:click="openModal"
                class="flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 px-6 py-3 text-white rounded-full shadow-lg font-semibold transition-all duration-200 tracking-wider">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M12 4v16M4 12h16"/>
                </svg>
                Thêm bài mới
            </button>
        </div>

        <!-- LESSONS LIST -->
        <div class="bg-white rounded-2xl shadow-lg divide-y overflow-hidden">
            @forelse($lessons as $lesson)
            <div class="group transition flex justify-between items-center px-6 py-5 hover:bg-indigo-50">
                <div class="flex items-center space-x-5">
                    <span class="bg-indigo-50 text-indigo-600 w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-lg shadow mr-2 group-hover:bg-indigo-200 transition">
                        {{ $lesson->order }}
                    </span>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-0.5">
                            <h4 class="font-bold text-gray-800 text-base">{{ $lesson->title }}</h4>
                            @if($lesson->is_preview)
                            <span class="text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded shadow">Học thử</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 max-w-md">{{ Str::limit(strip_tags($lesson->content), 90) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 opacity-80 group-hover:opacity-100 transition">
                    <button
                        wire:click="edit({{ $lesson->id }})"
                        class="hover:bg-indigo-100 rounded-full p-2 transition focus:outline-none"
                        title="Sửa"
                    >
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path d="M15.232 5.232l3.536 3.536M16.732 3.732a2.5 2.5 0 013.536 3.536L7.5 20.036H4v-3.5L16.732 3.732z"></path>
                        </svg>
                    </button>
                    <button
                        onclick="if(confirm('Bạn có chắc muốn xoá bài học này không?') === false) return false;"
                        wire:click="delete({{ $lesson->id }})"
                        class="hover:bg-rose-100 rounded-full p-2 transition focus:outline-none"
                        title="Xoá"
                    >
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m5 0H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-14 text-center text-slate-400 text-lg">Chưa có bài học nào. Hãy thêm bài học đầu tiên!</div>
            @endforelse
        </div>

        <!-- MODAL MODERN -->
        @if($isModalOpen)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black/50 backdrop-blur-sm transition">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 animate-fade-in-up">
                <button wire:click="$set('isModalOpen', false)" class="absolute top-3 right-3 text-gray-400 hover:text-indigo-600 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="px-10 py-8">
                    <h2 class="text-2xl font-extrabold text-indigo-700 text-center mb-6">{{ $lessonId ? 'Sửa bài học' : 'Thêm bài học mới' }}</h2>
                    <form wire:submit.prevent="store" class="space-y-6">

                        <div>
                            <label class="block text-sm font-semibold mb-1 text-slate-700">Tiêu đề bài học <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="title"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition text-[15px] bg-slate-50"
                                placeholder="Nhập tiêu đề bài học...">
                            @error('title')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-slate-700">Thứ tự hiển thị <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="order"
                                    class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition bg-slate-50"
                                    placeholder="VD: 1">
                                @error('order')
                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1 text-slate-700">Nội dung bài học <span class="text-red-500">*</span></label>
                            <textarea wire:model="content" rows="6"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-300 transition bg-slate-50"
                                placeholder="Soạn nội dung bài học..."></textarea>
                            @error('content')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <label class="flex items-center gap-3 mt-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_preview" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2 transition">
                            <span class="text-sm text-gray-700">Cho phép <strong>học thử</strong> (bài học hiển thị công khai, chưa cần đăng ký)</span>
                        </label>

                        <div class="flex justify-end gap-4 pt-4">
                            <button type="button" wire:click="$set('isModalOpen', false)"
                                class="px-5 py-2 bg-slate-100 text-slate-600 rounded-lg shadow hover:bg-slate-200 transition font-semibold">
                                Hủy
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-bold shadow hover:from-indigo-700 hover:to-purple-700 transition">
                                {{ $lessonId ? 'Cập nhật' : 'Tạo mới' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
