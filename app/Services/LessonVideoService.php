<?php

namespace App\Services;

use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonVideoService
{
    public function hasVideo(Lesson $lesson): bool
    {
        return filled($lesson->video_url) || filled($lesson->video_storage);
    }

    public function playableUrl(Lesson $lesson): ?string
    {
        if ($lesson->video_type === 'upload') {
            if ($lesson->video_storage) {
                return Storage::disk('public')->url($lesson->video_storage);
            }

            return $lesson->video_url;
        }

        return $lesson->video_url;
    }

    public function isEmbedType(Lesson $lesson): bool
    {
        return in_array($lesson->video_type, ['youtube', 'vimeo', 'external'], true);
    }

    public function embedUrl(Lesson $lesson): ?string
    {
        $url = $this->playableUrl($lesson);
        if (! $url) {
            return null;
        }

        return match ($lesson->video_type) {
            'youtube' => $this->youtubeEmbedUrl($url),
            'vimeo' => $this->vimeoEmbedUrl($url),
            'external' => $url,
            default => null,
        };
    }

    private function youtubeEmbedUrl(string $url): ?string
    {
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/'.$m[1];
        }

        return Str::contains($url, 'youtube.com/embed') ? $url : null;
    }

    private function vimeoEmbedUrl(string $url): ?string
    {
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return Str::contains($url, 'player.vimeo.com') ? $url : null;
    }
}
