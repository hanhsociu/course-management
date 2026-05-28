<x-app-layout>
    <div class="container-fluid p-0 home-content">
        <div class="homepage-slide-blue">
            <h1>Khám phá khóa học</h1>
            <span class="title-sub-header">Tìm kiếm, xem thử và đăng ký trong vài bước — thanh toán PayOS an toàn.</span>
            <form method="GET" action="{{ route('catalog') }}">
                <div class="searchbox-contrainer col-md-6 mx-auto">
                    <input name="q" type="text" class="searchbox d-none d-sm-inline-block"
                        placeholder="Tìm theo tên hoặc mô tả khóa học..."
                        value="{{ request('q') }}" maxlength="255">
                    <input name="q" type="text" class="searchbox d-inline-block d-sm-none"
                        placeholder="Tìm khóa học..." value="{{ request('q') }}" maxlength="255">
                    <button type="submit" class="searchbox-submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>

        <div class="container tab-content mt-4 mb-5">
            @if(request()->filled('q'))
            <p class="text-center mb-4">
                Kết quả cho: <strong>{{ request('q') }}</strong>
                — <a href="{{ route('catalog') }}">Xóa bộ lọc</a>
            </p>
            @endif

            <div class="row">
                @forelse($courses as $course)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="course-block mx-auto">
                        <a href="{{ route('courses.show', $course) }}">
                            <main>
                                <div class="course-thumb-placeholder">
                                    {{ Str::upper(Str::limit($course->title, 1, '')) }}
                                </div>
                                <div class="col-md-12">
                                    <h6 class="course-title">{{ $course->title }}</h6>
                                </div>
                                <div class="instructor-clist">
                                    <div class="col-md-12">
                                        <i class="fa fa-book"></i>&nbsp;
                                        <span>{{ Str::limit($course->description, 60) }}</span>
                                    </div>
                                </div>
                            </main>
                            <footer>
                                <div class="c-row">
                                    <div class="col-md-6 col-sm-6 col-6">
                                        @if((float) $course->price > 0)
                                        <h5 class="course-price">{{ number_format($course->price) }}đ</h5>
                                        @else
                                        <h5 class="course-price">Miễn phí</h5>
                                        @endif
                                    </div>
                                    <div class="col-md-5 offset-md-1 col-sm-5 offset-sm-1 col-5 offset-1 text-right">
                                        @if(!empty($course->has_preview_lessons))
                                        <span class="badge badge-success">Học thử</span>
                                        @endif
                                    </div>
                                </div>
                            </footer>
                        </a>
                        <div class="px-3 pb-3">
                            @guest
                            <a href="{{ route('login') }}" class="btn btn-ulearn btn-block btn-sm">Đăng nhập để đăng ký</a>
                            @else
                        @if(auth()->user()->canAccessCourseLearning($course))
                        <a href="{{ route('courses.learn', $course) }}" class="btn btn-ulearn btn-block btn-sm">Vào học</a>
                            @elseif((float) $course->price > 0)
                            <form action="{{ route('courses.checkout', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-ulearn btn-block btn-sm">Thanh toán {{ number_format($course->price) }}đ</button>
                            </form>
                            @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-ulearn btn-block btn-sm">Đăng ký miễn phí</button>
                            </form>
                            @endif
                            @endguest
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <article class="container not-found-block">
                        <div class="row">
                            <div class="col-12 not-found-col text-center py-5">
                                <h3>Chưa có khóa học phù hợp</h3>
                                <p class="mt-2">@if(request()->filled('q'))Thử từ khóa khác.@else Hệ thống đang cập nhật nội dung.@endif</p>
                            </div>
                        </div>
                    </article>
                </div>
                @endforelse
            </div>

            @if($courses->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $courses->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
