@php
/** @var \App\Models\Course $course */
/** @var \Illuminate\Support\Collection|\App\Models\Lesson[] $lessons */
/** @var \App\Models\Lesson $lesson */
/** @var \Illuminate\Support\Collection $completedLessonIds */
$routeName = $routeName ?? 'courses.learn';
@endphp

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
    @if($course->sections->isEmpty())
        @foreach($lessons as $item)
            @include('learning.partials.lesson-sidebar-item', ['item' => $item])
        @endforeach
    @else
    @foreach($course->sections as $section)
        <div class="px-3 py-2 bg-light border-bottom">
            <small class="font-weight-bold text-uppercase">{{ $section->title }}</small>
        </div>
        @foreach($section->lessons as $item)
            @include('learning.partials.lesson-sidebar-item', ['item' => $item])
        @endforeach
    @endforeach

    @php $orphan = $lessons->whereNull('section_id'); @endphp
    @if($orphan->isNotEmpty())
        @if($course->sections->isNotEmpty())
        <div class="px-3 py-2 bg-light border-bottom">
            <small class="font-weight-bold text-uppercase">Bài học khác</small>
        </div>
        @endif
        @foreach($orphan as $item)
            @include('learning.partials.lesson-sidebar-item', ['item' => $item])
        @endforeach
    @endif
    @endif
</div>
