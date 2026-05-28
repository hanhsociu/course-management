<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePayment;
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
        $paymentStatuses = $this->getPaymentStatusesForEnrollments($enrollments->items());

        return view('admin.enrollments.index', compact('enrollments', 'courses', 'paymentStatuses'));
    }

    private function getPaymentStatusesForEnrollments(array $enrollments): array
    {
        if (empty($enrollments)) {
            return [];
        }

        $pairs = collect($enrollments)
            ->map(fn (Enrollment $row) => [
                'user_id' => $row->user_id,
                'course_id' => $row->course_id,
            ])
            ->unique(fn (array $item) => $item['user_id'].'-'.$item['course_id'])
            ->values();

        $payments = CoursePayment::query()
            ->where(function ($query) use ($pairs) {
                foreach ($pairs as $pair) {
                    $query->orWhere(function ($or) use ($pair) {
                        $or->where('user_id', $pair['user_id'])
                            ->where('course_id', $pair['course_id']);
                    });
                }
            })
            ->orderByDesc('id')
            ->get(['user_id', 'course_id', 'status']);

        $mapped = [];
        foreach ($payments as $payment) {
            $key = $payment->user_id.'-'.$payment->course_id;
            if (!isset($mapped[$key])) {
                $mapped[$key] = $payment->status;
            }
        }

        return $mapped;
    }
}
