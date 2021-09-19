@extends(FRONT.'.education.layouts.master-user')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(72)??null])
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('front.education.users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-4 user-info">
                        <h2>{{ __('education.My Courses') }}</h2>
                        <div class="row">
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <a href="#">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="text-secondary"><small>Finished</small></em>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <a href="#">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="text-secondary"><small>Finished</small></em>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <a href="#">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="text-secondary"><small>Finished</small></em>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <a href="#">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="text-secondary"><small>Finished</small></em>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <a href="#">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="text-secondary"><small>Finished</small></em>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <a href="#">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="text-secondary"><small>Finished</small></em>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <a href="#">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="text-secondary"><small>Finished</small></em>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <h2 class="mt-5">{{ __('education.Recommended for you') }}</h2>
                        <div class="row">
                            <div class="col-md-4 col-lg-2 mb-4">
                                <a href="#">
                                    <div class="bg-light course-profile p-2 text-center">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="bg-primary px-2 text-white rounded-pill"><small>{{ __('education.Buy Now') }}</small></em>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 col-lg-2 mb-4">
                                <a href="#">
                                    <div class="bg-light course-profile p-2 text-center">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <h5 class="mt-3">PMP Course</h5>
                                        <em class="bg-primary px-2 text-white rounded-pill"><small>{{ __('education.Buy Now') }}</small></em>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
