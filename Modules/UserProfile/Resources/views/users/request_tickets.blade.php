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
                        <h4>{{ __('education.support') }}</h4>
                        <div class="row my-4 request_tickets">
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="#">
                                    <div class="card user-badge p-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{CustomAsset('images\support1.png')}}" alt="">
                                            <p class="my-0 lead mx-2 text-center w-100" style="color:#000">Chat</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="#">
                                    <div class="card user-badge p-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{CustomAsset('images\support2.png')}}" alt="">
                                            <p class="my-0 lead mx-2 text-center w-100" style="color:#000">Technical Support</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="{{ route('user.my_complaints','my_complaints') }}">
                                    <div class="card user-badge p-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{CustomAsset('images\support3.png')}}" alt="">
                                            <p class="my-0 lead mx-2 text-center w-100" style="color:#000">Compliant</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="{{ route('user.my_complaints','my_refunds') }}">
                                    <div class="card user-badge p-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{CustomAsset('images\support4.png')}}" alt="">
                                            <p class="my-0 lead mx-2 text-center w-100" style="color: #000">Refund Request</p>
                                        </div>
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
