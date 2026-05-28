<x-app-layout>
    <div class="container-fluid p-0" style="margin-top: 60px;">
        <div class="row no-gutters">
            <div class="col-lg-9">
                <div class="learning-player-wrap">
                    @if($lesson->video_url)
                    <div class="embed-responsive embed-responsive-16by9 w-100">
                        <iframe class="embed-responsive-item" src="{{ $lesson->video_url }}" allowfullscreen title="{{ $lesson->title }}"></iframe>
                    </div>
                    @else
                    <p class="m-0"><i class="fa fa-video-slash"></i> Chưa có video — {{ $lesson->title }}</p>
                    @endif
                </div>

                <div class="container py-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                        <div>
                            <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('my-courses') }}" class="text-muted small">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                            <h2 class="mt-2">{{ $lesson->title }}</h2>
                            <p class="text-muted small mb-0">{{ $course->title }} · Cập nhật {{ $lesson->updated_at->format('d/m/Y') }}</p>
                        </div>
                        <livewire:learning.mark-complete :lessonId="$lesson->id" :key="'btn-'.$lesson->id" />
                    </div>
                    <div class="cv-course-container">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                </div>
            </div>

            <div class="col-lg-3 learning-sidebar p-0">
                <div class="p-3 border-bottom bg-white">
                    <strong class="d-block small text-uppercase text-muted">Tiến độ</strong>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar bg-info" style="width: {{ $progress }}%"></div>
                    </div>
                    <small class="text-muted">{{ $completedCount }}/{{ $totalLessons }} bài · {{ $progress }}%</small>
                </div>
                <div class="p-3 border-bottom bg-white">
                    <strong class="small text-uppercase">Nội dung khóa học</strong>
                </div>
                <div style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    @foreach($lessons as $item)
                    <a href="{{ route('learning.view', [$course->id, $item->id]) }}"
                        class="learning-lesson-item {{ $lesson->id == $item->id ? 'active' : '' }} {{ auth()->user()->completedLessons->contains($item->id) ? 'completed' : '' }}">
                        <span class="mr-2">
                            @if(auth()->user()->completedLessons->contains($item->id))
                            <i class="fa fa-check-circle"></i>
                            @else
                            <span class="badge badge-light">{{ $item->order }}</span>
                            @endif
                        </span>
                        {{ $item->title }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
