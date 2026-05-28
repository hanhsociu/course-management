<x-app-layout>
    <div class="container-fluid p-0 home-content">
        <div class="subpage-slide-blue">
            <div class="container">
                <h1>{{ $course->title }}</h1>
                @if($course->short_description)
                <p class="title-sub-header mb-0">{{ $course->short_description }}</p>
                @endif
            </div>
        </div>

        <div class="breadcrumb-container">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($course->title, 50) }}</li>
                </ol>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="mb-4">
                        @if($thumbnailUrl)
                        <img src="{{ $thumbnailUrl }}" alt="{{ $course->title }}" class="img-fluid rounded w-100" style="max-height: 360px; object-fit: cover;">
                        @else
                        <div class="course-thumb-placeholder" style="height: 280px;">{{ Str::upper(Str::limit($course->title, 1, '')) }}</div>
                        @endif
                    </div>

                    <div class="cv-course-container mb-4">
                        <h4>Giới thiệu khóa học</h4>
                        @if($course->description)
                        <div class="text-muted">{!! nl2br(e($course->description)) !!}</div>
                        @else
                        <p class="text-muted mb-0">Chưa có mô tả chi tiết.</p>
                        @endif
                    </div>

                    @if($course->requirements)
                    <div class="cv-course-container mb-4">
                        <h5>Yêu cầu</h5>
                        <ul class="mb-0">
                            @foreach($course->requirements as $item)
                            <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($course->benefits)
                    <div class="cv-course-container mb-4">
                        <h5>Bạn sẽ học được</h5>
                        <ul class="mb-0">
                            @foreach($course->benefits as $item)
                            <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <h4 class="mt-4 mb-3">Nội dung khóa học</h4>
                    <p class="text-muted small">
                        <span class="badge badge-success">Học thử</span> — xem miễn phí.
                        <span class="badge badge-secondary ml-1"><i class="fa fa-lock"></i></span> — cần đăng ký/mua.
                    </p>

                    @foreach($course->sections as $section)
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">
                            {{ $section->title }}
                            @if($section->description)
                            <small class="d-block text-muted font-weight-normal">{{ $section->description }}</small>
                            @endif
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($section->lessons as $lessonItem)
                            @include('courses.partials.lesson-row', ['lessonItem' => $lessonItem])
                            @endforeach
                        </ul>
                    </div>
                    @endforeach

                    @if($orphanLessons->isNotEmpty())
                    <div class="card mb-3">
                        @if($course->sections->isNotEmpty())
                        <div class="card-header font-weight-bold">Bài học khác</div>
                        @endif
                        <ul class="list-group list-group-flush">
                            @foreach($orphanLessons as $lessonItem)
                            @include('courses.partials.lesson-row', ['lessonItem' => $lessonItem])
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                        <div class="card-body">
                            <div class="mb-3">
                                @if((float) $course->old_price > (float) $course->price)
                                <del class="text-muted">{{ number_format($course->old_price) }}đ</del>
                                @endif
                                <h3 class="cv-price mb-0">
                                    @if((float) $course->price > 0)
                                    {{ number_format($course->price) }}đ
                                    @else
                                    Miễn phí
                                    @endif
                                </h3>
                            </div>

                            <ul class="list-unstyled small text-muted mb-3">
                                @if($course->category)
                                <li class="mb-2"><i class="fa fa-folder text-info"></i> {{ $course->category->name }}</li>
                                @endif
                                @if($course->instructor)
                                <li class="mb-2"><i class="fa fa-user text-info"></i> {{ $course->instructor->name }}</li>
                                @endif
                                <li class="mb-2"><i class="fa fa-signal text-info"></i> {{ $levelLabel }}</li>
                                <li class="mb-2"><i class="fa fa-clock text-info"></i> {{ $course->duration_minutes }} phút</li>
                                <li class="mb-2"><i class="fa fa-book text-info"></i> {{ $course->total_lessons ?: $course->lessons()->where('status', true)->count() }} bài học</li>
                                <li class="mb-2"><i class="fa fa-globe text-info"></i> {{ $course->language }}</li>
                            </ul>

                            @php
                            $firstPreview = $course->lessons()->where('is_preview', true)->where('status', true)->orderBy('order')->first();
                            @endphp
                            @if($firstPreview)
                            <a href="{{ route('courses.preview', [$course, $firstPreview]) }}" class="btn btn-outline-info btn-block mb-2">
                                <i class="fa fa-play"></i> Xem học thử
                            </a>
                            @endif

                            @auth
                            @if($canLearn)
                            <a href="{{ route('courses.learn', $course) }}" class="btn btn-ulearn-cview btn-block">
                                {{ $isEnrolled ? 'Tiếp tục học' : 'Vào học ngay' }}
                            </a>
                            @elseif((float) $course->price > 0)
                            <form action="{{ route('courses.checkout', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-ulearn-cview btn-block">Thanh toán PayOS</button>
                            </form>
                            @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-ulearn-cview btn-block">Đăng ký miễn phí</button>
                            </form>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="btn btn-ulearn-cview btn-block">Đăng nhập để mua</a>
                            <p class="text-center small mt-2 mb-0">
                                Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a>
                            </p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
