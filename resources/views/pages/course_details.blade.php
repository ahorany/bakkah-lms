@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
    <?php
    $video = null;
    $image = null;

    if($course->uploads){
        foreach ($course->uploads as $file){
            if($file->post_type == 'intro_video' ){
                $video = $file;
            }else if($file->post_type == 'image'){
                $image = $file;
            }
        }
    }
    ?>

                <div class="dash-header course-header d-flex align-items-md-center flex-column flex-md-row">
                    <div class="text-center course-image w-30 mb-4 mb-md-0">
                        @if($image)
                            <img  src="{{CustomAsset('upload/thumb200/'.$image->file)}}">
                        @endif

                        <div class="progress">
                            <div style="width: {{$course->users[0]->pivot->progress??0}}% !important;" class="bar"></div>
                        </div>
                        <small>{{$course->users[0]->pivot->progress??0}}% Complete</small>
                    </div>
                    <div class="mx-md-4">
                        <ol class="breadcrumb">
                            <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li>
                            <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li>
                            <li> {{$course->trans_title}}</li>
                        </ol>
                        <h1>{{$course->trans_title}}</h1>

                        <div class="rating">
                            <span lass="total_rate">{{round($total_rate,1)}}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="17.43" height="16.6"
                                viewBox="0 0 17.43 16.6">
                                <path id="Path_39" data-name="Path 39"
                                    d="M88.211,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.753,5.35,5.934,1.018L87.348,194Z"
                                    transform="translate(-74.153 -183.355)" fill="#fb4400" />
                            </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" width="17.43" height="16.6"
                                viewBox="0 0 17.43 16.6">
                                <path id="Path_39" data-name="Path 39"
                                    d="M88.211,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.753,5.35,5.934,1.018L87.348,194Z"
                                    transform="translate(-74.153 -183.355)" fill="#fb4400" />
                            </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" width="17.43" height="16.6"
                                viewBox="0 0 17.43 16.6">
                                <path id="Path_39" data-name="Path 39"
                                    d="M88.211,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.753,5.35,5.934,1.018L87.348,194Z"
                                    transform="translate(-74.153 -183.355)" fill="#fb4400" />
                            </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" id="Group_32" data-name="Group 32"  width="17.43"
                                height="16.6" viewBox="0 0 17.43 16.6">
                                <path id="Path_43" data-name="Path 43"
                                    d="M160.391,199.955l-5.375-2.706-5.394,2.66.91-5.948-4.2-4.313,5.938-.966,2.805-5.326,2.758,5.35,5.929,1.018L159.528,194Z"
                                    transform="translate(-146.334 -183.355)" fill="#c6c6c6" />
                                <path id="Path_44" data-name="Path 44"
                                    d="M155.025,183.4l-2.753,5.228-5.938.966,4.2,4.313-.91,5.948,5.394-2.66.009,0Z"
                                    transform="translate(-146.334 -183.298)" fill="#fb4400" />
                            </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" width="17.43" height="16.6"
                                viewBox="0 0 17.43 16.6">
                                <path id="Path_42" data-name="Path 42"
                                    d="M142.346,199.955l-5.375-2.706-5.4,2.66.915-5.948-4.2-4.313,5.938-.966,2.8-5.326,2.753,5.35,5.934,1.018L141.483,194Z"
                                    transform="translate(-128.289 -183.355)" fill="#c6c6c6" />
                            </svg>

                        </div>
{{--                        <a href="#" class="btn btn-primary px-4">Resume Course</a>--}}
                    </div>
                </div>

                <div class="row my-4">
                    <div class="col-lg-8 col-xl-9">
                        <p class="lead light">{{$course->trans_excerpt}}</p>
                    </div>
                    @if($video)
                      <div class="col-lg-4 col-xl-3">
                        <div class="card h-100 justify-content-center align-items-center p-5 video-btn">
                            <button><svg xmlns="http://www.w3.org/2000/svg" width="26.818" height="30.542"
                                    viewBox="0 0 26.818 30.542">
                                    <path id="Path_92" data-name="Path 92" d="M1586.871,1164.139V1133.6l26.818,15.165Z"
                                        transform="translate(-1586.871 -1133.597)" fill="#fff" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

         @if($video)
                <div class="modal">
                    <div class="modal-content">
                        <div class="modal-close">x</div>
                        <video width="100%" oncontextmenu="return false;" controls="controls" controlslist="nodownload" src="{{CustomAsset('upload/video/'.$video->file)}}" class="embed-responsive-item"></video>
                    </div>
                </div>
         @endif


        @if ($course->contents)
          <div class="row mt-3 course-content">
                    <div class="col-12">
                        <h3>CONTENT</h3>
                    </div>
                    <div class="col-lg-8 mb-5 mb-lg-0">

{{--                        <div class="card">--}}
{{--                            <a href="#" class="text-black">--}}
{{--                                <div class="d-flex align-items-center p-20">--}}
{{--                                    <img src="assets/images/video.png" alt="">--}}

{{--                                    <h2 class="light mx-4">Self Study Tour Video </h2>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
                 @foreach($course->contents as $key => $section)
                        <div class="card learning-file mt-5">
                            <h2>{{$section->title}}</h2>
                            <div style="margin: 0px 40px;">{!!  $section->details->excerpt??null !!}</div>
                            @isset($section->contents)
                                <ul>
                                    @foreach($section->contents as $k => $content)
                                        <?php
                                        $class = 'fas fa-file';
                                        switch($content->post_type){
                                            case "video" :  $class = 'fas fa-video';    break;
                                            case "presentation" :  $class = 'fas fa-file-powerpoint';   break;
                                            case "exam" :  $class = 'fas fa-question-circle';  break;
                                        }

                                        ?>
                                        <li>
                                            <a @if( ( isset($section->contents[($k-1)]->user_contents[0]) || ( isset($course->contents[($key-1)])  && isset($course->contents[($key-1)]->contents[ (count($course->contents[($key-1)]->contents) - 1)]->user_contents[0]) && $k == 0  ) )  || ($content->status == 1)  )     href=" @if($content->post_type != 'exam') {{CustomRoute('user.course_preview',$content->id)}} @else {{CustomRoute('user.exam',$content->id)}} @endif" @else style="color: #c1bebe" href="#"  onclick="return false"  @endif >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28.126" height="28.127"
                                                    viewBox="0 0 28.126 28.127">
                                                    <g id="Group_39" data-name="Group 39" transform="translate(-92 -5.528)">
                                                        <path id="Path_52" data-name="Path 52"
                                                            d="M111.447,9.044H108.48V8.9a1.593,1.593,0,0,0-1.592-1.592H105.87a2.973,2.973,0,0,0-2.72-1.783h-.106a2.973,2.973,0,0,0-2.72,1.783H99.305A1.594,1.594,0,0,0,97.713,8.9v.141H94.747c-1.72,0-2.747.822-2.747,2.2V30.908a2.75,2.75,0,0,0,2.747,2.747h16.7a2.75,2.75,0,0,0,2.747-2.747V18.474a.549.549,0,1,0-1.1,0V30.908a1.65,1.65,0,0,1-1.648,1.648h-16.7A1.65,1.65,0,0,1,93.1,30.908V11.241c0-.272,0-1.1,1.648-1.1h2.966v.769a.549.549,0,0,0,.549.549h9.668a.549.549,0,0,0,.549-.549v-.769h2.966a1.65,1.65,0,0,1,1.648,1.648V13.64a.549.549,0,1,0,1.1,0V11.79a2.75,2.75,0,0,0-2.747-2.747Zm-4.065,1.318h-8.57V8.9a.494.494,0,0,1,.493-.493h1.406a.55.55,0,0,0,.53-.406,1.87,1.87,0,0,1,1.8-1.377h.106A1.871,1.871,0,0,1,104.952,8a.549.549,0,0,0,.53.406h1.407a.493.493,0,0,1,.493.493Zm0,0"
                                                            fill="#bdbdbd" />
                                                        <path id="Path_53" data-name="Path 53"
                                                            d="M399.748,149.137a.549.549,0,1,0,.388.16.553.553,0,0,0-.388-.16Zm0,0"
                                                            transform="translate(-286.104 -133.748)" fill="#bdbdbd" />
                                                        <path id="Path_54" data-name="Path 54"
                                                            d="M486.151,263.13a.549.549,0,1,0,.388.161.553.553,0,0,0-.388-.161Zm0,0"
                                                            transform="translate(-366.574 -239.913)" fill="#bdbdbd" />
                                                        <path id="Path_55" data-name="Path 55"
                                                            d="M438.965,96.179l-1.758-3.928a.55.55,0,0,0-1,0l-1.758,3.928a.548.548,0,0,0-.048.224v16.4a1.313,1.313,0,0,0,1.312,1.312H437.7a1.313,1.313,0,0,0,1.312-1.312V106.63a.549.549,0,0,0-1.1,0v3.841H435.5V96.953h2.417v5.07a.549.549,0,1,0,1.1,0V96.4a.548.548,0,0,0-.048-.224Zm-2.259-2.359.91,2.034h-1.821Zm1.209,17.75v1.238a.213.213,0,0,1-.213.213H435.71a.213.213,0,0,1-.213-.213V111.57Zm0,0"
                                                            transform="translate(-318.886 -80.465)" fill="#bdbdbd" />
                                                        <path id="Path_56" data-name="Path 56"
                                                            d="M154.842,120.727a2.307,2.307,0,1,0,2.307,2.307,2.31,2.31,0,0,0-2.307-2.307Zm0,3.516a1.209,1.209,0,1,1,1.209-1.208,1.21,1.21,0,0,1-1.209,1.208Zm0,0"
                                                            transform="translate(-56.378 -107.289)" fill="#bdbdbd" />
                                                        <path id="Path_57" data-name="Path 57"
                                                            d="M154.842,216.727a2.307,2.307,0,1,0,2.307,2.307,2.31,2.31,0,0,0-2.307-2.307Zm0,3.516a1.209,1.209,0,1,1,1.209-1.208,1.21,1.21,0,0,1-1.209,1.208Zm0,0"
                                                            transform="translate(-56.378 -196.696)" fill="#bdbdbd" />
                                                        <path id="Path_58" data-name="Path 58"
                                                            d="M154.842,312.727a2.307,2.307,0,1,0,2.307,2.307,2.31,2.31,0,0,0-2.307-2.307Zm0,3.516a1.209,1.209,0,1,1,1.209-1.208,1.21,1.21,0,0,1-1.209,1.208Zm0,0"
                                                            transform="translate(-56.378 -286.104)" fill="#bdbdbd" />
                                                        <path id="Path_59" data-name="Path 59"
                                                            d="M253.782,171.926h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0"
                                                            transform="translate(-144.295 -154.972)" fill="#bdbdbd" />
                                                        <path id="Path_60" data-name="Path 60"
                                                            d="M253.782,133.528h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0"
                                                            transform="translate(-144.295 -119.211)" fill="#bdbdbd" />
                                                        <path id="Path_61" data-name="Path 61"
                                                            d="M253.782,267.926h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0"
                                                            transform="translate(-144.295 -244.38)" fill="#bdbdbd" />
                                                        <path id="Path_62" data-name="Path 62"
                                                            d="M253.782,229.528h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0"
                                                            transform="translate(-144.295 -208.618)" fill="#bdbdbd" />
                                                        <path id="Path_63" data-name="Path 63"
                                                            d="M253.782,363.926h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0"
                                                            transform="translate(-144.295 -333.788)" fill="#bdbdbd" />
                                                        <path id="Path_64" data-name="Path 64"
                                                            d="M253.782,325.528h-6.3a.549.549,0,0,0,0,1.1h6.3a.549.549,0,1,0,0-1.1Zm0,0"
                                                            transform="translate(-144.295 -298.026)" fill="#bdbdbd" />
                                                    </g>
                                                </svg>
                                                <span> {{$content->title}}</span> </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endisset
                        </div> <!-- /.learning-file -->
                 @endforeach
               </div>

                    <div class="col-lg-4">
                        <div class="card p-30 activity">
                            <h2>Activity</h2>
{{--                            <ul>--}}
{{--                                <li>Lean Six Sigma Yellow belt training provides insight to the methodology for process--}}
{{--                                    improvement, tools and techniques of Six Sigma.</li>--}}
{{--                                <li>Lean Six Sigma Yellow belt training provides insight to the methodology for process--}}
{{--                                    improvement, tools and techniques of Six Sigma.</li>--}}
{{--                                <li>Lean Six Sigma Yellow belt training provides insight to the methodology for process--}}
{{--                                    improvement, tools and techniques of Six Sigma.</li>--}}
{{--                                <li>Lean Six Sigma Yellow belt training provides insight to the methodology for process--}}
{{--                                    improvement, tools and techniques of Six Sigma.</li>--}}
{{--                            </ul>--}}
                        </div>

{{--                        <div class="d-flex justify-content-between mt-5 course-group">--}}
{{--                            <div class="persons card p-20">--}}
{{--                                <h3 class="mt-0">Course Group</h3>--}}
{{--                                <!-- <small>Lean Six Sigma Yellow belt training provides insight to the </small> -->--}}
{{--                                <ul>--}}
{{--                                    <li><img src="http://placehold.it/50x50" alt=""></li>--}}
{{--                                    <li><img src="http://placehold.it/50x50" alt=""></li>--}}
{{--                                    <li><img src="http://placehold.it/50x50" alt=""></li>--}}
{{--                                    <li><img src="http://placehold.it/50x50" alt=""></li>--}}
{{--                                    <li><img src="http://placehold.it/50x50" alt=""></li>--}}
{{--                                    <li><img src="http://placehold.it/50x50" alt=""></li>--}}
{{--                                    <li>+15</li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                            <div class="new-students">--}}
{{--                                <span>New Student</span>--}}
{{--                                <b>12</b>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
        @endif
@endsection

@section('script')
    <script>
        var btn = document.querySelector('.video-btn');
        var modal = document.querySelector('.modal');
        var content = document.querySelector('.modal-content');
        var close = document.querySelector('.modal-close');

        btn.onclick = e => {
            modal.querySelector('video').play();
            modal.classList.add("show");
        };

        close.onclick = e => {
            modal.querySelector('video').pause();
            modal.classList.remove("show");
        };

        modal.onclick = e => {
            modal.querySelector('video').pause();
            modal.classList.remove("show");
        };

        content.onclick = e => e.stopPropagation()
    </script>
@endsection