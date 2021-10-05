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

                                    <div class="col-12 col-md-4">
                                        <div class="card">
                                            @isset($course->upload->file)
                                              <img class="card-img-top" src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" alt="Card image cap">
                                            @endisset
                                            <div class="card-body">
                                                <h5 class="card-title">{{$course->trans_title}}</h5>
{{--                                                <p class="card-text">{{$course->trans_excerpt}}</p>--}}
{{--                                                <a href="#" class="btn btn-primary">Reset course</a>--}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-8">

                                        <div class="card pt-3 pl-3" >
                                            <h5 class="card-title">Course Description</h5>

                                            <div class="card-body">
                                                <p class="card-text">{{$course->trans_excerpt}}</p>
                                            </div>
                                        </div>

                                </div>

{{--                                 <div class="col-12 col-md-4"></div>--}}
                                    <div class="col-12 col-md-8 offset-md-4">
                                        @foreach($course->contents as $section)
                                        <div class="card pt-3 pl-3" >
                                            <h5 class="card-title">{{$section->title}}</h5>

                                            <div class="card-body">
                                                @isset($section->contents)
                                                    @foreach($section->contents as $content)
                                                        <p class="card-text"><a   target="_blank"   href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.exam',$content->id)}} @endif"><i class="fas fa-check"></i> {{$content->title}}</a></p>
                                                    @endforeach
                                                @endisset
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>

                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

