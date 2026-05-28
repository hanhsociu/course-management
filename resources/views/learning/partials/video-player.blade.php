@php
/** @var \App\Models\Lesson $lesson */
/** @var \App\Services\LessonVideoService $videoService */
@endphp

@if($videoService->hasVideo($lesson))
    @if($videoService->isEmbedType($lesson) && $embedUrl = $videoService->embedUrl($lesson))
        <div class="embed-responsive embed-responsive-16by9 w-100 bg-dark">
            <iframe class="embed-responsive-item"
                src="{{ $embedUrl }}"
                allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                title="{{ $lesson->title }}"></iframe>
        </div>
    @elseif($playUrl = $videoService->playableUrl($lesson))
        <video class="w-100" controls playsinline preload="metadata" style="max-height: 70vh; background: #000;">
            <source src="{{ $playUrl }}">
            Trình duyệt không hỗ trợ video HTML5.
        </video>
    @else
        <div class="learning-player-wrap">
            <p class="m-0 text-muted"><i class="fa fa-exclamation-circle"></i> Không tải được video.</p>
        </div>
    @endif
@else
    <div class="learning-player-wrap">
        <p class="m-0"><i class="fa fa-video-slash"></i> Bài học chưa có video</p>
    </div>
@endif
