<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Services\CourseAccessService;
use App\Services\LessonVideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function __construct(
        protected CourseAccessService $access,
        protected LessonVideoService $videoService,
    ) {}

    public function show(Course $course)
    {
        $course->load([
            'category',
            'instructor:id,name,avatar',
            'sections' => fn ($q) => $q->where('status', true)->orderBy('order'),
            'sections.lessons' => fn ($q) => $q->where('status', true)->orderBy('order'),
        ]);

        $orphanLessons = $course->lessons()
            ->where('status', true)
            ->whereNull('section_id')
            ->orderBy('order')
            ->get();

        $isEnrolled = Auth::check() && $this->access->isEnrolled(Auth::user(), $course);
        $canLearn = Auth::check() && $this->access->canLearn(Auth::user(), $course);

        $this->loadEnrolledCoursesForAuthUser();

        return view('courses.show', [
            'course' => $course,
            'orphanLessons' => $orphanLessons,
            'isEnrolled' => $isEnrolled,
            'canLearn' => $canLearn,
            'levelLabel' => $this->levelLabel($course->level),
            'thumbnailUrl' => $this->thumbnailUrl($course),
        ]);
    }

    public function preview(Course $course, ?Lesson $lesson = null)
    {
        $previewLessons = $course->lessons()
            ->where('is_preview', true)
            ->where('status', true)
            ->orderBy('order')
            ->get();

        if ($previewLessons->isEmpty()) {
            return redirect()->route('courses.show', $course)
                ->with('info', 'Khóa học này chưa có bài học thử.');
        }

        $lesson = $lesson ?? $previewLessons->first();

        if ((int) $lesson->course_id !== (int) $course->id || ! $lesson->is_preview || ! $lesson->status) {
            abort(404);
        }

        $lesson->load('attachments');

        return view('learning.preview', [
            'course' => $course,
            'lesson' => $lesson,
            'previewLessons' => $previewLessons,
            'totalInCourse' => $course->lessons()->where('status', true)->count(),
            'videoService' => $this->videoService,
        ]);
    }

    private function levelLabel(?string $level): string
    {
        return match ($level) {
            'beginner' => 'Cơ bản',
            'intermediate' => 'Trung cấp',
            'advanced' => 'Nâng cao',
            default => 'Chưa phân loại',
        };
    }

    private function thumbnailUrl(Course $course): ?string
    {
        if (! $course->thumbnail) {
            return null;
        }

        if (str_starts_with($course->thumbnail, 'http')) {
            return $course->thumbnail;
        }

        return Storage::disk('public')->url($course->thumbnail);
    }

    private function loadEnrolledCoursesForAuthUser(): void
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $user->loadMissing('enrolledCourses');
        }
    }
}
