@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{ __('education.Certifications') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <div class="d-flex upload-certification">
                            <h4><i class="fas fa-certificate"></i> {{ __('education.Certifications') }}</h4>
                            {{-- <button class="ml-auto"><i class="fas fa-cloud-upload-alt"></i> Upload Proffisional Certification</button> --}}
                        </div>
                        <div class="row mt-3 mx-0">

                            @include('training.certificates.certificate.certificate_body')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
