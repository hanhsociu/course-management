<li class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div class="d-flex align-items-start">
        <span class="badge badge-light mr-2 mt-1">{{ $lessonItem->order }}</span>
        <div>
            <strong>{{ $lessonItem->title }}</strong>
            @if($lessonItem->duration_seconds)
            <small class="d-block text-muted">{{ gmdate('i:s', $lessonItem->duration_seconds) }}</small>
            @endif
        </div>
    </div>
    <div class="mt-2 mt-sm-0">
        @if($lessonItem->is_preview)
        <a href="{{ route('courses.preview', [$course, $lessonItem]) }}" class="btn btn-sm btn-success">
            <i class="fa fa-play"></i> Học thử
        </a>
        @else
        <span class="badge badge-secondary"><i class="fa fa-lock"></i> Sau đăng ký</span>
        @endif
    </div>
</li>
