@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
<style>
.userarea-wrapper{
    background: #fafafa;
}
.user-course .my-courses {
    border: 1px solid gainsboro;
    background: #fff;
    padding: 20px;
}
.card.my-badge {
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
    <?php
        $url = '';
        if(auth()->user()->upload) {
            $url = auth()->user()->upload->file;
            $url = CustomAsset('upload/full/'. $url);
        }else {
            $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
        }
    ?>
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content">
                    <div class="p-5 user-course">
                        <h4 class="mb-4">
                            {{ __('education.Course Overview') }}
                        </h4>
                        <div class="row mx-0 my-courses">
                            @foreach($courses->courses as $course)
                                <div class="col-6 col-md-3 col-lg-3 my-2 p-4">
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
                    <div class="px-5 pb-5 user-badge">
                        <h4 class="mb-0">
                            {{ __('education.Badges') }}
                        </h4>
                        <small class="num m-0 mt-2 mb-4 d-block" style="color:gray;">Your Latest Achievements</small>
                        <div class="card my-badge p-4" style="display:flex; flex-direction:row;">
                            <div style="text-align: center; width: 6%; margin: 0 10px;">
                                <img class="img-fluid" src="{{CustomAsset('/images/lms1.png')}}" alt="Card image cap">
                            </div>
                            <div style="text-align: center; width: 6%; margin: 0 10px;">
                                <img class="img-fluid" src="{{CustomAsset('/images/lms2.png')}}" alt="Card image cap">
                            </div>
                            <div style="text-align: center; width: 6%; margin: 0 10px;">
                                <img class="img-fluid" src="{{CustomAsset('/images/lms3.png')}}" alt="Card image cap">
                            </div>
                            <div style="text-align: center; width: 6%; margin: 0 10px;">
                                <img class="img-fluid" src="{{CustomAsset('/images/lms4.png')}}" alt="Card image cap">
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

@endsection
