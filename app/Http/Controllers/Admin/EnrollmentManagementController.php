<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentManagementController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'q' => 'nullable|string|max:255',
        ]);

        $query = Enrollment::query()
            ->with(['user:id,name,email,role', 'course:id,title'])
            ->latest('created_at');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($q = trim((string) $request->input('q'))) {
            $query->whereHas('user', function ($qry) use ($q) {
                $qry->where('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        }

        $enrollments = $query->paginate(20)->withQueryString();
        $courses = Course::orderBy('title')->get(['id', 'title']);

        return view('admin.enrollments.index', compact('enrollments', 'courses'));
    }
}
