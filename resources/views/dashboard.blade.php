<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0">Bảng điều khiển quản trị</h2>
        <p class="text-muted small mb-0">Tổng quan hệ thống khóa học</p>
    </x-slot>

    <div class="container-fluid p-0 home-content">
        <div class="container py-4 mb-5">
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <a href="{{ route('admin.courses') }}" class="ulearn-admin-card">
                        <p class="text-info small font-weight-bold text-uppercase mb-1">Nội dung</p>
                        <h5 class="font-weight-bold">Khóa học & bài học</h5>
                        <p class="small text-muted mb-0">Tạo, sửa và quản lý cấu trúc khóa.</p>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('admin.users') }}" class="ulearn-admin-card">
                        <p class="text-success small font-weight-bold text-uppercase mb-1">Tài khoản</p>
                        <h5 class="font-weight-bold">Người dùng</h5>
                        <p class="small text-muted mb-0">Học viên và quyền truy cập.</p>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('admin.enrollments') }}" class="ulearn-admin-card">
                        <p class="text-warning small font-weight-bold text-uppercase mb-1">Vận hành</p>
                        <h5 class="font-weight-bold">Đăng ký & thanh toán</h5>
                        <p class="small text-muted mb-0">Đối soát giao dịch PayOS.</p>
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center p-3">
                        <p class="small text-muted mb-1">Học viên</p>
                        <h3 class="text-info mb-0">{{ $stats->total_students }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center p-3">
                        <p class="small text-muted mb-1">Khóa học</p>
                        <h3 class="text-success mb-0">{{ $stats->total_courses }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center p-3">
                        <p class="small text-muted mb-1">Lượt đăng ký</p>
                        <h3 class="text-warning mb-0">{{ $stats->total_enrollments }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card text-center p-3">
                        <p class="small text-muted mb-1">Doanh thu</p>
                        <h3 class="mb-0">{{ $stats->total_revenue }}</h3>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Đăng ký mới nhất</strong>
                    <a href="{{ route('admin.enrollments') }}" class="btn btn-sm btn-outline-info">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Học viên</th>
                                <th>Khóa học</th>
                                <th>Ngày đăng ký</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats->recent_enrollments as $enroll)
                            <tr>
                                <td>{{ $enroll->student_name }}</td>
                                <td>{{ $enroll->course_title }}</td>
                                <td>{{ \Carbon\Carbon::parse($enroll->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Chưa có đăng ký gần đây.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
