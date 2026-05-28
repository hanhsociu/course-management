<a href="{{ route($routeName, ['course' => $course, 'lesson' => $item->id]) }}"
    class="learning-lesson-item {{ $lesson->id == $item->id ? 'active' : '' }} {{ $completedLessonIds->contains($item->id) ? 'completed' : '' }}">
    <span class="mr-2">
        @if($completedLessonIds->contains($item->id))
        <i class="fa fa-check-circle text-success"></i>
        @else
        <span class="badge badge-light">{{ $item->order }}</span>
        @endif
    </span>
    {{ $item->title }}
    @if($item->is_preview)
    <span class="badge badge-success badge-pill ml-1">Thử</span>
    @endif
</a>
