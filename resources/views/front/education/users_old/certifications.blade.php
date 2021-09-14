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
                        <h2>{{ __('education.Certifications') }}</h2>
                        <div class="row">
                            <div class="col-md-4 col-lg-2 mb-4">
                                <div class="bg-light course-profile p-2 text-center">
                                    <div class="preview">
                                        <img class="w-100" src="https://placehold.it/300x200" alt="">
                                        <a href="https://placehold.it/300x200">Preview</a>
                                    </div>
                                    <h5 class="mt-3">PMP Course</h5>
                                    <a class="btn btn-primary rounded-pill btn-sm py-0 px-2" href="#"><em><small>Download</small></em></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
