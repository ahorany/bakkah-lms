@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
<style>
    .user-info .my-courses {
    border: 1px solid gainsboro;
    background: #fff;
    padding: 50px;
}
.line {
    width: 100%;
    background: #fb4400;
    height: 4px;
}
</style>
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="p-5 user-info">
                        <h4 class="mb-4">
                            <!-- <i class="fas fa-graduation-cap"></i>  -->
                            {{ __('education.Course Overview') }}
                        </h4>
                        <div class="row mx-0 my-courses">
                            @foreach($courses->courses as $course)
                                <div class="col-6 col-md-4 col-lg-4 px-4">
                                    <div class="card p-4" style="width: 100%; border-radius: 10px; border: 1px solid #f2f2f2">
                                        @isset($course->upload->file)
                                        <img class="card-img-top" src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" alt="Card image cap">
                                        @endisset
                                        <div class="card-body text-center p-0">
                                            <h3 class="card-title my-2" style="font-weight:700;">{{$course->trans_title}}</h3>
                                            <div class="rate">
                                                <div class="line"></div>
                                                <small class="num m-0 mt-2" style="color:gray;">25% Complete</small>
                                            </div>
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
