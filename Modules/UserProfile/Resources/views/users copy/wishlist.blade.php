@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{ __('education.Wishlists') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-4 user-info">
                        <h2 class="mb-4"><i class="far fa-heart"></i> {{ __('education.Wishlists') }}</h2>
                        @if ($user->wishlists->count() > 0)
                            <div class="row mb-4">
                                @foreach ($user->wishlists as $wishlist)
                                <div class="col-md-4 col-lg-2 mb-4">
                                    <div class="bg-light course-profile p-2 text-center">
                                        <a href="{{route('education.courses.single', $wishlist->trainingOption->course->slug??null)}}">
                                            <img class="w-100" src="{{CustomAsset('upload/thumb200/'.$wishlist->trainingOption->course->upload->file)}}" alt="">
                                        </a>
                                        <a href="{{route('education.courses.single', $wishlist->trainingOption->course->slug??null)}}">
                                            <h6 class="mt-3">{{$wishlist->trainingOption->course->trans_title}}</h6>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                        <div>
                            <a class="btn btn-primary" href="{{route('education.courses')}}">{{__('education.Explore courses')}}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
