@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.My Courses') }}</h4>
                            <div class="row">
                                @foreach($courses->courses as $course)
                                    <div class="col-12 col-md-4">
                                        <div class="card" style="width: 18rem;">
                                            @isset($course->upload->file)
                                              <img class="card-img-top" src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" alt="Card image cap">
                                            @endisset
                                            <div class="card-body">
                                                <h5 class="card-title">{{$course->trans_title}}</h5>
{{--                                                <p class="card-text">{{$course->trans_excerpt}}</p>--}}
                                                <a href="{{CustomRoute('user.course_details',$course->id)}}" class="btn btn-primary">Show Details</a>


                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
