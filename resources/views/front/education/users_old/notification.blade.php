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
                        <h2>{{ __('education.All Notifications') }}</h2>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center bg-light p-3">
                                    <i class="far fa-bell fa-bell-slash fa-2x main-color"></i>
                                    <div class="mx-3 w-100">
                                        <div class="d-lg-flex align-items-center justify-content-between">
                                            <h3 class="m-0">Course Start</h3>
                                            <small class="text-secondary"><em>10:00pm | 20/10/2020</em></small>
                                        </div>
                                        <p class="m-0 text-secondary">Your PMP course will start in 30 min. Be ready</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center bg-light p-3">
                                    <i class="far fa-bell fa-bell-slash fa-2x main-color"></i>
                                    <div class="mx-3 w-100">
                                        <div class="d-lg-flex align-items-center justify-content-between">
                                            <h3 class="m-0">Course Start</h3>
                                            <small class="text-secondary"><em>10:00pm | 20/10/2020</em></small>
                                        </div>
                                        <p class="m-0 text-secondary">Your PMP course will start in 30 min. Be ready</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center bg-light p-3">
                                    <i class="far fa-bell fa-2x main-color"></i>
                                    <div class="mx-3 w-100">
                                        <div class="d-lg-flex align-items-center justify-content-between">
                                            <h3 class="m-0">Course Start</h3>
                                            <small class="text-secondary"><em>10:00pm | 20/10/2020</em></small>
                                        </div>
                                        <p class="m-0 text-secondary">Your PMP course will start in 30 min. Be ready</p>
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
