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
                        <h4>{{ __('education.referral') }}</h4>
                        <div class="row mt-3">
                            <div class="col-md-12 col-lg-12 col-12 mb-4 px-2">
                                <div class="referral-header">
                                    <div class="row m-0">
                                    <div class="col-md-2 col-lg-2 col-12 p-0" style="background: #fb4400; color: #fff;">
                                        <div class="code">
                                            <p class="m-0">Your Referral Code</p>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-8 col-12 px-5">
                                        <div class="username px-0">
                                            <p class="m-0">username552587</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-12 p-0">
                                        <div class="social_icon">
                                            <a href="#" style="background: #0181cc"><i class="fab fa-facebook-f"></i></a>
                                            <a href="#" style="background: #02bcdf"><i class="fab fa-twitter"></i></a>
                                            <a href="#" style="background: #0181cc"><i class="fab fa-linkedin-in"></i></a>
                                            <a href="#" style="background: #d60000"><i class="fab fa-youtube"></i></a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-12 px-0 mb-2" style="border-bottom: 2px solid #efefef;">
                                <h4 class="p-2" style="color: #fb4400"><i class="fas fa-circle"></i> Who used my Referral Code</h4>
                                        <div class="row m-0">
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number percentage m-2 p-2 position-relative">
                                                        <p class="m-0">10%</p>
                                                        {{-- <span class="position-absolute">SAR</span> --}}
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number percentage m-2 p-2 position-relative">
                                                        <p class="m-0">10%</p>
                                                        {{-- <span class="position-absolute">SAR</span> --}}
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number percentage m-2 p-2 position-relative">
                                                        <p class="m-0">10%</p>
                                                        {{-- <span class="position-absolute">SAR</span> --}}
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number percentage m-2 p-2 position-relative">
                                                        <p class="m-0">10%</p>
                                                        {{-- <span class="position-absolute">SAR</span> --}}
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number percentage m-2 p-2 position-relative">
                                                        <p class="m-0">10%</p>
                                                        {{-- <span class="position-absolute">SAR</span> --}}
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-12 px-0">
                                <h4 class="p-2" style="color: #fb4400"><i class="fas fa-circle"></i> Cash Back</h4>
                                        <div class="row m-0">
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number m-2 p-2 position-relative">
                                                        <p class="m-0">10</p>
                                                        <span class="position-absolute">SAR</span>
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number m-2 p-2 position-relative">
                                                        <p class="m-0">10</p>
                                                        <span class="position-absolute">SAR</span>
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-2 col-6 mb-4 px-2 card-referral">
                                                <div class="certifications referral course-profile px-3 py-2 text-center">
                                                    <div class="number m-2 p-2 position-relative">
                                                        <p class="m-0">10</p>
                                                        <span class="position-absolute">SAR</span>
                                                    </div>
                                                    <div class="preview">
                                                        <img class="w-100" src="{{CustomAsset('images\prince2.png')}}" alt="">
                                                        <a href="https://placehold.it/300x200">Preview</a>
                                                    </div>
                                                    <p class="my-b date">27 / 7 / 2021</p>
                                                    <a class="download details d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="#"><em><small>Details</small></em></a>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
