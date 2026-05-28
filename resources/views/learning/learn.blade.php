<x-app-layout>
    <div class="container-fluid p-0" style="margin-top: 60px;">
        <div class="bg-dark text-white px-3 py-2 d-flex flex-wrap justify-content-between align-items-center">
            <div class="min-w-0">
                <small class="text-uppercase text-info">Đang học</small>
                <h1 class="h6 mb-0 text-truncate">{{ $course->title }}</h1>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('my-courses') }}" class="btn btn-outline-light btn-sm mr-1">Khóa của tôi</a>
                <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-light btn-sm">Chi tiết khóa</a>
            </div>
        </div>

        <div class="row no-gutters">
            <div class="col-lg-9">
                @include('learning.partials.video-player', ['lesson' => $lesson, 'videoService' => $videoService])

                <div class="container py-4">
                    <h2 class="h4">{{ $lesson->title }}</h2>
                    <p class="text-muted small mb-3">
                        @if($lesson->duration_seconds)
                        <i class="fa fa-clock"></i> {{ gmdate('H:i:s', $lesson->duration_seconds) }}
                        @endif
                        · Cập nhật {{ $lesson->updated_at->format('d/m/Y') }}
                    </p>

                    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                        <livewire:learning.mark-complete :lessonId="$lesson->id" :key="'btn-'.$lesson->id" />
                    </div>

                    @if($lesson->content)
                    <div class="cv-course-container mb-4">
                        <h5 class="mb-3">Nội dung bài học</h5>
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                    @endif

                    @if($lesson->attachment_url)
                    <div class="alert alert-light border mb-4">
                        <strong><i class="fa fa-paperclip"></i> Tệp đính kèm</strong>
                        <a href="{{ $lesson->attachment_url }}" class="btn btn-sm btn-outline-info ml-2" target="_blank" rel="noopener">Tải xuống</a>
                    </div>
                    @endif

                    @if($lesson->attachments->isNotEmpty())
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">Tài liệu bổ sung</div>
                        <ul class="list-group list-group-flush">
                            @foreach($lesson->attachments as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $file->title }}</span>
                                <a href="{{ $file->file_url }}" class="btn btn-sm btn-info" target="_blank" rel="noopener">Mở</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 learning-sidebar p-0 border-left">
                @include('learning.partials.lesson-sidebar', [
                    'routeName' => 'courses.learn',
                ])
            </div>
        </div>
    </div>
</x-app-layout>
