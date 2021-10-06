@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(72)??null])
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <h4>{{ __('education.invoice') }}</h4>
                        <div class="row mt-3">
                            <div class="col-md-2 col-lg-2 col-12 mb-4 px-3 card-referral">
                                <div class="invoice referral course-profile px-3 py-2 text-center">
                                    <p class="invoice m-0">Invoice</p>
                                    <h5 class="mb-0 mt-4">Prince2 Course</h5>
                                    <p class="mb-4 date">27/7/2021</p>
                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-12 mb-4 px-3 card-referral">
                                <div class="invoice referral course-profile px-3 py-2 text-center">
                                    <p class="invoice m-0">Invoice</p>
                                    <h5 class="mb-0 mt-4">Prince2 Course</h5>
                                    <p class="mb-4 date">27/7/2021</p>
                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
