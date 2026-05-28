<x-app-layout>
    <div class="container-fluid p-0" style="margin-top: 60px;">
        <div class="bg-info text-white px-3 py-2 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <small class="text-uppercase font-weight-bold">Học thử miễn phí</small>
                <h1 class="h5 mb-0">{{ $course->title }}</h1>
            </div>
            <div>
                <a href="{{ route('courses.show', $course) }}" class="btn btn-light btn-sm mr-2">Mua / Đăng ký</a>
                @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Đăng nhập</a>
                @endguest
            </div>
        </div>

        <div class="row no-gutters">
            <div class="col-lg-9">
                @include('learning.partials.video-player', ['lesson' => $lesson, 'videoService' => $videoService])

                <div class="container py-4">
                    <a href="{{ route('courses.show', $course) }}" class="text-muted small"><i class="fa fa-arrow-left"></i> Về khóa học</a>
                    <h2 class="mt-2 h4">{{ $lesson->title }}</h2>
                    <div class="alert alert-info mt-3">
                        Bạn đang xem <strong>bài học thử</strong>. Đăng ký để mở {{ $totalInCourse }} bài và theo dõi tiến độ.
                    </div>

                    @if($lesson->content)
                    <div class="cv-course-container mb-4">{!! nl2br(e($lesson->content)) !!}</div>
                    @endif

                    @if($lesson->attachment_url || $lesson->attachments->isNotEmpty())
                    <div class="card">
                        <div class="card-header">Tài liệu</div>
                        <ul class="list-group list-group-flush">
                            @if($lesson->attachment_url)
                            <li class="list-group-item">
                                <a href="{{ $lesson->attachment_url }}" target="_blank" rel="noopener">Tệp đính kèm chính</a>
                            </li>
                            @endif
                            @foreach($lesson->attachments as $file)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $file->title }}</span>
                                <a href="{{ $file->file_url }}" target="_blank" rel="noopener">Mở</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 learning-sidebar">
                <div class="p-3 border-bottom bg-white">
                    <strong class="small text-uppercase">Bài mở thử</strong>
                    <p class="small text-muted mb-0">{{ $previewLessons->count() }} / {{ $totalInCourse }} bài</p>
                </div>
                @foreach($previewLessons as $item)
                <a href="{{ route('courses.preview', [$course, $item]) }}"
                    class="learning-lesson-item {{ $lesson->id === $item->id ? 'active' : '' }}">
                    <span class="badge badge-success mr-1">THỬ</span> {{ $item->order }}. {{ $item->title }}
                </a>
                @endforeach
                @if($totalInCourse > $previewLessons->count())
                <div class="p-3 border-top">
                    <p class="small text-muted">+{{ $totalInCourse - $previewLessons->count() }} bài sau đăng ký.</p>
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-ulearn-cview btn-block btn-sm">Đăng ký khóa</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
