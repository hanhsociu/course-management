# 🚀 Course Management System - Backend API Roadmap
**Mục tiêu dự án:** Xây dựng hệ thống backend cho nền tảng học trực tuyến (mô hình Udemy basic). Tập trung vào kiến trúc RESTful API, tối ưu truy vấn cơ sở dữ liệu và xử lý logic nghiệp vụ phức tạp.

## Phase 1: Architecture Setup & Database Design 
*Mục tiêu: Xây dựng nền tảng vững chắc, chuẩn hóa cấu trúc thư mục và database.*

- [ ] **Task 1: Project Initialization**
  - Khởi tạo project Laravel.
  - Setup Git repository & tạo file `.gitignore` chuẩn.
  - Cấu hình file `.env` cho Database.
- [ ] **Task 2: Database Schema & Migrations**
  - Tạo các bảng: `users`, `courses`, `lessons`, `enrollments`, `progresses`.
  - Thiết lập **Foreign Keys** và quy tắc `onDelete('cascade')`.
  - Thêm **Database Indexes** cho các trường thường xuyên tìm kiếm (vd: `course_id`, `user_id`).
  - Thêm **Soft Deletes** cho `courses` và `lessons` để không mất dữ liệu lịch sử.
- [ ] **Task 3: Eloquent Models & Relationships**
  - Định nghĩa strict types và relationships (`hasMany`, `belongsTo`, `belongsToMany`).
  - Áp dụng các scopes cơ bản (vd: `scopeActive()` cho khóa học).

## Phase 2: Authentication & Core API 
*Mục tiêu: Xây dựng luồng xác thực bảo mật và API danh sách khóa học chuẩn REST.*

- [ ] **Task 4: Authentication & Authorization**
  - Cài đặt **Laravel Sanctum** để cấp phát API Token.
  - Xây dựng API Login / Register.
  - Thêm cột `role` (admin/user) vào bảng users.
- [ ] **Task 5: Course Catalog API (Client)**
  - API `GET /api/courses` với tính năng **Pagination**.
  - Xử lý **Eager Loading** (`with('lessons')`) để khắc phục triệt để lỗi N+1 Query.
  - Sử dụng **API Resources (JsonResource)** để format dữ liệu trả về chuẩn mực, giấu đi các trường nhạy cảm trong DB.
  - Thêm tính năng Search & Lọc (Filter by category/status).

## Phase 3: Business Logic - Enrollment & Learning
*Mục tiêu: Xử lý logic lõi của hệ thống, đảm bảo tính toàn vẹn dữ liệu.*

- [ ] **Task 6: Course Enrollment System**
  - API `POST /api/enrollments`.
  - Sử dụng **FormRequests** để validate dữ liệu đầu vào.
  - Áp dụng **Database Transactions** (`DB::transaction`) khi xử lý đăng ký để đảm bảo không bị rác dữ liệu nếu lỗi giữa chừng.
- [ ] **Task 7: Middleware Security**
  - Tạo Middleware `CheckEnrollment`: Chặn user không được gọi API lấy nội dung Lesson nếu chưa enroll.
- [ ] **Task 8: Progress Tracking API**
  - API `POST /api/progress`: Đánh dấu hoàn thành bài học.
  - Áp dụng logic **Upsert** (Create or Update) để tránh duplicate record trong bảng `progresses`.
  - Viết logic tính toán % hoàn thành khóa học (Sử dụng Eloquent Aggregate methods hoặc Raw SQL để tối ưu).

## Phase 4: Admin Management Panel API 
*Mục tiêu: Cung cấp API quản trị an toàn và đầy đủ quyền hành.*

- [ ] **Task 9: Admin Routing & Middleware**
  - Tạo nhóm Route riêng biệt cho Admin.
  - Cấu hình Middleware `CheckAdminRole`.
- [ ] **Task 10: Course & Lesson CRUD (Admin)**
  - Xây dựng đầy đủ API Create/Read/Update/Delete.
  - Xử lý logic Upload ảnh thumbnail cho Course (Lưu trữ ở thư mục `storage` và tạo symlink).
- [ ] **Task 11: User & Enrollment Dashboard API**
  - API thống kê số lượng học viên, số lượt enroll.
  - Xem chi tiết tiến độ học tập của từng user.

## Phase 5: Optimization & Quality Assurance
*Mục tiêu: Biến project từ "chạy được" thành "chạy ngon" và sẵn sàng cho môi trường Production.*

- [ ] **Task 12: Caching**
  - Cấu hình Redis (hoặc File Cache).
  - Áp dụng Cache cho API danh sách khóa học (`GET /api/courses`) vì dữ liệu này ít thay đổi nhưng đọc nhiều.
- [ ] **Task 13: Error Handling & Logging**
  - Chuẩn hóa Custom Exception (trả về JSON error response đồng nhất cho toàn hệ thống).
  - Ghi log (Laravel Log) cho các thao tác quan trọng (vd: Có người đăng ký khóa học, Lỗi hệ thống).
- [ ] **Task 14: API Documentation**
  - Tích hợp Swagger hoặc viết file Postman Collection xịn sò đính kèm vào source code.
