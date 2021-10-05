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
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.Exam') }}</h4>
                        <div class="row">

                            <div class="col-12 col-md-4">
                                <div class="card">
{{--                                    @isset($course->upload->file)--}}
{{--                                        <img class="card-img-top" src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" alt="Card image cap">--}}
{{--                                    @endisset--}}
                                    <div class="card-body">
                                        <?php $users_exams_count = count($exam->exam->users_exams) ?>
                                        <h5 class="card-title">title : {{$exam->title}}</h5>
                                        <p class="card-title">start date :{{$exam->exam->start_date}}</p>
                                        <p class="card-title">end date :{{$exam->exam->end_date}}</p>
                                        <p class="card-title">duration :{{$exam->exam->duration}}</p>
                                        <p class="card-title">exam attempt count :{{$exam->exam->attempt_count}}</p>
                                        <p class="card-title">your attempts  :{{$users_exams_count}}</p>
                                        <p class="card-title">mark  : -- </p>

                                        @if($users_exams_count == 0)
                                                <p class="text-warning">No Attempts</p>
                                                <a href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Start Attempt</a>
                                         @elseif($exam->exam->users_exams[$users_exams_count-1]->status == 0)
                                                <a href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Return to Exam</a>

                                            @elseif($users_exams_count < $exam->exam->attempt_count && $exam->exam->users_exams[$users_exams_count-1]->status == 1)
                                                <a onclick="confirmNewAttempt()" href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Start New Attempt</a>
                                           @else
                                                <p class="text-danger">All your attempts are over</p>
                                            @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-8">

                                <div class="card pt-3 pl-3" >
                                    <h5 class="card-title">Exam Description</h5>

                                    <div class="card-body">
                                        <p class="card-text">{{$exam->details->excerpt}}</p>
                                    </div>
                                </div>

                            </div>

                            {{--                                 <div class="col-12 col-md-4"></div>--}}
                            <div class="col-12 col-md-8 offset-md-4">
{{--                                @foreach($course->contents as $section)--}}
{{--                                    <div class="card pt-3 pl-3" >--}}
{{--                                        <h5 class="card-title">{{$section->title}}</h5>--}}

{{--                                        <div class="card-body">--}}
{{--                                            @isset($section->contents)--}}
{{--                                                @foreach($section->contents as $content)--}}
{{--                                                    <p class="card-text"><a   target="_blank"   href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.preview.exam',$content->id)}} @endif"><i class="fas fa-check"></i> {{$content->title}}</a></p>--}}
{{--                                                @endforeach--}}
{{--                                            @endisset--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        function confirmNewAttempt(){
           if( confirm('Are u sure ?') == false)
               event.preventDefault()
        }

    </script>
@endsection

