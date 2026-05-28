<x-app-layout>
    <div class="container-fluid p-0 home-content">
        <div class="subpage-slide-blue">
            <div class="container">
                <h1>Khóa học của tôi</h1>
            </div>
        </div>

        <div class="breadcrumb-container">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Khóa học của tôi</li>
                </ol>
            </div>
        </div>

        <div class="container mb-5" id="my-courses">
            <div class="row">
                @forelse($courses as $course)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="course-block mx-auto">
                        <a href="{{ route('courses.learn', $course) }}" class="c-view">
                            <main>
                                <div class="course-thumb-placeholder">{{ Str::upper(Str::limit($course->title, 1, '')) }}</div>
                                <div class="col-md-12">
                                    <h6 class="course-title">{{ $course->title }}</h6>
                                </div>
                            </main>
                            <footer>
                                <div class="c-row">
                                    <div class="col-12">
                                        <div class="progress mt-2" style="height: 8px;">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ $course->progress }}%"
                                                aria-valuenow="{{ $course->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">Tiến độ: {{ $course->progress }}%</small>
                                    </div>
                                </div>
                            </footer>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <article class="container not-found-block">
                        <div class="row">
                            <div class="col-12 not-found-col text-center py-5">
                                <span><b>Chưa có khóa học</b></span>
                                <h3 class="mt-3">Bạn chưa đăng ký khóa nào</h3>
                                <a href="{{ route('catalog') }}" class="btn btn-ulearn-cview mt-3">Khám phá khóa học</a>
                            </div>
                        </div>
                    </article>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
